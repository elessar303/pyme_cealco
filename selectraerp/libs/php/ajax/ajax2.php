<?php
session_start();
ini_set("display_errors",1);
require_once("../../../libs/php/adodb5/adodb.inc.php");
require_once("../../../libs/php/configuracion/config.php");
require_once("../../../libs/php/clases/ConexionComun.php");
require_once("../../../libs/php/clases/login.php");
require_once "../../../libs/php/clases/numerosALetras.class.php";

include("../../../../menu_sistemas/lib/common.php") ;

if(isset($_GET["opt"])==true||isset($_POST["opt"])==true){
$conn  = new ConexionComun();
$login = new Login();

$opt = (isset($_GET["opt"])) ? $_GET["opt"] : $_POST["opt"];

switch($opt){
    case "eliminar_asientoCXP":
     $instruccion = "select * from cxp_edocuenta_detalle where cod_edocuenta_detalle = '".$_GET["cod"]."'";
     $campos =  $conn->ObtenerFilasBySqlSelect($instruccion);

     $instruccion = "delete from tabla_impuestos where numero_control_factura = '".$campos[0]["numero"]."' and tipo_documento='c' and totalizar_monto_retencion='".$campos[0]["monto"]."'";
     echo $conn->Execute2($instruccion);

     $instruccion = "delete from cxp_edocuenta_detalle where cod_edocuenta_detalle = '".$_GET["cod"]."'";
     echo $conn->Execute2($instruccion);

     $instruccion = "update cxp_edocuenta set marca = '' where cod_edocuenta = ".$campos[0]["cod_edocuenta"];
     //echo $instruccion;
     $conn->Execute2($instruccion);
    break;



    case "eliminar_asientoCXC":

     $instruccion = "select * from cxc_edocuenta_detalle where cod_edocuenta_detalle = '".$_GET["cod"]."'";
     $campos =  $conn->ObtenerFilasBySqlSelect($instruccion);


     $instruccion = "delete from cxc_edocuenta_detalle where cod_edocuenta_detalle = '".$_GET["cod"]."'";
     echo $conn->Execute2($instruccion);

     $instruccion = "delete from tabla_impuestos where numero_control_factura = '".$campos[0]["numero"]."' and tipo_documento='f' and totalizar_monto_retencion='".$campos[0]["monto"]."'";
     echo $conn->Execute2($instruccion);

     $instruccion = "update cxc_edocuenta set marca = '' where cod_edocuenta = ".$campos[0]["cod_edocuenta"];
     echo $instruccion;
     $conn->Execute2($instruccion);
     
    break;

    case "impuestos":
    $instruccion = "select * from lista_impuestos as li
        left join formulacion_impuestos as fi on li.cod_formula=fi.cod_formula
        where cod_impuesto= '".$_GET["cod_impuesto"]."'";
    $campos = $conn->ObtenerFilasBySqlSelect($instruccion);
    $PORCENTAJE=$campos[0]["alicuota"];
    $PAGOMAYORA=$campos[0]["pago_mayor_a"];
    $MONTOSUSTRACCION=$campos[0]["monto_sustraccion"];
    $MONTOBASE=$_GET["monto_base"];
    $formula=$campos[0]["formula"];
    $resultado=eval($formula);
    //alert($formula)
    $calculo =  $_GET["monto_islr"]*$porcentaje;

    echo "[{'total_retencion':'".$MONTO."','porcentaje':'".$campos[0]["alicuota"]."','formula':'".$campos[0]["formula"]."','resultado':'".$MONTO."','codigo_impuesto':'".$campos[0]["cod_impuesto"]."','cod_tipo_impuesto':'".$campos[0]["cod_tipo_impuesto"]."'}]";
    break;

    case "impuesto_iva":
    $instruccion = "select * from impuesto_iva where cod_impuesto_iva = ".$_GET["cod_impuesto_iva"];
    $campos = $conn->ObtenerFilasBySqlSelect($instruccion);
    
    $calculo = $_GET["montoiva"]*($campos[0]["porcentaje"]/100);
    
    echo "[{'total_iva':'".($calculo)."','porcentaje':'".$campos[0]["porcentaje"]."'}]";
    break;
    case "ValidarCodigoitem":
    $campos = $conn->ObtenerFilasBySqlSelect("select * from item where cod_item = '".$_GET["v1"]."'");
 
    if(count($campos)==0){
        echo "1";
    }else{
        echo "-1";
    }
    break;
    case "DetalleCliente":
	$campos = $conn->ObtenerFilasBySqlSelect("select * from clientes where id_cliente = '".$_GET["v1"]."'");
	   
	if(count($campos)==0){
        echo "1";
    }else{
        echo json_encode($campos);	
    }	
    break;
	
    case "Detalleproveedor":
	$campos = $conn->ObtenerFilasBySqlSelect("select * from proveedor where id_proveedor = '".$_GET["v1"]."'");
	if(count($campos)==0){
        echo "1";
    }else{
        echo json_encode($campos);	
    }	
    break;

    case "ValidarCodigoCliente":
	$campos = $conn->ObtenerFilasBySqlSelect("select * from clientes where cod_cliente = '".$_GET["v1"]."'");
	   
	    if(count($campos)==0){
        echo "1";
    }else{
        echo "-1";
    }	
    break;
    
    case "ValidarNombreUsuario":
	$campos = $conn->ObtenerFilasBySqlSelect("select * from usuarios where usuario = '".$_GET["v1"]."'");
	    if(count($campos)==0){
        echo "1";
    }else{
        echo "-1";
    }	
	break;
    case "Selectitem":
	$campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `cod_item_forma` = '".$_GET["v1"]."' and estatus = 'A' order by descripcion1 asc");
	//SELECT * FROM `item` as i left join compra as c on c.id_proveedor=6 left join compra_detalle as cd on c.id_compra=cd.id_compra WHERE i.cod_item_forma = 1 and i.id_item) 
        if(count($campos)==0){
		echo "[{id:'-1'}]";
	}else{
		echo json_encode($campos);		
	}
    break;

    case "Selectitemporproveedor":
	$campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `cod_item_forma` = '".$_GET["v1"]."' and estatus = 'A' order by descripcion1 asc");
	//SELECT * FROM `item` as i left join compra as c on c.id_proveedor=6 left join compra_detalle as cd on c.id_compra=cd.id_compra WHERE i.cod_item_forma = 1 and i.id_item)
        if(count($campos)==0){
		echo "[{id:'-1'}]";
	}else{
		echo json_encode($campos);
	}
    break;

    case "DetalleSelectitem":
	$campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `id_item` = '".$_GET["v1"]."'");
	if(count($campos)==0){
		echo "[{id:'-1'}]";
	}else{
		echo json_encode($campos);		
	}
    break;

    case "CargarAlmacenesDisponiblesByIdItem":
	$campos = $conn->ObtenerFilasBySqlSelect("select * FROM vw_existenciabyalmacen where id_item = '".$_GET["v1"]."' and cantidad > 0 order by cod_almacen");
	if(count($campos)==0){
		echo "[{id:'-1'}]";
	}else{
		echo json_encode($campos);		
	}
    break;

    case "verificarExistenciaItemByAlmacen":

        $campos = $conn->ObtenerFilasBySqlSelect("select * FROM vw_item_precomprometidos
											where id_item = '".$_GET["v2"]."' and cod_almacen = '".$_GET["v1"]."'");

        if(count($campos)==0){
		echo "[{id:'-1'}]";
	}else{
		echo json_encode($campos);		
	}
    break;
    case "precomprometeritem":

        
$campos = $conn->ObtenerFilasBySqlSelect("select * FROM vw_item_precomprometidos
        where id_item = '".$_GET["v1"]."' and cod_almacen = '".$_GET["codalmacen"]."'");

$cantidadExistenteOLD = $campos[0]["cantidad"];
$cantidadPedidad   = $_GET["cpedido"];

$cantidadExistenteNEW = $cantidadExistenteOLD-$cantidadPedidad;
if($cantidadExistenteNEW<0){
    echo "[{'id':'-99','observacion':'La cantidad Pedida es mayor a la existente.'}]";
    exit;
}


$campos = $conn->ObtenerFilasBySqlSelect("select * FROM item where id_item = ".$_GET["v1"]." and cod_item_forma = 1"); // 1: item producto
if(count($campos)>0){
$instruccion = "
INSERT INTO item_precompromiso (
`id_item_precomiso` ,
`cod_item_precompromiso` ,
`id_item` ,
`cantidad` ,
`usuario_creacion` ,
`fecha_creacion` ,
`idSessionActualphp`,
`almacen`
)
VALUES (
NULL , '".$_GET["codprecompromiso"]."','".$_GET["v1"]."', '".$_GET["cpedido"]."', '".$login->getUsuario()."',
CURRENT_TIMESTAMP , '".$login->getIdSessionActual()."','".$_GET["codalmacen"]."'
);
";

$conn->Execute2($instruccion);
echo "[{'id':'1','observacion':''}]";



        }else{
           echo "[{'id':'-1','observacion':''}]";
        }
    break;



    
    case "delete_precomprometeritem":
        $instruccion = "
        delete from item_precompromiso where
        cod_item_precompromiso = '".$_GET["codprecompromiso"]."'  and
        idSessionActualphp = '".$login->getIdSessionActual()."'      and
        usuario_creacion = '".$login->getUsuario()."' and
        id_item = '".$_GET["v1"]."'";
        $conn->Execute2($instruccion);

        break;

        case "det_edocuentacxp":

$data_parametros = $conn->ObtenerFilasBySqlSelect("select * from parametros_generales");
foreach($data_parametros as $key => $lista){
    $valueSELECT[] = $lista["cod_empresa"];
    $outputidfiscalSELECT[] = $lista["moneda"];
}
$campos = $conn->ObtenerFilasBySqlSelect("SELECT *
,vw_cxp.numero as num_cdet
,cxp_edocuenta.vencimiento_persona_contacto,
cxp_edocuenta.vencimiento_telefono,
cxp_edocuenta.vencimiento_descripcion from vw_cxp
 inner join cxp_edocuenta on cxp_edocuenta.cod_edocuenta = vw_cxp.cod_edocuenta
where vw_cxp.cod_edocuenta = ".$_GET["cod_edocuenta"]);
    if(count($campos)==0){
        exit;
    }
        echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div  style=" background-color:#fdfdfd; border: 1px solid #ededed;-moz-border-radius: 7px;padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px;  font-size: 13px; ">
                <table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Documento</th>
                        <th style="border-bottom: 1px solid #949494;">Numero</th>
                        <th style="border-bottom: 1px solid #949494;width:120px;">Fecha Emisión</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">Descripción</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Debito</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Credito</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Opt</th>
                    </thead>
                    <tbody>';


        $acuDebitos = 0;
        $acuCreditos = 0;
    foreach($campos as $key => $item){
        echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">'.$item["cod_edocuenta_detalle"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:110px;">'.$item["documento_cdet"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;">'.$item["num_cdet"].'</td>
                            <td align="center" style="border-bottom: 1px solid #949494;width:120px;">'.$item["fecha_emision_edodet"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:300px;">'.$item["descripcion"].'</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">'.number_format($item['debito'], 2, ",", ".").' '.$lista["moneda"].' </td>
                            <td align="right" style="border-bottom: 1px solid #949494;">'.number_format($item['credito'], 2, ",", ".").' '.$lista["moneda"].' </td>
                            <td align="right" style="border-bottom: 1px solid #949494;">';


        
if($key>0){
    echo '<img class="eliminarAsiento"  style="cursor:pointer;" title="Eliminar Asiento" src="../../libs/imagenes/cancel.gif">';
    echo "<input type='hidden' id='detalle_asiento' name='detalle_asiento' value='".$item["cod_edocuenta_detalle"]."'>";
}

        echo '</td>
        </tr>';

        $acuDebitos  += $item['debito'];
        $acuCreditos += $item['credito'];
    }
    echo '
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;width:300px;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Total Debitos/Creditos:</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>'.number_format($acuDebitos, 2, ",", ".").' '.$lista["moneda"].'</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>'.number_format($acuCreditos , 2, ",", ".").' '.$lista["moneda"].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Saldo Pendiente:</b></td>
                            <td colspan="2"align="right" style="border-bottom: 1px solid #949494;"><b style="color:red;">'.number_format($acuCreditos-$acuDebitos, 2, ",", ".").' '.$lista["moneda"].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;">

                            </td>
                        </tr>
    ';

if($campos[0]["marca"]!="X"){
         echo '

                        <tr>
                            <td colspan="6" align="left" style="border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=85&opt_seccion=88&opt_subseccion=pagoabonoCXP&cod='.$_GET["codigo_proveedor"].'&cod2='.$_GET["cod_edocuenta"].'\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Agregar Pago/Abono</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
			<td colspan="7" align="left" style="border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=85&opt_seccion=88&opt_subseccion=facturasCXP&cod='.$_GET["codigo_proveedor"].'&cod2='.$_GET["cod_edocuenta"].'\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Facturas/Notas de Credito</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
        ';}



    echo
    '</tbody>
    </table>
    </div>
    </td>
    </tr>';




    break;

    case "det_edocuenta":
$data_parametros = $conn->ObtenerFilasBySqlSelect("select * from parametros_generales");
foreach($data_parametros as $key => $lista){
    $valueSELECT[] = $lista["cod_empresa"];
    $outputidfiscalSELECT[] = $lista["moneda"];
}
    $campos = $conn->ObtenerFilasBySqlSelect("SELECT *
,vw_cxc.numero as num_cdet
,cxc_edocuenta.vencimiento_persona_contacto,
cxc_edocuenta.vencimiento_telefono,
cxc_edocuenta.vencimiento_descripcion from vw_cxc
 inner join cxc_edocuenta on cxc_edocuenta.cod_edocuenta = vw_cxc.cod_edocuenta
where vw_cxc.cod_edocuenta = ".$_GET["cod_edocuenta"]);
    if(count($campos)==0){
        exit;
    }
        echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div  style=" background-color:#fdfdfd; border: 1px solid #ededed;-moz-border-radius: 7px;padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px;  font-size: 13px; ">
                <table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Documento</th>
                        <th style="border-bottom: 1px solid #949494;">Numero</th>
                        <th style="border-bottom: 1px solid #949494;width:120px;">Fecha Emisión</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">Descripción</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Debito</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Credito</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Opt</th>
                    </thead>
                    <tbody>';


        $acuDebitos = 0;
        $acuCreditos = 0;
    foreach($campos as $key => $item){
        echo '
                        <tr> 
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">'.$item["cod_edocuenta_detalle"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:110px;">'.$item["documento_cdet"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;">'.$item["num_cdet"].'</td>
                            <td align="center" style="border-bottom: 1px solid #949494;width:120px;">'.$item["fecha_emision_edodet"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:300px;">'.$item["descripcion"].'</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">'.number_format($item['debito'], 2, ",", ".").' '.$lista["moneda"].'</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">'.number_format($item['credito'], 2, ",", ".").' '.$lista["moneda"].'</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">';
//if($item['debito']=="0.00"){
if($key>0){
echo '<img class="eliminarAsiento"  style="cursor:pointer;" title="Eliminar Asiento" src="../../libs/imagenes/cancel.gif">';
echo "<input type='hidden' id='detalle_asiento' name='detalle_asiento' value='".$item["cod_edocuenta_detalle"]."'>";
}

        echo '</td>
        </tr>';

        $acuDebitos  += $item['debito'];
        $acuCreditos += $item['credito'];
    }
    echo '
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;width:300px;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Total Debitos/Creditos:</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>'.number_format($acuDebitos, 2, ",", ".").' '.$lista["moneda"].'</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>'.number_format($acuCreditos , 2, ",", ".").'  '.$lista["moneda"].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Saldo Pendiente:</b></td>
                            <td colspan="2"align="right" style="border-bottom: 1px solid #949494;"><b style="color:red;">'.number_format($acuDebitos-$acuCreditos, 2, ",", ".").'  '.$lista["moneda"].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="7" align="right" style="border-bottom: 1px solid #949494;width:300px;">

                            </td>
                        </tr>
    ';


if($campos[0]["marca"]!="X"){
         echo '

                        <tr>
                            <td colspan="6" align="left" style="border-bottom: 1px solid #949494;width:110px;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=6&opt_seccion=59&opt_subseccion=pagooabono&cod='.$_GET["codigo_cliente"].'&cod2='.$_GET["cod_edocuenta"].'\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Agregar Pago/Abono</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                            <br>
                            <img src="../../libs/imagenes/ico_user.gif"> Persona de Contacto: '.$campos[0]["vencimiento_persona_contacto"].'<br>
                            <img src="../../libs/imagenes/ico_cel.gif"> Telefono de Contacto: '.$campos[0]["vencimiento_telefono"].'<br>
                            <img src="../../libs/imagenes/ico_view.gif"> Observación: '.$campos[0]["vencimiento_descripcion"].'<br>
                            <img src="../../libs/imagenes/ew_calendar.gif"> Fecha de Vencimiento: '.$campos[0]["vencimiento_fecha"].'<br>
                            
                        </td>
                        </tr>
        ';}
    

    echo
    '</tbody>
    </table>
    </div>
    </td>
    </tr>';
         
    

   
    break;

    case "det_items":
    if($_GET["id_tipo_movimiento_almacen"]=='3'||$_GET["id_tipo_movimiento_almacen"]=='1')
    {
        $operacion="Entrada";
        $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item where kad.id_transaccion = ".$_GET["id_transaccion"]);

    }else if($_GET["id_tipo_movimiento_almacen"]=='4' || $_GET["id_tipo_movimiento_almacen"]=='2')
    {
        $operacion="Salida";
        $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item where kad.id_transaccion = ".$_GET["id_transaccion"]);
    }else if($_GET["id_tipo_movimiento_almacen"]=='5' )
    {
        $operacion="Traslado";
        $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
        from kardex_almacen_detalle as kad left join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item where kad.id_transaccion = ".$_GET["id_transaccion"]);

        $campos1 = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item where kad.id_transaccion = ".$_GET["id_transaccion"]);
    }
    //$campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
//from kardex_almacen_detalle as kad left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item where id_transaccion = ".$_GET["id_transaccion"]);
    //echo $campos;
    if(count($campos)==0){
        exit;
    }

    if($_GET["id_tipo_movimiento_almacen"]=='5')
    {
        echo '<tr class="detalle_items">
          <td colspan="8">
            <div  style=" background-color:#fdfdfd; border: 1px solid #ededed;-moz-border-radius: 7px;padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px;  font-size: 13px; ">
                <table >
                    <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:150px;">Almacen Entrada</th>
                        <th style="border-bottom: 1px solid #949494;width:150px;">Almacen Salida</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">Item</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Cantidad</th>
                    </thead>
                    <tbody>';
    }else
    {
        echo '<tr class="detalle_items">
          <td colspan="8">
            <div  style=" background-color:#fdfdfd; border: 1px solid #ededed;-moz-border-radius: 7px;padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px;  font-size: 13px; ">
                <table >
                    <thead>
                        <th align="center" style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:150px;">Almacen '.$operacion.'</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">Item</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Cantidad</th>
                    </thead>
                    <tbody>';
    }

    foreach($campos as $key => $item){
        if($_GET["id_tipo_movimiento_almacen"]=='5')
        {
            echo '
                        <tr>
                            <td align="left" style="border-bottom: 1px solid #949494;width:110px;">'.$item["id_transaccion_detalle"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:150px;">'.$item["descripcion"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:150px;">'.$campos1[0]["descripcion"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:300px;">'.$item["descripcion1"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;">'.$item['cantidad_item'].'</td>

                        </tr>';
        }else
        {
            echo '
                        <tr>
                            <td align="left" style="border-bottom: 1px solid #949494;width:110px;">'.$item["id_transaccion_detalle"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:150px;">'.$item["descripcion"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;width:300px;">'.$item["descripcion1"].'</td>
                            <td align="left" style="border-bottom: 1px solid #949494;">'.$item['cantidad_item'].'</td>

                        </tr>';
        }
    }

if($campos[0]["estado"]=="Pendiente"){
         echo '

                        <tr>
                            <td colspan="6" align="left" style="border-bottom: 1px solid #949494;width:110px;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=3&opt_seccion=108&opt_subseccion=movimientoalmacen&cod='.$_GET["id_transaccion"].'&cod2='.$_GET["cod_edocuenta"].'\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Realizar Movimiento de Almacen</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
        ';}


    echo
    '</tbody>
    </table>
    </div>
    </td>
    </tr>';
    
    break;

    case "getAlmacen":

        $campos = $conn->ObtenerFilasBySqlSelect("select * from almacen");

        if(count($campos)==0){
		echo "[{id:'-1'}]";
	}else{
		echo json_encode($campos);
	}
    break;
    
    case 'listaCXPpendientes':
            	header("Content-Type: text/plain");
$groupByBeneficiario = isset($_POST["groupBene"])?'si':'no';
if($groupByBeneficiario=="no"){
    $sql_ = "SELECT   pro.id_proveedor, cxpd. * , pro.descripcion AS beneficiario, pro.rif, cxpd.monto as monto_pagar, (
    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'd'
    ) AS sum_debito, (

    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'c'
    ) AS sum_credito, (
    (

    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'c'
    ) - (
    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'd' )
    ) AS monto_pendiente
    FROM cxp_edocuenta_detalle cxpd
    INNER JOIN cxp_edocuenta cxp ON cxpd.cod_edocuenta = cxp.cod_edocuenta
    INNER JOIN proveedores pro ON pro.id_proveedor = cxp.id_proveedor
    WHERE cxpd.marca = 'P'
    ";

    if(isset($_POST["id_proveedor"])){
        $sql_ .= " and  pro.id_proveedor = ".$_POST["id_proveedor"];
    }


$campos = $conn->ObtenerFilasBySqlSelect($sql_);



$start = isset($_POST['start'])?$_POST['start']:0; //posición a iniciar
$limit = isset($_POST['limit'])?$_POST['limit']:5; //número de registros a mostrar

echo json_encode(array(
                    "success" => true,
                    "total" => count($campos),
                    "data" => array_splice($campos,$start,$limit)
                ));

}

if($groupByBeneficiario=="si"){
    $sql_ ="SELECT  distinct  pro.id_proveedor, pro.descripcion AS beneficiario
FROM cxp_edocuenta_detalle cxpd
INNER JOIN cxp_edocuenta cxp ON cxpd.cod_edocuenta = cxp.cod_edocuenta
INNER JOIN proveedores pro ON pro.id_proveedor = cxp.id_proveedor
WHERE cxpd.marca = 'P'";


$campos = $conn->ObtenerFilasBySqlSelect($sql_);

echo json_encode(array(
                    "success" => true,
                    "total" => count($campos),
                    "data" => $campos
                ));

}




    
    break;
case "convertiraLetras":

header("Content-Type: text/plain");

$n = new numerosALetras();
$numero=$_GET["monto"];
$num_letras=$n->convertir($numero);

$array = array(
    "success" => true,
    "monto" => $num_letras
);
echo json_encode($array);
break;
case "tesodetasientos":
header("Content-Type: text/plain");
    $cod_cheque = $_POST["cod_cheque"];
    $sql_ = "
SELECT
cod_cheque_bauchedet,
cod_cheque,
descripcion,
cuenta_contable,
case tipo when 'd' then monto else '' end as debito,
case tipo when 'c' then monto else '' end as credito
 FROM `cheque_bache_det` where cod_cheque = ".$cod_cheque." order by tipo desc
";
    $campos = $conn->ObtenerFilasBySqlSelect($sql_);
    echo json_encode(array(
        "success"=>true,
        "total"=>count($campos),
        "data"=> $campos
    ));
break;
case "store_cuContable":
header("Content-Type: text/plain");
    // CONSULTA DE CUENTAS CONTABLES
$global=new bd(SELECTRA_CONF_PYME);

if(isset ($_POST["query"])){
    if($_POST["query"]==""){
        $cuentalike = " order by cuenta";
    }  else {
        $cuentalike = " and upper(concat(cuenta,' .-',Descrip)) like upper('%".$_POST["query"]."%') order by cuenta";
    }
    
}
$sentencia="select * from nomempresa where bd='".$_SESSION['EmpresaFacturacion']."'";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();
$campos_cuentas_cont = $conn->ObtenerFilasBySqlSelect("select concat(cuenta,' .-',Descrip) as descripcion, cuenta from ".$fila['bd_contabilidad'].".cwconcue where Tipo='P'".$cuentalike);
//echo "select cuenta as descripcion, cuenta from ".$fila['bd_contabilidad'].".cwconcue where Tipo='P'".$cuentalike." order Cuenta";
$campos_cuentas_Cant = $conn->ObtenerFilasBySqlSelect("select cuenta as descripcion, cuenta from ".$fila['bd_contabilidad'].".cwconcue where Tipo='P'".$cuentalike);

echo json_encode(array(
    "success"   => true,
    "total"     => count($campos_cuentas_Cant),
    "data"      => $campos_cuentas_cont
    ));
break;
case "store_tipoCuenta":
    $campos_comunes = $conn->ObtenerFilasBySqlSelect("select * from tipo_cuenta_banco");
echo json_encode(array(
    "success"   => true,
    "total"     => count($campos_comunes),
    "data"      => $campos_comunes
    ));
    break;

case "aCheBaucheDetCRUP":

    if($_POST["cod_cheque_bauchedet"]!=""&&$_POST["in_deleted"]!=1){//UPDATIAR
        $sql = "
            update cheque_bache_det set 
                        `monto` = ".$_POST["monto"].",
                        `tipo` = '".(($_POST["tipo_a"]=="Debito")?'d':'c')."',
                        `descripcion` = '".$_POST["descripcion"]."',
                        cuenta_contable = '".$_POST["cuenta_contable"]."'
           where cod_cheque_bauchedet = ".$_POST["cod_cheque_bauchedet"];
          $conn->Execute2($sql);

    }elseif($_POST["in_deleted"]=="1"){

        $sql = "delete from cheque_bache_det where cod_cheque_bauchedet = ".$_POST["cod_cheque_bauchedet"];
        $conn->Execute2($sql);
    }else{//NUEVO ASIENTO CHEQUE BAUCHE DET
        $sql = "
            INSERT INTO `cheque_bache_det` (
                        `cod_cheque`,
                        `monto`,
                        `tipo`,
                        `fecha`,
                        `descripcion`,
                        `fecha_creacion`,
                        `usuario_creacion`,cuenta_contable)
                        VALUES (
                            ".$_POST["cod_cheque"].",
                            ".$_POST["monto"].",
                            '".(($_POST["tipo_a"]=="Debito")?'d':'c')."',
                            '".date("Y-m-d")."',
                            '".$_POST["descripcion"]."',
                            CURRENT_TIMESTAMP,
                            '".$_SESSION['usuario']."',
                            '".$_POST["cuenta_contable"]."');";
          $conn->Execute2($sql);
    }
    
    echo json_encode(array(
        "success"   => true,
        "msg"       => "Asiento registrado exitosamente."
    ));

    
    break;
case "listaProveedores":
    $campos_comunes = $conn->ObtenerFilasBySqlSelect("
    select
        id_proveedor,
        cod_proveedor,
        descripcion as  beneficiario,
        direccion,
        telefonos,
        fax,
        email,
        rif,
        nit
    from
        proveedores");
echo json_encode(array(
    "success"   => true,
    "total"     => count($campos_comunes),
    "data"      => $campos_comunes
    ));
    break;
case 'movimientos_bancarios_conciliar':

list($dia1, $mes1,$anio1) = explode("/",$_POST["fecha1_"]);
list($dia2, $mes2,$anio2) = explode("/",$_POST["fecha2_"]);
$fecha1 = $anio1."-".$mes1."-".$dia1;
$fecha2 = $anio2."-".$mes2."-".$dia2;
$cod_cuenta = $_POST["cod_cuenta"];
$sql = "
SELECT
mb.cod_movimiento_ban,
mb.cod_tesor_bancodet,
tm.descripcion as tipo_movimiento_desc,
mb.numero_movimiento,
mb.fecha_movimiento,
mb.concepto,
case when mb.tipo_movimiento =  3 or mb.tipo_movimiento =  4 then mb.monto  else 0 end debe,
case when mb.tipo_movimiento  =  1 or mb.tipo_movimiento =  2 then mb.monto  else 0 end haber,
mb.tipo_movimiento,
mb.estado,
mb.cod_conciliacion,
'false' as conciliar
 FROM `movimientos_bancarios` mb inner join tipo_movimientos_ban tm
 on tm.cod_tipo_movimientos_ban = mb.tipo_movimiento
 where mb.fecha_movimiento between '".$fecha1."' and '".$fecha2."'
 and mb.cod_tesor_bancodet = ".$cod_cuenta."  and mb.cod_conciliacion is null
order by mb.cod_movimiento_ban";
$campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);

echo json_encode(array(
            "success"   => true,
            "total"     => count($campos_comunes),
            "data"      => $campos_comunes
        ));

    break;

	case 'cxpIvaFactura':
	
	$MONTOBASE = $_GET[montoBase];
	$codIva = $_GET[codIva];
	
	$ivas=$conn->ObtenerFilasBySqlSelect("select li.alicuota, fi.formula from lista_impuestos li join formulacion_impuestos fi on (li.cod_formula=fi.cod_formula) where li.cod_impuesto=$codIva");
	$PORCENTAJE=$ivas[0][alicuota];
	eval($ivas[0][formula]);
	echo $cad=$PORCENTAJE.'-'.$MONTO;
	break;

	case 'cxpRetIslrFactura':
	$id_item=$_GET[servicio];
	$cod_entidad=$_GET[entidad];
	$item_totalsiniva=$_GET[montoBase];
	$islr = $conn->ObtenerFilasBySqlSelect("select si.cod_lista_impuesto, fi.formula, li.alicuota, li.pago_mayor_a, li.monto_sustraccion, li.descripcion, li.cod_impuesto from servicios_islr si join lista_impuestos li on (si.cod_lista_impuesto=li.cod_impuesto) join formulacion_impuestos fi on (fi.cod_formula=li.cod_formula) where si.cod_item=$id_item and li.cod_entidad=$cod_entidad and li.pago_mayor_a<$item_totalsiniva");
	if($islr[0])
	{
		$PORCENTAJE=$islr[0]["alicuota"];
		$MONTOBASE=$item_totalsiniva;
		$formula=$islr[0]["formula"];
		eval($formula);
		
		echo $MONTO;
	}
	else
		echo $cad=0;
	break;
	
}


}



?>
