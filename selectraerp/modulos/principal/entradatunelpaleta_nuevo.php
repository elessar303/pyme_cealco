<?php
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/usuarios.php");

$usuarios = new Usuarios();
$arrayCodModulo = array();
$arrayNomModulo = array();
$id_transaccion=$_GET['id'];
//servicios asociados al cargo
$sql="select id_tipo_movimiento_almacen from tipo_movimiento_almacen where descripcion= '   
Cargo Tunel'";
$id_movimiento=$usuarios->ObtenerFilasBySqlSelect($sql);
$sql="select id_movimiento_almacen, id_servicio from movimiento_almacen_servicio where id_movimiento_almacen = ".$id_movimiento[0]['id_tipo_movimiento_almacen'];
$buscarservicios=$usuarios->ObtenerFilasBySqlSelect($sql);
$checkbox="";
foreach ($buscarservicios as $servicios) 
{
    $sql="select id_item, cod_item, precio1, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
    $contarservicio=$usuarios->ObtenerFilasBySqlSelect($sql);
    $checkbox[]=['id' => $contarservicio[0]['id_item'], 'nombre' => $contarservicio[0]['descripcion1'],];
}
//print_r($checkbox); exit();
$smarty->assign("checkbox", $checkbox);

            
$smarty->assign("movimiento", $id_transaccion);
//buscar si existe movimientos con este id
$sql="SELECT *,a.id_transaccion as id FROM 
    kardex_almacen as a 
    inner join  
    kardex_almacen_detalle as b on a.id_transaccion=b.id_transaccion
    inner join item as c on b.id_item=c.id_item
    where a.id_transaccion_calidad=
    (select a.id_transaccion 
    from calidad_almacen as a 
    inner join calidad_almacen_detalle as b on a.id_transaccion=b.id_transaccion 
    where b.id_transaccion_detalle=".$id_transaccion.")";
//print_r($sql); exit();
$datos=$usuarios->ObtenerFilasBySqlSelect($sql);
if($datos==null)
{
    //no existen transacciones todavia, buscar la cantidad total y mandar mensaje no hay datos
    $sql=
    "   
        select a.id_ticket_entrada, b.cantidad, c.unidad_paleta, c.descripcion1 as nombre_producto from
        calidad_almacen as a
        inner join calidad_almacen_detalle as b on a.id_transaccion=b.id_transaccion
        inner join item as c on b.id_item=c.id_item
        where b.id_transaccion_detalle=".$id_transaccion."
    ";
    $total=$usuarios->ObtenerFilasBySqlSelect($sql);
    //total peso es 0
    $smarty->assign("totalpeso", 0);
    $smarty->assign("visiblecerrar", 0);
    $smarty->assign("total", $total[0]['cantidad']);
    $smarty->assign("paleta", $total[0]['unidad_paleta']);
    $smarty->assign("nombre_producto", $total[0]['nombre_producto']);
    $smarty->assign("id_ticket_entrada", $total[0]['id_ticket_entrada']);
    $smarty->assign("ticket", 1);
    $datostabla="<tr><td colspan='10' align='center'> <b>No Hay Entradas Registradas</b> </td></tr>";
    $smarty->assign("datostabla", $datostabla);
}
else
{
    if($datos[0]['cierre_entrada']==1)
    {
        //no debe verse nada
        $smarty->assign("visiblecerrar", 2);
        
    }
    else
    {
        //debemos buscar si esta cerrado el detalle de esta calidad
        $sql="select cierre_entrada from calidad_almacen_detalle where id_transaccion_detalle='".$id_transaccion."'";
        $cierre_entrada=$usuarios->ObtenerFilasBySqlSelect($sql);
        if($cierre_entrada[0]['cierre_entrada']==1)
        {
            $smarty->assign("visiblecerrar", 2);
        }
        else
        {
            $smarty->assign("visiblecerrar", 1);
        }
        
    }
    
    //buscar el peso total ingresado al almacen
    $sql=
    "
        SELECT sum(b.peso) as peso FROM 
        kardex_almacen as a 
        inner join  
        kardex_almacen_detalle as b on a.id_transaccion=b.id_transaccion
        inner join item as c on b.id_item=c.id_item
        where a.id_transaccion_calidad=
        (select a.id_transaccion 
        from calidad_almacen as a 
        inner join calidad_almacen_detalle as b on a.id_transaccion=b.id_transaccion 
        where b.id_transaccion_detalle=".$id_transaccion.")
    ";
    $totalpeso=$usuarios->ObtenerFilasBySqlSelect($sql);
    //existen transacciones, buscar la cantidad disponible
    $sql=
    "
        select id_item from calidad_almacen_detalle where id_transaccion_detalle=".$id_transaccion;
    
    $id_item=$usuarios->ObtenerFilasBySqlSelect($sql);
    $sql=
    "   select 
        (
            (select b.cantidad from
            calidad_almacen as a
            inner join calidad_almacen_detalle as b on a.id_transaccion=b.id_transaccion
            inner join item as c on b.id_item=c.id_item
            where b.id_transaccion_detalle=".$id_transaccion.") 
            - 
            (select  COALESCE(sum(b.cantidad), 0) from
            kardex_almacen as a
            inner join kardex_almacen_detalle as b on a.id_transaccion=b.id_transaccion
            where a.id_transaccion=".$datos[0]['id']." and b.id_item=".$id_item[0]['id_item'].")
        ) as total
        
    ";
    $total=$usuarios->ObtenerFilasBySqlSelect($sql);
    $sql=
    "
        select descripcion1 from item where id_item=".$id_item[0]['id_item']."
    ";
    $nombre=$usuarios->ObtenerFilasBySqlSelect($sql);
    $smarty->assign("totalpeso", $totalpeso[0]['peso']);
    $smarty->assign("nombre_producto", $nombre[0]['descripcion1']);
    $smarty->assign("total", $total[0]['total']);
    $smarty->assign("paleta", $datos[0]['unidad_paleta']);
    $smarty->assign("id_ticket_entrada", "");
    //construir tabla de datos 
    $sql=
    "
        select b.id_transaccion_detalle as detalle_movimiento,  e.nombre as cliente, d.descripcion, c.codigo_barras, c.descripcion1, b.lote, b.cantidad, b.peso, d.descripcion as ubicacion, a.id_documento, f.descripcion as almacen, a.ticket_entrada, m.marca
        from 
        kardex_almacen as a
        inner join kardex_almacen_detalle as b on a.id_transaccion=b.id_transaccion
        left join marca as m on m.id=b.id_marca
        inner join item as c on b.id_item=c.id_item
        inner join ubicacion as d on b.id_ubi_entrada=d.id
        inner join clientes as e on a.id_proveedor=e.id_cliente
        inner join almacen as f on b.id_almacen_entrada=f.cod_almacen
        where a.id_transaccion_calidad=
        (select a.id_transaccion 
        from calidad_almacen as a 
        inner join calidad_almacen_detalle as b on a.id_transaccion=b.id_transaccion 
        where b.id_transaccion_detalle=".$id_transaccion." and c.id_item=".$id_item[0]['id_item'].")
    ";
    $datostabla=$usuarios->ObtenerFilasBySqlSelect($sql);
    //print_r($sql); exit();
    foreach($datostabla as $clave => $valor)
    {
        $tabla.=
        "
            <tr>
                <td align='center'>
                    ".$valor['cliente']."
                </td>
                
                <td align='center'>
                    ".$valor['codigo_barras']."
                </td>
                <td align='center'>
                    ".$valor['descripcion1']." - ".$valor['marca']."
                </td>
                <td align='center'>
                    ".$valor['lote']."
                </td>
                <td align='center'>
                    ".$valor['cantidad']."
                </td>
                <td align='center'>
                    ".$valor['peso']."
                </td>
                <td align='center'>
                    ".$valor['almacen']."
                </td>
                <td align='center'>
                    ".$valor['ubicacion']."
                </td>
                <td align='center'>
                    ".$valor['ticket_entrada']."
                </td>
                <td align='center'>
                    <button id='".$valor['detalle_movimiento']."' value='Generar Ticket' onclick='llamarPdf(this.id)'>PDF</button>
                </td>
                <td align='center'>
                    <button id='".$valor['detalle_movimiento']."' value='Generar Ticket' onclick='llamarTXT(this.id)'>TXT </button>
                </td>
                <td align='center'>
                    <button id='".$valor['detalle_movimiento']."' value='Eliminar' onclick='EliminarPaleta(this.id)'>Eliminar</button>
                </td>
            </tr>
        ";
    }

    $smarty->assign("datostabla", $tabla);
    $smarty->assign("ticket", 0);
}
$modulos = $usuarios->ObtenerFilasBySqlSelect("SELECT cod_modulo, nom_menu FROM modulos WHERE cod_modulo_padre IS NULL AND visible = 1 AND cod_modulo != 54 ORDER BY orden");
$smarty->assign("modulos", $modulos);

//agregando almacenes
$almacenes= $usuarios->ObtenerFilasBySqlSelect("select * from almacen");
foreach ($almacenes as $key => $item) 
{
    $arraySelectOption[] = $item["cod_almacen"];
    $arraySelectOutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_ubicacion_principal", $arraySelectOption);
$smarty->assign("option_output_ubicacion_principal", $arraySelectOutPut);
$arraySelectOption="";
$arraySelectOutPut="";

$arraySelectOption_tipo=array(0,1);
$arraySelectOutPut_tipo=array('PYME','POS');


if (isset($_POST["aceptar"])) {

    $instruccion = "
    INSERT INTO caja_impresora (caja_host, serial_impresora, ip, caja_tipo)
    VALUES ('" . $_POST["nombre_caja"]. "', '" . $_POST["serial"] . "','" . $_POST["ip"] . "','" . $_POST["tipo_caja"] . "');";

    $usuarios->Execute2($instruccion);


    $pyme=$usuarios->ObtenerFilasBySqlSelect("select venta_pyme from parametros_generales");
if($pyme[0]['venta_pyme']==1){
  $instruccion = "
    INSERT INTO cierre_caja_control (cajas, secuencia, estatus_cierre) (SELECT host, max(hostsequence)-1, 1 from  ".POS.".closedcash where host='".$_POST["nombre_caja"]."');";    
    $usuarios->Execute2($instruccion);

$sql="SELECT numero_z+1 as numero_z FROM libro_ventas WHERE serial_impresora='".$_POST["serial"]."' order by id desc limit 1";

$nro_z_insert= $usuarios->ObtenerFilasBySqlSelect($sql);

$filas_nro_z=$usuarios->getFilas($nro_z_insert);


if($filas_nro_z==0){
$z=0;
}else{
$z=$nro_z_insert[0]['numero_z'];
}

}else{

$instruccion = "
    INSERT INTO `closedcash_pyme`(nombre_caja, serial_caja, money, fecha_inicio, fecha_fin, secuencia) VALUES ('".$_POST['nombre_caja']."', '".$_POST['serial']."', '".$_POST['serial'].$_POST['ip'].date('Y-m-d_H:i:s')."',  now(), null, 1)";
    $usuarios->Execute2($instruccion);
    $instruccion = "
    INSERT INTO cierre_caja_control_pyme (serial_cajas, nombre_cajas, secuencia, estatus_cierre) (SELECT  serial_caja, nombre_caja, max(secuencia)-1, 1 from  closedcash_pyme where nombre_caja='".$_POST["nombre_caja"]."' and serial_caja='".$_POST['serial']."');";    
    $usuarios->Execute2($instruccion);


}
   echo "<script type=\"text/javascript\">
       history.go(-1);
   </script>";
    exit;
}
?>
