<?php
session_start();
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");

require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
//agregado nuevo
require_once("../../config.ini.php");
require_once('../../../generalp.config.inc.php');
require_once('../../../includes/clases/BDControlador.php');

$almacen = new Almacen();

$login = new Login();


$bdprecios =  new Almacen();
$bddat =  new Almacen();	
$bdPyme =  new Almacen();
$bdPpyme =  new Almacen();
$bdPos =  new Almacen();
$bdPos_remotop=  new Almacen();
$ConexionError=0;



/*$bdprecios =  new BDControlador();
$bddat =  new BDControlador();	
$bdPyme =  new BDControlador();
$bdPpyme =  new BDControlador();
*/
//$bdPos =  new BDControlador();
$pos=POS;
$bdPos->Execute2("UPDATE $pos.people SET VISIBLE=1 WHERE ID IN (SELECT id_people FROM $pos.people_caja)");

//conexion para obtener la fecha para la sincronizacion por ventana de bloqueo
$campos_fechaBD =$almacen->ObtenerFilasBySqlSelect("SELECT * FROM control_sincronizacion
            WHERE fecha_cierre_debido = ( 
            SELECT MAX( `fecha_cierre_debido` ) 
            FROM control_sincronizacion )");
if(!empty($_POST["bandera"])){
$bandera=$_POST["bandera"];}else{
$bandera=$_GET["bandera"];
}

//if para separar lo que se carga por el archivo php de lo que se cargara por el ajax
if ($_POST["generar"] == "si" || $_GET["generar"]=='si') {	
	$usuario = $login->getUsuario();
	$usuario1 = $login->getIdUsuario();
	$fechaBD=$campos_fechaBD[0]["fecha_cierre_debido"];
	//fecha de prueba quitar cuando termine la prueba
	//$fechaBD="2015-02-10";
	//fecha de la BD donde se sincronizacion
	$fechaAyer=strftime( "%Y-%m-%d",strtotime("-1 day"));
	$fechaHoy=date("Y-m-d");
	$sincroHoy=0;
        
	if($fechaHoy==$fechaBD){
	 	//bandera para indicar que ya se sincronizo por hoy, se pasa al ajax para mostrar mjs
	 	$sincroHoy=1;
	 	?>
			<input type="hidden" id="sincroHoy" value="<?php echo $sincroHoy ?>">
		<?php
		exit();

	}//fin de fechaHoy

//caso 1 sincornizacion desde el boton***************************
	if($bandera==0 ){
		
		$usuario = $login->getUsuario();
		$horaActual= date("H:i:s");
		//$horaActual= "11:28:59";
		//variable para pasar al archivo tpl
		 $horaMenor=0;

		if($horaActual < "12:00:00"){
			$horaMenor=1;
			?>
			<input type="hidden" id="horaMenor" value="<?php echo $horaMenor ?>">
	 	<?php	 	
	 	exit();
	    }

	    try{

	    
	    	//sincronizacion desde el pos al pyme+++++++++++++++++++++
                        $desde=date("Y-m-d")." 00:00:00";
			$hasta=date("Y-m-d")." 23:59:00";
			$fecha=date("Y-m-d");
                        
//                       $desde="2015-02-26 00:00:00";
//                       $hasta="2015-02-26 23:00:00";
//                       $fecha="2015-02-26";
	    	//conexion para la base de datos del pos
                       


			//busco las ventas diarias en stockdiary del pos
                       //echo "SELECT PRODUCT, sum(UNITS) as CANT, PRICE FROM $pos.stockdiary WHERE DATENEW BETWEEN '$desde' and '$hasta' GROUP BY PRODUCT";exit();
			$bdPos->BeginTrans();
                        $fetch=$bdPos->ObtenerFilasBySqlSelect("SELECT PRODUCT, sum(UNITS) as CANT, PRICE FROM $pos.stockdiary WHERE DATENEW BETWEEN '$desde' and '$hasta' GROUP BY PRODUCT"); 
                        //print_r($fetch);exit();
                       
                        if(count($fetch)>0){
                       // echo "INSERT INTO kardex_almacen (tipo_movimiento_almacen, autorizado_por, observacion, fecha, usuario_creacion, fecha_creacion, estado, fecha_ejecucion, id_documento) VALUES ('2', '$usuario', 'Salida por Ventas', '".$fechaBD."', '$usuario', '".date("Y-m-d H:i:s")."', 'Entregado', '".date("Y-m-d H:i:s")."','0')";exit();
                        $bdPos->ExecuteTrans("INSERT INTO kardex_almacen (tipo_movimiento_almacen, autorizado_por, observacion, fecha, usuario_creacion, fecha_creacion, estado, fecha_ejecucion, id_documento) VALUES ('2', '$usuario', 'Salida por Ventas', '".$fecha."', '$usuario', '".date("Y-m-d H:i:s")."', 'Entregado', '".date("Y-m-d H:i:s")."','0')");
		    	
                                
                                $i=0;
                                while($i < count($fetch)){ 
	   				$itemF=$bdPos->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE  itempos='".$fetch[$i][PRODUCT]."'");
					$item = $itemF[0]["id_item"];
		  					
		  			$ubF=$bdPos->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE  puede_vender='1'");
		  			$idUbic = $ubF[0]["id"];	  			
					$canti=$fetch[$i][CANT]*(-1);
					$bdPos->ExecuteTrans("INSERT INTO kardex_almacen_detalle (id_transaccion, id_almacen_salida, id_item, cantidad, id_ubi_salida, precio) VALUES ('$id_kardex', '1', '$item', '".$canti."', '".$idUbic."', '".$fetch[$i][PRICE]."')");
		  			$campos = $bdPos->ObtenerFilasBySqlSelect("select * from item_existencia_almacen where id_item  = '$item' and id_ubicacion  = '$idUbic' ");
					$cantidadExistente = $campos[0][cantidad];
					$codAlmacen = $campos[0][cod_almacen];
					$bdPos->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($cantidadExistente - $canti) . "' where id_item  = '$item' and cod_almacen = '$codAlmacen' and id_ubicacion='$idUbic'");
					
					//echo "<br>";
				$i++;}
                                
                                
                                
                                //************FIN DE ACTUALIZAR VENTAS POR PYME****************************
                                //******--------AHORA COMIENZA LA ACTUALIZACION HACIA EL CENTRAL DE LAS VENTAS.---------------*************
//                                $llego=1;
//                                $fortinet=$bdPos->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
//                                $fortinet= $fortinet[0]["fortinet"];                                  
//                              
//                                if($llego==1 && $fortinet==1){
//                                $pos = POS;
//                                //funcion nueva en la clase conexion comun para conectar remotamente
//                                
//                                $bdPos_remotop= new Almacen(DB_HOST,DB_USUARIO,DB_CLAVE,BASEDEDATOS);
//                                $bdPos_remotop->BeginTrans();
//                                $codigo_siga=$bdPos_remotop->ObtenerFilasBySqlSelect("SELECT codigo_siga FROM parametros_generales");
//                                $siga=$codigo_siga[0]["codigo_siga"];
//                                $inicio=date("Y-m-d")." 00:00:00";
//                                $final=date("Y-m-d")." 23:00:00";
//                                $fecha=date("Y-m-d");
////                                $inicio="2015-02-26 00:00:00";
////                                $final="2015-02-26 23:00:00";
////                                $fecha="2015-02-26";
//                                
//                                // $conet= $bdPos_remotopp=new Almacen(DB_HOSTP,DB_USUARIOP,DB_CLAVEP,DB_SELECTRA_PYMEP,true);
//                                $bdPos_remotopp=new Almacen();
//                                $conet=$bdPos_remotopp->conexionRemota_central(DB_HOSTP,DB_USUARIOP,DB_CLAVEP,DB_SELECTRA_PYMEP);
//                                
//                                $bdPos_remotopp->BeginTrans();
//
//                                $rowdat=$bdPos_remotop->ObtenerFilasBySqlSelect("SELECT products.REFERENCE,
//                                                                        products.NAME,
//                                                                        products.CODE,
//                                                                        ticketlines.PRICE AS PRICESELL,
//                                                                        taxes.RATE,
//                                                                        sum(ticketlines.UNITS) as UNITS
//                                                                        FROM $pos.products INNER JOIN
//                                                                        $pos.ticketlines ON products.ID = ticketlines.PRODUCT INNER JOIN
//                                                                        $pos.tickets ON ticketlines.TICKET = tickets.ID 
//                                                                        INNER JOIN $pos.taxes ON ticketlines.TAXID = taxes.ID 
//                                                                        WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
//                                                                        GROUP BY products.NAME,ticketlines.PRICE 
//                                                                        ORDER BY NAME");
//                                                                      // echo count($rowdat);exit();
//                                                                        
//                                        $ir=0;
//
//                                        if ($conet==false) {
//                                          $ConexionError=1;
//                                          ?>
<!--                                            <input type="hidden" id="conexion" value="//<?php //echo $ConexionError ?>">-->
                                          //<?php   
//                                           exit();
//                                        }
//                                                     
//                                        while($ir < count($rowdat))
//                                        {
//                                             $bdPos_remotopp->ExecuteTrans("INSERT INTO vproducto (fecha, siga, referencia, nombre, codigo_barra, precio, iva, unidad) VALUES ('$fecha', '$siga', '".$rowdat[$ir][REFERENCE]."', '".$rowdat[$ir][NAME]."', '".$rowdat[$ir][CODE]."', '".$rowdat[$ir][PRICESELL]."', '".$rowdat[$ir][RATE]."', '".$rowdat[$ir][UNITS]."')");
//                                             $ir++;
//                                        }
//                                        
//                                        
//                                        
//                                        
//                                        //SEGUNDO SUBIMOS AL SERVIDOR CENTRAL LA INFORMACION DE LOS CAJEROS
//                                        $rowdat=$bdPos_remotop->ObtenerFilasBySqlSelect("SELECT people.NAME,
//                                        payments.PAYMENT,
//                                        sum(payments.TOTAL) as TOTAL
//                                             FROM $pos.payments INNER JOIN
//                                        $pos.receipts ON receipts.ID = payments.RECEIPT INNER JOIN
//                                        $pos.tickets ON tickets.ID = receipts.ID INNER JOIN
//                                        $pos.people ON tickets.PERSON = people.ID       
//                                              WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
//                                              GROUP BY people.NAME, payments.PAYMENT  
//                                              ORDER BY NAME");
//                                                //echo count($rowdat);exit();
//                                              
//                                   $ir=0;
//                                    
//                                    while($ir < count($rowdat))
//                                    {
//                                         $bdPos_remotopp->ExecuteTrans("INSERT INTO vcajero (fecha, siga, nombre, tipo_pago, monto) VALUES ('$fecha', '$siga', '".$rowdat[$ir][NAME]."', '".$rowdat[$ir][PAYMENT]."', '".$rowdat[$ir][TOTAL]."')");
//                                         $ir++;
//                                    }
//                                    
//                                  
//                                    //TERCER PASO INSERTAR EN LA BD CENTRAL LA INFORMCACION DE LAS VENTAS POR CLIENTES
//                                   // echo "select b.code as codigo, d.units as cantidad,c.taxid cedula, d.datenew as fecha from  $pos.tickets as a, $pos.products as b, $pos.customers as c, $pos.ticketlines as d where d.product=b.id and d.ticket=a.id and a.customer=c.id and d.datenew between '{$inicio}' and '{$final}'";exit();
//                                    $bdPos->ExecuteTrans("truncate table $pos.vclientes");
//                                   
//                                    $datos_remoto=$bdPos_remotopp->ObtenerFilasBySqlSelect("select * from vclientes where fecha between DATE_SUB(CURDATE(), INTERVAL 1 WEEK) and CURDATE()");
//                                    $cont=0;
//                                    //print_r($datos_remoto);
//                                     count($datos_remoto);
//                                    while($cont<count($datos_remoto)){
//                                    $bdPos->ExecuteTrans("insert into $pos.vclientes (codigo_barra, cantidad_prod, cedula,fecha) values ('".$datos_remoto[$cont]['codigo_barra']."','".$datos_remoto[$cont]['cantidad_prod']."','".$datos_remoto[$cont]['cedula']."','".$datos_remoto[$cont]['fecha']."')");
//                                    $cont++;
//                                    
//                                    }
//                                    
//                                            $rowdat=$bdPos->ObtenerFilasBySqlSelect("select b.code as codigo, sum(d.units) as cantidad,c.taxid cedula, d.datenew as fecha from  $pos.tickets as a, $pos.products as b, $pos.customers as c, $pos.ticketlines as d where d.product=b.id and d.ticket=a.id and a.customer=c.id and d.datenew between '{$inicio}' and '{$final}' group by codigo");
//                                    
//                                    $ir=0;
//                                   // echo "INSERT INTO vcliente (codigo_barras, cantidad_prod, cedula, fecha, siga) VALUES ('".$rowdat[$ir][codigo]."', '".$rowdat[$ir][cantidad]."', SUBSTR(".$rowdat[$ir][cedula].",1), from_unixtime(".$rowdat[$ir][fecha].",'%d.%m.%Y')";exit();
//                                    while($ir<count($rowdat))
//                                    {   $cedula=  substr($rowdat[$ir][cedula], 1);
//                                        $fecha=date('Y/m/d',strtotime($rowdat[$ir][fecha]));
//                                      //  $newDate = date("d-m-Y", strtotime($originalDate));
//                                        //echo "INSERT INTO vclientes (codigo_barra, cantidad_prod, cedula, fecha, siga) 
//                                          //  VALUES ('".$rowdat[$ir][codigo]."', '".$rowdat[$ir][cantidad]."','".$cedula."','".$fecha."','".$siga."')";exit();
//                                        $bdPos_remotopp->ExecuteTrans("INSERT INTO vclientes (codigo_barra, cantidad_prod, cedula, fecha, siga) 
//                                            VALUES ('".$rowdat[$ir][codigo]."', '".$rowdat[$ir][cantidad]."','".$cedula."','".$fecha."','".$siga."')");
//                                         $ir++;
//                                    }
//                                    $bdPos_remotop->ExecuteTrans("INSERT INTO control_sincronizacion (fecha_cierre_debido, fecha_cierre, id_usuario)
//                                    VALUES ('".date("Y-m-d")."', '".date("Y-m-d H:i:s")."','".$usuario1."')");
//                                    
//                                    
//
//                                    
//                                    
//                                  $bdPos_remotopp->CommitTrans(1);
//                                  $bdPos->CommitTrans(1);
//                                  $bdPos_remotop->CommitTrans(1);
//                                  $funciona=1;
//                                  
//                                }
                                
                                
          //fin del $fetch       
                                          
                                      $bdPos_remotop->ExecuteTrans("INSERT INTO control_sincronizacion (fecha_cierre_debido, fecha_cierre, id_usuario)
                                    VALUES ('".date("Y-m-d")."', '".date("Y-m-d H:i:s")."','".$usuario1."')");
//                                    
//                                    
//
//                                    
//                                    
//                                 $bdPos_remotopp->CommitTrans(1);
                                 $bdPos->CommitTrans(1);
                                $bdPos_remotop->CommitTrans(1);
                                $funciona=1;
		   	}else{ 
		   					$bdPos->CommitTrans(1);
                            echo "<p style='color:black; font: oblique bold 120% cursive; margin-top: 2cm; ' align='center'>El Dia de Hoy No Se Realizaron Ventas</p>"; exit();

                        }

		  

	    }catch(Exception $ex){		

			
			
		}

 

	}// FIN DE BANDERA 0
        
        
        
        
        
        
        
  //*****************COMIENZA LA SINCRONIZACION CON LA BANDERA 1 O MEJOR DICHO CUANDO SE LE OLVIDA AL USUARIO ACTUALIZAR***********************
      
        if($bandera==1){ 
            
            while($fechaBD<$fechaAyer){
                        $ac_fecha=0;
                        $nuevafecha = strtotime ( '+1 day' , strtotime ( $fechaBD ) ) ;
			$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
			$fechaBD=$nuevafecha;
                        $desde=$fechaBD." 00:00:00"; 
                        $hasta=$fechaBD." 23:59:00"; 
                        
                        $bdPos->BeginTrans();
                        $fetch=$bdPos->ObtenerFilasBySqlSelect("SELECT PRODUCT, sum(UNITS) as CANT, PRICE FROM $pos.stockdiary WHERE DATENEW BETWEEN '$desde' and '$hasta' GROUP BY PRODUCT");
                        echo "SELECT PRODUCT, sum(UNITS) as CANT, PRICE FROM $pos.stockdiary WHERE DATENEW BETWEEN '$desde' and '$hasta' GROUP BY PRODUCT";
                        $bdPos_remotop= new Almacen(DB_HOST,DB_USUARIO,DB_CLAVE,BASEDEDATOS);
                                $bdPos_remotop->BeginTrans();
                        if(count($fetch)>0){
                       // echo "INSERT INTO kardex_almacen (tipo_movimiento_almacen, autorizado_por, observacion, fecha, usuario_creacion, fecha_creacion, estado, fecha_ejecucion, id_documento) VALUES ('2', '$usuario', 'Salida por Ventas', '".$fechaBD."', '$usuario', '".date("Y-m-d H:i:s")."', 'Entregado', '".date("Y-m-d H:i:s")."','0')";exit();
                        $bdPos->ExecuteTrans("INSERT INTO kardex_almacen (tipo_movimiento_almacen, autorizado_por, observacion, fecha, usuario_creacion, fecha_creacion, estado, fecha_ejecucion, id_documento) VALUES ('2', '$usuario', 'Salida por Ventas', '".$fechaBD."', '$usuario', '".date("Y-m-d H:i:s")."', 'Entregado', '".date("Y-m-d H:i:s")."','0')");
		    	echo count($fetch);
                                
                                $i=0;
                                while($i < count($fetch)){ 
	   				$itemF=$bdPos->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE  itempos='".$fetch[$i][PRODUCT]."'");
					$item = $itemF[0]["id_item"];
		  					
		  			$ubF=$bdPos->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE  puede_vender='1'");
		  			$idUbic = $ubF[0]["id"];	  			
					$canti=$fetch[$i][CANT]*(-1);
					$bdPos->ExecuteTrans("INSERT INTO kardex_almacen_detalle (id_transaccion, id_almacen_salida, id_item, cantidad, id_ubi_salida, precio) VALUES ('$id_kardex', '1', '$item', '".$canti."', '".$idUbic."', '".$fetch[$i][PRICE]."')");
		  			$campos = $bdPos->ObtenerFilasBySqlSelect("select * from item_existencia_almacen where id_item  = '$item' and id_ubicacion  = '$idUbic' ");
					$cantidadExistente = $campos[0][cantidad];
					$codAlmacen = $campos[0][cod_almacen];
					$bdPos->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($cantidadExistente - $canti) . "' where id_item  = '$item' and cod_almacen = '$codAlmacen' and id_ubicacion='$idUbic'");
					//echo "<br>";
				$i++;}
                        
                                //FIN DE LA ACTUALIZACION DE VENTA POS PYME
                                //COMIENZA LA ACTUALIZACION HACIA EL CENTRAL
//                                $llego=1;
//                                $fortinet=$bdPos->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
//                                $fortinet= $fortinet[0]["fortinet"];    
//                                if($llego==1 && $fortinet==1){
//                                $pos = POS;
//                                //funcion nueva en la clase conexion comun para conectar remotamente
//                                
//                                
//                                $codigo_siga=$bdPos_remotop->ObtenerFilasBySqlSelect("SELECT codigo_siga FROM parametros_generales");
//                                $siga=$codigo_siga[0]["codigo_siga"];
////                                $inicio=date("Y-m-d")." 00:00:00";
////                                $final=date("Y-m-d")." 23:00:00";
//                                $inicio=date($desde);
//                                $final=date($hasta);
//                                $fecha=date("Y-m-d");
////                                $inicio="2015-02-26 00:00:00";
////                                $final="2015-02-26 23:00:00";
////                                $fecha="2015-02-26";
//                                
//                                $bdPos_remotopp=new Almacen();
//                                $bdPos_remotopp->conexionRemota_central(DB_HOSTP,DB_USUARIOP,DB_CLAVEP,DB_SELECTRA_PYMEP);
//                                $bdPos_remotopp->BeginTrans();
//                                $rowdat=$bdPos_remotop->ObtenerFilasBySqlSelect("SELECT products.REFERENCE,
//                                                                        products.NAME,
//                                                                        products.CODE,
//                                                                        products.PRICESELL,
//                                                                        taxes.RATE,
//                                                                        sum(ticketlines.UNITS) as UNITS
//                                                                        FROM $pos.products INNER JOIN
//                                                                        $pos.ticketlines ON products.ID = ticketlines.PRODUCT INNER JOIN
//                                                                        $pos.tickets ON ticketlines.TICKET = tickets.ID 
//                                                                        INNER JOIN $pos.taxes ON ticketlines.TAXID = taxes.ID 
//                                                                        WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
//                                                                        GROUP BY products.NAME 
//                                                                        ORDER BY NAME");
//                                                                      // echo count($rowdat);exit();
//                                                                        
//                                        $ir=0;
//                                                    
//                                        while($ir < count($rowdat))
//                                        {
//                                             $bdPos_remotopp->ExecuteTrans("INSERT INTO vproducto (fecha, siga, referencia, nombre, codigo_barra, precio, iva, unidad) VALUES ('$inicio', '$siga', '".$rowdat[$ir][REFERENCE]."', '".$rowdat[$ir][NAME]."', '".$rowdat[$ir][CODE]."', '".$rowdat[$ir][PRICESELL]."', '".$rowdat[$ir][RATE]."', '".$rowdat[$ir][UNITS]."')");
//                                             $ir++;
//                                        }
//                                        
//                                        
//                                        
//                                        
//                                        //SEGUNDO SUBIMOS AL SERVIDOR CENTRAL LA INFORMACION DE LOS CAJEROS
//                                        $rowdat=$bdPos_remotop->ObtenerFilasBySqlSelect("SELECT people.NAME,
//                                        payments.PAYMENT,
//                                        sum(payments.TOTAL) as TOTAL
//                                             FROM $pos.payments INNER JOIN
//                                        $pos.receipts ON receipts.ID = payments.RECEIPT INNER JOIN
//                                        $pos.tickets ON tickets.ID = receipts.ID INNER JOIN
//                                        $pos.people ON tickets.PERSON = people.ID       
//                                              WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
//                                              GROUP BY people.NAME, payments.PAYMENT  
//                                              ORDER BY NAME");
//                                                //echo count($rowdat);exit();
//                                              
//                                   $ir=0;
//                                    
//                                    while($ir < count($rowdat))
//                                    {
//                                         $bdPos_remotopp->ExecuteTrans("INSERT INTO vcajero (fecha, siga, nombre, tipo_pago, monto) VALUES ('$inicio', '$siga', '".$rowdat[$ir][NAME]."', '".$rowdat[$ir][PAYMENT]."', '".$rowdat[$ir][TOTAL]."')");
//                                         $ir++;
//                                    }
//                                    
//                                     //TERCER PASO INSERTAR EN LA BD CENTRAL LA INFORMCACION DE LAS VENTAS POR CLIENTES
//                                   // echo "select b.code as codigo, d.units as cantidad,c.taxid cedula, d.datenew as fecha from  $pos.tickets as a, $pos.products as b, $pos.customers as c, $pos.ticketlines as d where d.product=b.id and d.ticket=a.id and a.customer=c.id and d.datenew between '{$inicio}' and '{$final}'";exit();
//                                   
//                                    $bdPos->ExecuteTrans("truncate table $pos.vclientes");
//                                   
//                                    $datos_remoto=$bdPos_remotopp->ObtenerFilasBySqlSelect("select * from vclientes where fecha between DATE_SUB(CURDATE(), INTERVAL 1 WEEK) and CURDATE()");
//                                    $cont=0;
//                                    //print_r($datos_remoto);
//                                     //count($datos_remoto);
//                                    while($cont<count($datos_remoto)){
//                                    $bdPos->ExecuteTrans("insert into $pos.vclientes (codigo_barra, cantidad_prod, cedula,fecha) values ('".$datos_remoto[$cont]['codigo_barra']."','".$datos_remoto[$cont]['cantidad_prod']."','".$datos_remoto[$cont]['cedula']."','".$datos_remoto[$cont]['fecha']."')");
//                                    $cont++;
//                                    
//                                    }
//                                    
//                                    $rowdat=$bdPos->ObtenerFilasBySqlSelect("select b.code as codigo, sum(d.units) as cantidad,c.taxid cedula, d.datenew as fecha from  $pos.tickets as a, $pos.products as b, $pos.customers as c, $pos.ticketlines as d where d.product=b.id and d.ticket=a.id and a.customer=c.id and d.datenew between '{$inicio}' and '{$final}' group by codigo");
//                                    
//                                    $ir=0;
//                                   // echo "INSERT INTO vcliente (codigo_barras, cantidad_prod, cedula, fecha, siga) VALUES ('".$rowdat[$ir][codigo]."', '".$rowdat[$ir][cantidad]."', SUBSTR(".$rowdat[$ir][cedula].",1), from_unixtime(".$rowdat[$ir][fecha].",'%d.%m.%Y')";exit();
//                                    while($ir<count($rowdat))
//                                    {   $cedula=  substr($rowdat[$ir][cedula], 1);
//                                        $fecha=date('Y/m/d',strtotime($rowdat[$ir][fecha]));
//                                      //  $newDate = date("d-m-Y", strtotime($originalDate));
//                                        //echo "INSERT INTO vclientes (codigo_barra, cantidad_prod, cedula, fecha, siga) 
//                                          //  VALUES ('".$rowdat[$ir][codigo]."', '".$rowdat[$ir][cantidad]."','".$cedula."','".$fecha."','".$siga."')";exit();
//                                        $bdPos_remotopp->ExecuteTrans("INSERT INTO vclientes (codigo_barra, cantidad_prod, cedula, fecha, siga) 
//                                            VALUES ('".$rowdat[$ir][codigo]."', '".$rowdat[$ir][cantidad]."','".$cedula."','".$fecha."','".$siga."')");
//                                         $ir++;
//                                    }
//                                 
//                                  
//                                }// FIN DEL LLEGO PARA ACTUALIZAR LAS VENTAS AL CENTRAL
//                                
                               
                                
                                
                                
                         //SE ACTUALIZA LA FECHA EN CONTROL SINCRONIZACION       
                                
                                if($bandera==1){
	        		//ingresa la fecha de sincronizacion con la fecha debida 
	        		//sincronizacion forzada
	        		//acomodar para colocarlo al final del proceso OJO
	        		$bdPos_remotop->ExecuteTrans("INSERT INTO control_sincronizacion (fecha_cierre_debido, fecha_cierre, id_usuario)
	        			VALUES ('$fechaBD', '".date("Y-m-d H:i:s")."','".$usuario1."')");
//                                    $sql="INSERT INTO control_sincronizacion (fecha_cierre_debido, fecha_cierre, id_usuario)
//	        			VALUES ('$fechaBD', '".date("Y-m-d H:i:s")."','".$usuario1."')";
//	        		$almacen->ExecuteTrans($sql);
                                $ac_fecha=1;
                                

	        	}
                         $bdPos_remotop->CommitTrans(1); //control de sincronizacion
                        // $bdPos_remotopp->CommitTrans(1); //conexion remota central
                         $bdPos->CommitTrans(1); //conexion local
                         
                                
                                
                       
                
                
                }//FIN DEL IF DE SI ENCUENTRA VENTAS EN ESE DIA
                if($bandera==1 && $ac_fecha!=1){ 
	        		//ingresa la fecha de sincronizacion con la fecha debida 
	        		//sincronizacion forzada
	        		//acomodar para colocarlo al final del proceso OJO
	        		$bdPos_remotop->ExecuteTrans("INSERT INTO control_sincronizacion (fecha_cierre_debido, fecha_cierre, id_usuario)
	        			VALUES ('$fechaBD', '".date("Y-m-d H:i:s")."','".$usuario1."')");
                                $bdPos_remotop->CommitTrans(1); //control de sincronizacion

	        	}
                        
            }// FIN DEL REPITA MIENTRAS DE FECHA
            
            
        
            
            
            
            
            
            
            
            
            
            
            
            
        }//FIN DE BANDERA 1
        
        
      echo "<input type=hidden id=funciona name=funciona value='".$funciona."'/>";  
        
  if ($bandera==1){			
		header("Location: ../sesion/index.php");
   		exit();
	}      
        
        
  //header("Location: index.php?opt_menu=106");      

}//fin del generar=si


	
	?>
	<input type="hidden" id="nombre" value="entro">
	<?php

	





