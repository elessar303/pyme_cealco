<?php

include("../../libs/php/clases/almacen.php");
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/producto.php");
require_once("../../libs/php/clases/ConexionComun.php");
$almacen = new Almacen();
$login = new Login();
$comun = new Comunes();
$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());


if (isset($_POST["input_cantidad_items"]))
{ // si el usuario hizo post

	for ($j = 0; $j < (int) $_POST["input_cantidad_items"]; $j++){

	$cadena_item[$j]=$_POST["_id_item"][$j];
	$cadena_cantidad[$j]=$_POST["_cantidad"][$j];
		if($cadena_cantidad[$j]<0){
			echo "Traslado con Cantidad en Negativo, Verificar Datos";
			exit();
		}

	}

	$res = array_diff($cadena_item, array_diff(array_unique($cadena_item), array_diff_assoc($cadena_item, array_unique($cadena_item))));
 	if($res[0]!=''){
	foreach(array_unique($res) as $v) {
		$sql="SELECT * FROM item WHERE id_item=".$v."";
		$item = $almacen->ObtenerFilasBySqlSelect($sql);
	    echo "Producto Duplicado Durante la Operacion: ".$v." ".$item[0]['descripcion1']."<br/>";
		}
	exit();  
	}



    $almacen->BeginTrans();
    $pos=POS;
    $kardex_almacen_instruccion = "
    INSERT INTO kardex_almacen (
    `id_transaccion` ,
    `tipo_movimiento_almacen` ,
    `autorizado_por` ,
    `observacion` ,
    `fecha` ,
    `usuario_creacion`,
    `fecha_creacion`
    )
    VALUES (
    NULL ,
    '5',
    '" . $_POST["autorizado_por"] . "',
    '" . $_POST["observaciones"] . "',
    '" . $_POST["input_fechacompra"] . "',
    '" . $login->getUsuario() . "',
    CURRENT_TIMESTAMP
    );";

    $almacen->ExecuteTrans($kardex_almacen_instruccion);

    $id_transaccion = $almacen->getInsertID();

    for ($i = 0; $i < (int) $_POST["input_cantidad_items"]; $i++)
    {

        $sql="SELECT coniva1 FROM item WHERE id_item  = '{$_POST["_id_item"][$i]}'";
        $precio_actual=$almacen->ObtenerFilasBySqlSelect($sql);

        $kardex_almacen_detalle_instruccion = "
        INSERT INTO kardex_almacen_detalle (
        `id_transaccion_detalle` ,
        `id_transaccion` ,
        `id_almacen_entrada` ,
        `id_almacen_salida` ,
        `id_item` ,
        `cantidad`,
         `id_ubi_entrada` ,
        `id_ubi_salida`, 
        `precio`,
        `lote`
        )
        VALUES (
        NULL ,
        '" . $id_transaccion . "',
        '" . $_POST["almacen_entrada"] . "',
        '" . $_POST["_id_almacen"][$i] . "',
        '" . $_POST["_id_item"][$i] . "',
        '" . $_POST["_cantidad"][$i] . "',
         '" . $_POST["ubicacion_entrada"]. "',
        '" . $_POST["_ubicacion"][$i]. "',
        ".$precio_actual[0]['coniva1'].",
        '" . $_POST["_nlote"][$i]. "'
        );";

        //echo $kardex_almacen_detalle_instruccion; exit();

        $almacen->ExecuteTrans($kardex_almacen_detalle_instruccion);

        //Registro de salidas de almacen.
        $campos = $almacen->ObtenerFilasBySqlSelect("
                        select * from item_existencia_almacen
                                where
                        id_item  = '" . $_POST["_id_item"][$i] . "' and
                         id_ubicacion  = '" . $_POST["_ubicacion"][$i] . "' and
                        cod_almacen = '" . $_POST["_id_almacen"][$i] . "' and lote='" . $_POST["_nlote"][$i] . "'");
        #echo "select * from item_existencia_almacen where id_item  = '".$_POST["_id_item"][$i]."' and cod_almacen = '".$_POST["_id_almacen"][$i]."'<br>";
        if (count($campos) > 0) {
            $cantidadExistente = $campos[0]["cantidad"];
            #echo "update item_existencia_almacen set cantidad = '" . ($cantidadExistente - $_POST["_cantidad"][$i]) . "' where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["_id_almacen"][$i] . "'<br>";
            $almacen->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($cantidadExistente - $_POST["_cantidad"][$i]) . "'
                        where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["_id_almacen"][$i] . "' and id_ubicacion='". $_POST["_ubicacion"][$i]."' and lote='" . $_POST["_nlote"][$i] . "'");
        } else {
            
        }

        //Entrada de Items en almacen seleccionado...

        $campos = $almacen->ObtenerFilasBySqlSelect("
                        select * from item_existencia_almacen
                                where
                        id_item  = '" . $_POST["_id_item"][$i] . "' and
                        cod_almacen = '" . $_POST["almacen_entrada"]. "' and id_ubicacion = '" . $_POST["ubicacion_entrada"]. "' and lote='" . $_POST["_nlote"][$i] . "'");

        #echo "select * from item_existencia_almacen where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["almacen_entrada"][$i] . "'<br>";
        if (count($campos) > 0) {
            $cantidadExistente = $campos[0]["cantidad"];
            #echo "update item_existencia_almacen set cantidad = '" . ($cantidadExistente - $_POST["_cantidad"][$i]) . "' where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["almacen_entrada"] . "'<br>";
            $almacen->ExecuteTrans("update item_existencia_almacen set cantidad = '" . ($cantidadExistente + $_POST["_cantidad"][$i]) . "'
                where id_item  = '" . $_POST["_id_item"][$i] . "' and cod_almacen = '" . $_POST["almacen_entrada"] . "' and id_ubicacion = '" . $_POST["ubicacion_entrada"]. "' and lote='" . $_POST["_nlote"][$i] . "'");
        } else {
            $instruccion = "
                    INSERT INTO item_existencia_almacen(
                    `cod_almacen` ,
                    `id_item` ,
                    `cantidad`,
                    `id_ubicacion`,
                    `lote`
                    )
                    VALUES (
                        '" . $_POST["almacen_entrada"] . "',
                        '" . $_POST["_id_item"][$i] . "',
                        '" . $_POST["_cantidad"][$i] . "',
                        '" .$_POST["ubicacion_entrada"]. "',
                        '" . $_POST["_nlote"][$i] . "'
                    );";
            $almacen->ExecuteTrans($instruccion);
        }
    
		 $ubi=$_POST["ubicacion_entrada"];
                 
                 
                 
                $ubi1=$_POST["_ubicacion"][$i]; 


                
                
                
                
                //CASO TRASLADO DE PISO DE VENTA A OTRO LUGAR
                $sql="SELECT * FROM ubicacion  WHERE id='".$ubi1."'";

               $ubica_salida = $almacen->ObtenerFilasBySqlSelect($sql);
               $puedev=$ubica_salida[0]["puede_vender"]; 
                 if($puedev==1){ 
                     
                     if($pos!=''){
                        
                    $itemm= $almacen->ObtenerFilasBySqlSelect("SELECT * FROM item join grupo on (item.cod_grupo=grupo.cod_grupo) WHERE
                    id_item= '{$_POST["_id_item"][$i]}'"); 
                    $itempos=$itemm[0]["itempos"]; 
                    
                    
                    $verificar=$almacen->ObtenerFilasBySqlSelect("select * from $pos.stockcurrent where product='$itempos'");
                        if($verificar[0]["PRODUCT"]!="" && $verificar[0]["UNITS"]>=$_POST["_cantidad"][$i]){
                          $instruccion = "update $pos.stockcurrent set UNITS=(UNITS-{$_POST["_cantidad"][$i]}) WHERE PRODUCT='$itempos'";
                        }else{
                            $almacen->errorTransaccion = 0;
                             }
                    
                    
                   // $instruccion = "update $pos.stockcurrent set UNITS=(UNITS-{$_POST["_cantidad"][$i]}) WHERE PRODUCT='$itempos'";
                    $almacen->ExecuteTrans($instruccion);
                    
                    
                     }
                     
                     
                     
                 }
                 
        $sql="SELECT * FROM ubicacion  WHERE id='$ubi'";
		$ubis = $almacen->ObtenerFilasBySqlSelect($sql);

  		$puedev=$ubis[0]["puede_vender"]; 
                
		if($pos!='')
		{	
                    //CASO TRASLADO DE CUALQUIER LUGAR A PISO DE VENTA
			if($puedev==1)
			{
                //    echo "<br>";
                $sql="SELECT * FROM item join grupo on (item.cod_grupo=grupo.cod_grupo) WHERE
                    id_item  = {$_POST["_id_item"][$i]} ";
				$itemm = $almacen->ObtenerFilasBySqlSelect($sql);
  				 
  				 $grupopos=$itemm[0]["grupopos"];
                 $cod_bar=$itemm[0]["codigo_barras"];
  				$itemmpos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM $pos.products WHERE CODE='$cod_bar'");
                $itempos=$itemmpos[0]["ID"];
  				if($itemmpos[0]["ID"]!="")
  				{
                                    
                        $verificar=$almacen->ObtenerFilasBySqlSelect("select * from $pos.stockcurrent where product='$itempos' ");
                        if($verificar[0]["PRODUCT"]!=""){
                          $instruccion = "update $pos.stockcurrent set UNITS=(UNITS+{$_POST["_cantidad"][$i]}) WHERE PRODUCT='$itempos'";
                        }else{
                        $instruccion = "insert into  $pos.stockcurrent values('0','$itempos',null,'".$_POST["_cantidad"][$i]."')"; 
                        }
                    $almacen->ExecuteTrans($instruccion);

                    if ($itemm[0]["unidad_venta"]==2) {
                            $pesado=1;
                        }elseif ($itemm[0]["unidad_venta"]==1) {
                            $pesado=0;
                        }

                    $instruccion="update $pos.products set PRICESELL=".$itemm[0]["precio1"].", CATEGORY='".$grupopos."', ISSCALE='".$pesado."' where ID = '".$itempos."'";


                    $almacen->ExecuteTrans($instruccion);
                }
        		else
        		{     
                    //Genero el itempos con el codigo de barra mas la fecha actual
                    $horaActual= date("Y-m-d");
                    $id_pos_nuevo=$cod_bar.$horaActual; 
                    $itempos=$comun->codigo_pos($id_pos_nuevo);

        			$iva=($itemm[0][iva])/100;
                    
                    if($itemm[0]["referencia"]=='')
                    {
                        $itemm[0]["referencia"]=$itemm[0]["cod_item"];
                    }

                    if ($itemm[0]["unidad_venta"]==2) {
                            $pesado=1;
                    }elseif ($itemm[0]["unidad_venta"]==1) {
                            $pesado=0;
                    }

        			$imp = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM $pos.taxes WHERE RATE='$iva' ");
        			$instruccion = "INSERT INTO  $pos.products (ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, ISCOM, ISSCALE, ATTRIBUTES, QUANTITY_MAX, TIME_FOR_TRY) VALUES ('".$itemm[0]["itempos"]."', '".$itemm[0]["referencia"]."', '".$itemm[0]["codigo_barras"]."', '".$itemm[0]["descripcion1"]."', '".$itemm[0]["coniva1"]."', '".$itemm[0]["precio1"]."', '".$itemm[0]["grupopos"]."', '".$imp[0]["CATEGORY"]."', 0, ".$pesado.", null, '".$itemm[0]["cantidad_rest"]."', '".$itemm[0]["dias_rest"]."')";
                	$almacen->ExecuteTrans($instruccion);

                    
                	//echo "<br>";
                	$instruccion = "INSERT INTO  $pos.stockcurrent (LOCATION, PRODUCT, UNITS) VALUES ('0','".$itemm[0]["itempos"]."', '{$_POST["_cantidad"][$i]}')";
                	$almacen->ExecuteTrans($instruccion);
                    //echo "<br>";
                	
    				$instruccion = "INSERT INTO  $pos.products_cat ( PRODUCT) VALUES ('".$itemm[0]["itempos"]."')";
       				$almacen->ExecuteTrans($instruccion);
				} 
			}
    	}
    }// Fin del For
    if ($almacen->errorTransaccion == 1)
    {    
        //echo "paso";   
        Msg::setMessage("<span style=\"color:#62875f;\">Traslado Generado Exitosamente </span>");
    }
    elseif($almacen->errorTransaccion == 0)
    {
        //echo "no paso";
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de cargar el traslado, por favor intente nuevamente.</span>");
    }
    $almacen->CommitTrans($almacen->errorTransaccion);

    //sexit;
    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}

$datos_almacen = $almacen->ObtenerFilasBySqlSelect("select * from almacen");
$valueSELECT = "";
$outputSELECT = "";
foreach ($datos_almacen as $key => $item) {
    $valueSELECT[] = $item["cod_almacen"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_almacen", $valueSELECT);
$smarty->assign("option_output_almacen", $outputSELECT);
?>
