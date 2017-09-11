<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/clases/clase_db.inc.php");

error_reporting(-1);
$ano=substr(date('Y'), 1);
$schema="SIMA".$ano;
$pymeC="selectrapyme_central";
$bandera=1;
$mes_actual=date('m');
$mes=1;
$ano_ventas=substr(date('Y'), 2);

$conex1=$conn_pyme = new ConexionComun();
$conn_siga = new DB_Class02;
$conex2=$conn_siga->DB_Init();

$conn_siga2 = new DB_Class02;
$conex3=$conn_siga2->DB_Init();


if($conex1 && $conex2) {
$sql_truncate='TRUNCATE TABLE '.$pymeC.'.observaciones_migracion';
$truncate_producto=$conn_pyme->Execute2($sql_truncate);

while (	$mes<=$mes_actual) {
    
$mes_ventas=str_pad($mes, 2, "0", STR_PAD_LEFT);
$sql_facturas="SELECT * from $pymeC.ventas_".$mes_ventas."_".$ano_ventas."_pyme a LEFT JOIN $pymeC.puntos_venta b ON a.codigo_siga = b.codigo_siga_punto WHERE monto_credito!=0 GROUP BY cod_factura_fiscal, codigo_siga";
$select_cab = $conn_pyme->ObtenerFilasBySqlSelect($sql_facturas);
foreach ($select_cab as $cab) {//Cabecera de la factura

    //Referencia y Numero de Control
    $cod_siga_trun=substr($cab['codigo_siga'], -3);
    $ref=substr($cab['codigo_siga'], -3).$cab['cod_factura_fiscal'];

    //Rif del Cliente
    $chars = array("-", " ");
    $rif = trim(strtoupper(str_replace($chars, "", $cab['rif'])));

    //Validamos rif del cliente en SIGA
    $sql_rif_siga="SELECT * FROM \"".$schema."\"".".facliente WHERE rifpro='".$rif."'";
    $rs_rif=$conn_siga->DB_Consulta($sql_rif_siga); 
    $rif_siga=$conn_siga->DB_fetch_array($rs_rif);

    //Validamos referencia de la factura en SIGA
    $sql_ref_siga="SELECT * FROM \"".$schema."\"".".fafacturpro WHERE reffac='".$ref."'";
    $rs_ref=$conn_siga->DB_Consulta($sql_ref_siga); 
    $ref_siga=$conn_siga->DB_fetch_array($rs_ref);

    //Si la referencia esta ya usada seguimos con el siguiente registros y almacenamos el error
    if ($ref_siga['reffac']!='') {
    $sql="INSERT INTO $pymeC.observaciones_migracion(observacion, factura, fecha, codigo_siga) VALUES ('Nro de Referencia ya se encuentra en SIGA: ".$ref."', '".$cab['cod_factura_fiscal']."', '".$cab['fechaFactura']."', '".$cab['codigo_siga']."')";
    $conn_pyme->Execute2($sql);
    continue;
    }

    //Si el cliente se encuentra registrado y la referencia no se encuentra migrada seguimos con el proceso
    if ($rif_siga['nompro']!='') {
		$inser_cab_siga="INSERT INTO \"".$schema."\"".".fafacturpro(reffac,numcontrol, fecfac, codcli, desfac, tipref, monfac, codconpag,status, tipmon, valmon, coddirec, localidad) VALUES 
    	('".$ref."','".$ref."', '".$cab['fechaFactura']."','".$rif."','Factura numero ".$cab['cod_factura_fiscal']." del punto de venta ".$cab['codigo_siga']." con la impresora fiscal nro ".$cab['impresora_serial']."','V','".$cab['TotalTotalFactura']."','1','A','001','1.00','".$cab['codigo_estado_punto']."', '".$cod_siga_trun."');";
    	echo $inser_cab_siga."<br>";
    	$trans=$conn_siga2->DB_Iniciar_Transaccion();
    	$confirm_inser_cab=$conn_siga2->DB_Consulta($inser_cab_siga);

    	if ($confirm_inser_cab) {
			$bandera=0;
    	}

    	if (!$confirm_inser_cab) {
    				$sql="INSERT INTO $pymeC.observaciones_migracion(observacion) VALUES ('Error al ejecutar el QUERY: ".$inser_cab_siga."')";
     				$conn_pyme->Execute2($sql);
     				$bandera=1;
    		}

    $sql_productos="SELECT * from $pymeC.ventas_".$mes_ventas."_".$ano_ventas."_pyme a WHERE cod_factura_fiscal='".$cab['cod_factura_fiscal']."' and codigo_siga='".$cab['codigo_siga']."'";
    $select_pro = $conn_pyme->ObtenerFilasBySqlSelect($sql_productos);
    $cantidad_productos=count($select_pro);
    if ($cantidad_productos==$cab['cantidad_items']) { //Comprobamos si la cantidad de productos consultados es igual a la cantidad de productos por la que se hizo la factura

        foreach ($select_pro as $producto) { //Detalle de la factura

            //Consultamos el Codigo Siga del producto en la BD de SIGA
            $sql_producto_siga="SELECT * FROM \"".$schema."\"".".caregart WHERE codbar='".trim($producto['codigo_barras'])."'";
            $rs_producto=$conn_siga->DB_Consulta($sql_producto_siga); 
            $producto_siga=$conn_siga->DB_fetch_array($rs_producto);

            if ($producto_siga['codart']!='') {
				//calculamos el monto del iva
            $iva=$producto['_item_totalconiva']-$producto['_item_totalsiniva'];

            //Insertamos detalle de la factura
            $precio_unidad=$producto['_item_totalsiniva']/$producto['_item_cantidad'];

            $inser_det_siga="INSERT INTO \"".$schema."\"".".faartfacpro(codref,reffac, codart, cantot, precio, monrgo, totart, desart, mondes, unimed, codmon, valmon, estatus) 
            VALUES ('".$ref."','".$ref."','".$producto_siga['codart']."','".$producto['_item_cantidad']."','".number_format($precio_unidad,3,'.','')."',$iva,'".$producto['_item_totalconiva']."','".$producto_siga['desart']."','0.00','".$producto_siga['unimed']."','001','1.00', 'A');";
            echo $inser_det_siga."<br>";
            $confirm_inser_det=$conn_siga2->DB_Consulta($inser_det_siga);

            if ($confirm_inser_det) {
				$bandera=0;
    		}

    		if (!$confirm_inser_det) {
    				$sql="INSERT INTO $pymeC.observaciones_migracion(observacion) VALUES ('Error al ejecutar el QUERY: ".$inser_det_siga."')";
     				$conn_pyme->Execute2($sql);
     				$bandera=1;
    		}

            	if ($iva>0) { //Si existe iva insertamos en la tabla de recargo
                $inser_rgo_siga="INSERT INTO \"".$schema."\"".".fargoartpro(codrgo, codart, refdoc, monrgo, tipdoc, desart)
                VALUES ('0001','".$producto_siga['codart']."', '".$ref."', '".$iva."', 'F', '".$producto_siga['desart']."');";
                $inser_rgo_siga."<br>";
                $confirm_inser_rgo=$conn_siga2->DB_Consulta($inser_rgo_siga);
                if ($confirm_inser_rgo) {
						$bandera=0;
    			}

    			if (!$confirm_inser_rgo) {
    				$sql="INSERT INTO $pymeC.observaciones_migracion(observacion) VALUES ('Error al ejecutar el QUERY: ".$inser_rgo_siga."')";
     				$conn_pyme->Execute2($sql);
     				$bandera=1;
    			}


            	}
            }else{

            	//Insertamos el registro del evento para poder corregir el error y la factura pueda ser migrada
            	$sql="INSERT INTO $pymeC.observaciones_migracion(observacion, factura, fecha, codigo_siga) VALUES ('No se encuentra registrado el Producto en SIGA: ".$producto['codigo_barras']."', '".$cab['cod_factura_fiscal']."', '".$cab['fechaFactura']."', '".$cab['codigo_siga']."')";
     			$conn_pyme->Execute2($sql);
            	//Si no existe el codigo siga del producto termino el ciclo y se hace rollback de la transaccion
            	$conn_siga2->DB_Cancelar_Transaccion();
            	break;
            }

            

        }

    }else{
    	//Si la cantidad de productos no es igual se hace rollback de la transaccion
    	$sql="INSERT INTO $pymeC.observaciones_migracion(observacion, factura, fecha, codigo_siga) VALUES ('La cantidad de productos del detalle no concuerda con el total del productos de la factura', '".$cab['cod_factura_fiscal']."', '".$cab['fechaFactura']."', '".$cab['codigo_siga']."')";
     	$conn_pyme->Execute2($sql);
    	$conn_siga2->DB_Cancelar_Transaccion();
    }

    	if ($bandera==0) {
			$conn_siga2->DB_Confirmar_Transaccion();
    	}

    	if ($bandera==1) {
			$conn_siga2->DB_Cancelar_Transaccion();
    	}
       
     }else{
     	//Insertamos el registro del evento para poder corregir el error y la factura pueda ser migrada
     	$sql="INSERT INTO $pymeC.observaciones_migracion(observacion, factura, fecha, codigo_siga) VALUES ('No se encuentra registrado el RIF del Cliente en SIGA: ".$rif."', '".$cab['cod_factura_fiscal']."', '".$cab['fechaFactura']."', '".$cab['codigo_siga']."')";
     	$conn_pyme->Execute2($sql);
     }
     
     
    }
	$mes++;
}
exit();
//////////////// TRANSFERIR PUNTOS DE VENTAS ///////////////////////////    
    $sql ="select * from \"".$schema."\"".".cadefalm 
            WHERE codtip='71'
            AND (nomalm ilike '%PUNTO DE VENTA%' OR nomalm ilike '%HIPER PDVAL%' OR nomalm ilike '%SUPER PDVAL%') 
            AND nomalm NOT ILIKE '%DISPONIBLE%' 
            AND nomalm NOT ILIKE '%INACTIVO%' 
            AND nomalm NOT ilike '%ALMACENADORA %' 
            AND nomalm NOT ilike '%MOVIL %' 
            AND nomalm NOT ilike '%CENTRO DE ACOPIO%' 
            AND nomalm NOT ilike '%CENTRO DE DISTRIBUCION%'
            AND nomalm NOT ilike '%ALMACEN %' 
            AND nomalm NOT ilike '%EMPAQUETADORA%' 
            AND nomalm NOT ilike '%NO ESTA%' 
            AND nomalm NOT ilike '%NO USAR%'
            AND (estatus='A' or estatus is null) ";
    $rs_siga=$conn_siga->DB_Consulta($sql);      
    while($row_siga=$conn_siga->DB_fetch_array($rs_siga)){
        
            $sql ="SELECT * from selectrapyme_central.puntos_venta where codigo_siga='".$row_siga['codalm']."'";
            $row_puntos = $conn_pyme->ObtenerFilasBySqlSelect($sql);
            $regs_n=count($row_puntos);
            if($regs_n==0) { //SI EL PUNTO NO EXISTE LO INSERTA
                $sql="insert into selectrapyme_central.puntos_venta(codigo_siga,nombre_punto,direccion_punto,codigo_estado)
                      values ('".$row_siga['codalm']."','".$row_siga['nomalm']."','".$row_siga['diralm']."','".$row_siga['codedo']."')";
                $conn_pyme->Execute2($sql);

            }//Fin if($regs_n==0) { //SI EL PUNTO NO EXISTE LO INSERTA

    } //Fin While

//////////////// TRANSFERIR PRODUCTOS ///////////////////////////    
    $archivo = "/var/www/pyme/selectraerp/uploads/productos/productos.txt";
    $filas=file($archivo);
    $i=0;
    $rs_ins=0;
    
    while($filas[$i]!=NULL){
        $row = $filas[$i];    
        $values = explode(",",$row);
        $regs=count($values);
        
        $sql ="SELECT * from selectrapyme_central.productos where codigo_barras='".$values[2]."'";
        $row_puntos = $conn_pyme->ObtenerFilasBySqlSelect($sql);
        $regs_n=count($row_puntos);
        if($regs_n==0) { //SI EL PUNTO NO EXISTE LO INSERTA
            $sql="insert into selectrapyme_central.productos(cod_item, codigo_barras, descripcion1, 
                descripcion2, descripcion3, referencia, codigo_fabricante, unidad_empaque, cantidad, seriales, 
                garantia, tipo_item, factor_cambio, ultimo_costo, precio_x_escala, comision_x_item, tipo_comision_x_item, 
                desdeA1, desdeA2, desdeB1, comisiones1, comisiones2, comisiones3, desdeB2, desdeC1, desdeC2, precio1, 
                utilidad1, coniva1, precio2, utilidad2, coniva2, precio3, utilidad3, coniva3, precio_referencial1, 
                precio_referencial2, precio_referencial3, costo_actual, costo_promedio, costo_anterior, existencia_total, 
                existencia_min, existencia_max, monto_exento, iva, ubicacion1, ubicacion2, ubicacion3, ubicacion4, 
                ubicacion5, cod_departamento, cod_grupo, cod_linea, estatus, usuario_creacion, fecha_creacion, cod_item_forma, 
                tipo_prod, cuenta_contable1, cuenta_contable2, codigo_cuenta, serial1, foto, cantidad_bulto, kilos_bulto, proveedor, 
                fecha_ingreso, origen, costo_cif, costo_origen, temporada, mate_compo_clase, punto_pedido, tejido, reg_sanit, 
                cod_barra_bulto, observacion, foto1, foto2, foto3, foto4, cont_licen_nro, precio_cont, aprob_arte, propiedad, 
                regulado, itempos)
                values (
                '".$values[1]."','".$values[2]."','".$values[3]."','".$values[4]."','".$values[5]."',
                '".$values[6]."','".$values[7]."','".$values[8]."','".$values[9]."','".$values[10]."','".$values[11]."',
                '".$values[12]."','".$values[13]."','".$values[14]."','".$values[15]."','".$values[16]."','".$values[17]."',
                '".$values[18]."','".$values[19]."','".$values[20]."','".$values[21]."','".$values[22]."','".$values[23]."',
                '".$values[24]."','".$values[25]."','".$values[26]."','".$values[27]."','".$values[28]."','".$values[29]."',
                '".$values[30]."','".$values[31]."','".$values[32]."','".$values[33]."','".$values[34]."','".$values[35]."',
                '".$values[36]."','".$values[37]."','".$values[38]."','".$values[39]."','".$values[40]."','".$values[41]."',
                '".$values[42]."','".$values[43]."','".$values[44]."','".$values[45]."','".$values[46]."','".$values[47]."',
                '".$values[48]."','".$values[49]."','".$values[50]."','".$values[51]."','".$values[52]."','".$values[53]."',
                '".$values[54]."','".$values[55]."','".$values[56]."','".$values[57]."','".$values[58]."','".$values[59]."',
                '".$values[60]."','".$values[61]."','".$values[62]."','".$values[63]."','".$values[64]."','".$values[65]."',
                '".$values[66]."','".$values[67]."','".$values[68]."','".$values[69]."','".$values[70]."','".$values[71]."',
                '".$values[72]."','".$values[73]."','".$values[74]."','".$values[75]."','".$values[76]."','".$values[77]."',
                '".$values[78]."','".$values[79]."','".$values[80]."','".$values[81]."','".$values[82]."','".$values[83]."',
                
'".$values[84]."','".$values[85]."','".$values[86]."','".$values[87]."','".$values[88]."')";
            $rs_ins=$conn_pyme->Execute2($sql);
            if($rs_ins!=1) exit;
            
        }//Fin if($regs_n==0) { //SI EL PUNTO NO EXISTE LO INSERTA        
        
        $i++;
    } //Fin While
 
    //if($rs_ins==1) unlink($archivo);
    
//////////////// TRANSFERIR CATEGORIAS ///////////////////////////       
    $archivo = "/var/www/pyme/selectraerp/uploads/categorias/categoria.txt";
    $filas=file($archivo);
    $i=0;
    $rs_ins=0;
    
   while($filas[$i]!=NULL){
        $row = $filas[$i];    
        
        $values = explode(",",$row);
        
        $sql ="SELECT * from selectrapyme_central.grupo where cod_grupo='".$values[0]."'";
        //echo $sql;
        $row_puntos = $conn_pyme->ObtenerFilasBySqlSelect($sql);
        $regs_n=count($row_puntos);
        if($regs_n==0) { //SI LA CATEGORIA NO EXISTE LA INSERTA
            $sql="insert into selectrapyme_central.grupo(descripcion, id_rubro, restringido, cantidad_rest, dias_rest, grupopos)
            values
        ('".$values[1]."','".$values[2]."','".$values[3]."','".$values[4]."','".$values[5]."','".$values[6]."')";
            $rs_ins=$conn_pyme->Execute2($sql);
            if($rs_ins!=1) exit; 
        }//Fin if($regs_n==0) { //SI LA CATEGORIA NO EXISTE LA INSERTA        
        $i++;
    } //Fin While
    
    //if($rs_ins==1) unlink($archivo);
    
}else{
    echo "Error en la Conexión con las Bases de Datos...";
}

?>


