<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
require_once("../../libs/php/clases/login.php");
$bdCentral= "selectrapyme_central";
$almacen = new Almacen();
$login = new Login();
$pos=POS;

$puede_vender=$almacen->ObtenerFilasBySqlSelect("select ajuste_inventario from usuarios where cod_usuario='".$login->getIdUsuario()."'");
if($puede_vender[0]['ajuste_inventario']==0){
    echo "<script type='text/javascript'>alert('Error, Usted No Posee Permisos Para Realizar Ajustes de Inventario');window.history.back();
    window.close();
    </script>"; exit();}

$campos = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM almacen;");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["cod_almacen"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("name_form", "reporte");
$smarty->assign("option_values_almacen", $arraySelectOption);
$smarty->assign("option_output_almacen", $arraySelectoutPut);

$arraySelectOption = "";
$arraySelectoutPut = "";
$provee = new Proveedores();
$campos = $provee->ObtenerFilasBySqlSelect("SELECT * FROM proveedores;");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_provee", $arraySelectOption);
$smarty->assign("option_output_provee", $arraySelectoutPut);

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

$arraySelectOption = "";
$arraySelectoutPut = "";
$producto = new Producto();
$campos = $producto->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion");
foreach ($campos as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectOutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_producto", $arraySelectOption);
$smarty->assign("option_output_producto", $arraySelectOutPut);

$arraySelectOption = "";
$arraySelectoutPut = "";
$producto = new Producto();
$campos = $producto->ObtenerFilasBySqlSelect("SELECT * FROM tipo_toma");
foreach ($campos as $key => $item) {
    $arraySelectOption2[] = $item["id"];
    $arraySelectOutPut2[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo", $arraySelectOption2);
$smarty->assign("option_output_tipo", $arraySelectOutPut2);

//FILTRO PARA EL CODIGO SIGA
//$bdpp = DB_SELECTRA_PYMEPP;
$bdpp=DB_REPORTE_CENTRAL;
$filtro_siga_id="";
$siga=new Almacen();
$cod_sig=$siga->ObtenerFilasBySqlSelect("select distinct siga from $bdpp.vproducto");
foreach ($cod_sig as $key => $item) {
    $arraycod_sigainput[] = $item["siga"];
    
}
$smarty->assign("option_values_siga", $arraycod_sigainput);
//FIN DEL FILTRO PARA EL CODIGO SIGA


// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";
$cliente = new Clientes();

$punto = $cliente->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from $bdCentral.puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectOutPut1);

//estados
$arraySelectOption2 = "";
$arraySelectoutPut2 = "";
$cliente = new Clientes();
$campos = $cliente->ObtenerFilasBySqlSelect("SELECT * FROM $bdCentral.estados");
foreach ($campos as $key => $item) {
    $arraySelectOption2[] = $item["codigo_estado"];
    $arraySelectOutPut2[] = $item["nombre_estado"];
}
$smarty->assign("option_values_estado", $arraySelectOption2);
$smarty->assign("option_output_estado", $arraySelectOutPut2);

//categoria
$arraySelectOption3 = "";
$arraySelectoutPut3 = "";
$cliente = new Clientes();
$campos3 = $cliente->ObtenerFilasBySqlSelect("SELECT grupopos,TRIM(descripcion) as descripcion FROM $bdCentral.grupo2 order by descripcion");
foreach ($campos3 as $key => $item) {
    $arraySelectOption3[] = $item["grupopos"];
    $arraySelectOutPut3[] = $item["descripcion"];
}
$smarty->assign("option_values_categoria", $arraySelectOption3);
$smarty->assign("option_output_categoria", $arraySelectOutPut3);

//producto
$arraySelectOption4 = "";
$arraySelectoutPut4 = "";
$cliente = new Clientes();
$campos3 = $cliente->ObtenerFilasBySqlSelect("SELECT TRIM(descripcion1) as descripcion1,itempos  FROM $bdCentral.productos order by descripcion1");
foreach ($campos3 as $key => $item) {
    $arraySelectOption4[] = $item["itempos"];
    $arraySelectOutPut4[] = $item["descripcion1"];
}
$smarty->assign("option_values_productos", $arraySelectOption4);
$smarty->assign("option_output_productos", $arraySelectOutPut4);

$fecha = new DateTime();
$fecha->modify('first day of this month');
$smarty->assign("firstday", $fecha->format('Y-m-d'));
$fecha->modify('last day of this month');
$smarty->assign("lastday", $fecha->format('Y-m-d'));

if (isset($_GET['editar'])){

    $aceptar=$_POST['aceptar'];
    $almacen=$_POST['almacen_entrada'];
    $ubicacion=$_POST['ubicacion'];
    $tipo_toma=$_POST['tipo_toma'];
    $fecha_apertura=$_POST['fecha_apertura'];


    if(isset($_GET['editar'])){

    $obtener_edicion=$producto->ObtenerFilasBySqlSelect("select date(a.fecha_apertura) as fecha, a.tipo_toma, id_ubicacion, a.id_almacen, b.descripcion as ubicacion, c.descripcion as almacen, d.descripcion as toma from tomas_fisicas as a, ubicacion as b, almacen as c, tipo_toma as d where  a.id=".$_GET['cod']."
        and a.id_ubicacion=b.id
        and a.id_almacen=c.cod_almacen
        and a.tipo_toma=d.id"
        );


    $aceptar='Mostrar';
    $almacen=$obtener_edicion[0]['id_almacen'];
    $ubicacion=$obtener_edicion[0]['id_ubicacion'];
    $tipo_toma=$obtener_edicion[0]['tipo_toma'];
    $fecha_apertura=$obtener_edicion[0]['fecha'];
    $nombre_ubicacion=$obtener_edicion[0]['ubicacion'];
    $nombre_toma=$obtener_edicion[0]['toma'];
    $nombre_almacen=$obtener_edicion[0]['almacen'];


    }

    $update=isset($_POST['update']) ? $_POST['update'] : '';
    $id=$login->getIdUsuario();
    
    if(!isset($_GET['editar'])){
    $cabecera_toma=$producto->Execute2("INSERT INTO `tomas_fisicas`(`fecha_apertura`, `tipo_toma`, `id_almacen`, `id_ubicacion`, `id_usuario`) VALUES ('".$fecha_apertura."', ".$tipo_toma.", ".$almacen.", ".$ubicacion.", ".$id.")");
    $obtenerid=$producto->ObtenerFilasBySqlSelect("SELECT LAST_INSERT_ID() as id");
    
    $id_mov=$obtenerid[0]['id']; 
    //filtro para saber si es piso de venta, de ser piso de venta debe consultarse es la vista


    $nombre_ubi_arr=$producto->ObtenerFilasBySqlSelect("SELECT descripcion from ubicacion where id=".$ubicacion."");
    $nombre_ubi=$nombre_ubi_arr[0]['descripcion'];

    if($nombre_ubi=='PISO DE VENTA')
    {
        $tabla_ubicacion="vw_item_pisoventa";
    }
    else
    {
     $tabla_ubicacion="item_existencia_almacen";   
    }
    //si es toma rapida insertar todos los productos que tenga contenido y codigo de barras en la tomadetalle
    //este cambio es para cargar todos los productos en la ubicacion seleccionada en tipo completo, mas visualmente no se mostrarán en el template.
    }
    else
    {
        $id_mov=$_GET['cod'];
    }

     $query=$producto->ObtenerFilasBySqlSelect("SELECT a.id, a.id_mov, a.cod_bar, a.inv_sistema, a.toma1, a.toma2, a.tomadef, IF(a.mov_sugerido>0,concat('+',a.mov_sugerido), a.mov_sugerido) as mov_sugerido,b.descripcion1 as nombre_producto, c.toma from tomas_fisicas_detalle a, item b, tomas_fisicas as c WHERE c.id=a.id_mov and a.cod_bar=b.codigo_barras and a.id_llenado=1 and id_mov=".$id_mov."");   
    $i=0;
    $resultado=$producto->getFilas($query);
    $smarty->assign("resultado", $resultado);

    while($i<$resultado){
    $datos[$i]=$query[$i];
    $i++;
    }
    $smarty->assign("resultado", $resultado);
    $smarty->assign("consulta",$datos);

    $query=$producto->ObtenerFilasBySqlSelect($query);
    $i=0;
    $resultado=$producto->getFilas($query);
    $smarty->assign("sql", $sql);
    $smarty->assign("aceptar", $aceptar);
    $smarty->assign("ubicacion", $ubicacion);
    $smarty->assign("almacen_entrada", $almacen);
    $smarty->assign("nombre_almacen", $nombre_almacen);
    $smarty->assign("nombre_ubicacion", $nombre_ubicacion);
    $smarty->assign("nombre_toma", $nombre_toma);
    $smarty->assign("tipo_toma", $tipo_toma);
    $smarty->assign("fecha_apertura", $fecha_apertura);
    $smarty->assign("id_mov", $id_mov);
}
$nulltoma1="";
$nulltoma2="";
$nulltoma3="";


$smarty->assign("numero_toma", $datos[0]['toma']);
$smarty->assign("nulltoma1", $nulltoma1);
$smarty->assign("nulltoma2", $nulltoma2);
$smarty->assign("nulltoma3", $nulltoma3);


if ($_POST['aceptar']) {

$sql="SELECT id_ubicacion, id_almacen FROM tomas_fisicas WHERE id=".$_GET['cod']."";
$datos=$producto->ObtenerFilasBySqlSelect($sql);

$sql="SELECT id_ubicacion, id_almacen FROM tomas_fisicas WHERE id=".$_GET['cod']."";
$datos=$producto->ObtenerFilasBySqlSelect($sql);

$ubicacion=$datos[0]['id_ubicacion'];
$almacen=$datos[0]['id_almacen'];
$sql="SELECT descripcion FROM ubicacion WHERE id=".$ubicacion."";
$ubicacion_pro=$producto->ObtenerFilasBySqlSelect($sql);

//Procedimiento de los productos en donde los movimientos sean positivos (entradas)
$sql="SELECT a.id_item, b.tomadef, b.mov_sugerido, a.precio1, a.codigo_barras, a.descripcion1 FROM item a, tomas_fisicas_detalle b WHERE a.codigo_barras=b.cod_bar and b.id_mov=".$_GET['cod']." and b.mov_sugerido>=0";
$productos=$producto->ObtenerFilasBySqlSelect($sql);

$sql="SELECT id FROM ubicacion WHERE descripcion='AJUSTE POR ANALIZAR'";
$ubicacion_entrada=$producto->ObtenerFilasBySqlSelect($sql);

//Inserto la cabecera del Kardex para tener el id de la transaccion
$producto->BeginTrans();
$fecha=date('Y-m-d');
$sql="INSERT INTO kardex_almacen
    (tipo_movimiento_almacen, autorizado_por, observacion, fecha,  usuario_creacion, fecha_creacion, estado, fecha_ejecucion) 
    VALUES
    (9, 'SISTEMA', 'AJUSTE AUTOMATICO', '".$fecha."', '".$login->getUsuario()."', CURRENT_TIMESTAMP, 'Ejecutado', CURRENT_TIMESTAMP)";
$producto->ExecuteTrans($sql);
$id_transaccion = $producto->getInsertID();

//PROCESO DE AGREGAR EL CODIGO DE SEGURIDAD DEL KARDEX
    if($_POST['codigo_kardex']!=NULL)
    {
        $producto->ExecuteTrans("INSERT INTO `codigo_kardex`( `codigo`, `id_movimiento`, `tipo_moviento`, `usuario`, `fecha`) VALUES (".$_POST['codigo_kardex'].",".$id_transaccion.",'9','".$login->getUsuario()."', now());");
    }

foreach ($productos as $key => $item) {

    //Para las ubicaciones que sean distintas a PISO DE VENTA hago las operaciones en item_existencia_almacen
    if ($ubicacion_pro[0]['descripcion']!='PISO DE VENTA') {
        //Consulto si el producto existe en item existencia almancen, para hacer insert o update dependiendo sea el caso
        $sql="SELECT id_item FROM item_existencia_almacen WHERE id_item=".$item['id_item']." AND id_ubicacion=".$ubicacion." AND cod_almacen=".$almacen."";
        $filas_existencia=$producto->ObtenerFilasBySqlSelect($sql);

        if(count($filas_existencia)>0)
        {
        $sql="UPDATE item_existencia_almacen SET cantidad=".$item['tomadef']." WHERE 
        id_item=".$item['id_item']."
        AND id_ubicacion=".$ubicacion."
        AND cod_almacen=".$almacen."";
        $producto->ExecuteTrans($sql);
        }elseif(count($filas_existencia)==0){
        $sql="INSERT INTO item_existencia_almacen(cod_almacen, id_item, cantidad, id_ubicacion) 
        VALUES (".$almacen.", ".$item['id_item'].", ".$item['tomadef'].", ".$ubicacion.")";
        $producto->ExecuteTrans($sql);
        }

    }elseif($ubicacion_pro[0]['descripcion']=='PISO DE VENTA'){
        //Valido que el producto se encuentre creado en el POS de lo contrario se detiene el proceso para que el usuario corra el catalogo de sede 
        $sql="SELECT CODE, ID FROM $pos.products WHERE CODE='".$item['codigo_barras']."'";
        $creado_pos=$producto->ObtenerFilasBySqlSelect($sql);
        if(count($creado_pos)==0)
        {
            echo '<script language="javascript">alert("Error:El Producto: '.$item["codigo_barras"].' - , '.$item["descripcion1"].' no se encuentra registrado en el catalogo de productos POS, realizar la sincronizacion del Catalogo de Productos de Sede Central y volver a realizar esta operacion");window.history.go(-2);</script>';
            exit();
        }elseif (count($creado_pos)>0) {
        //Si el producto esta creado en el POS valido que si existe en stockcurrent, para hacer insert o update dependiendo sea el caso
        $sql="SELECT PRODUCT FROM $pos.stockcurrent WHERE CODE='".$creado_pos[0]['ID']."'";
        $filas_pos=$producto->ObtenerFilasBySqlSelect($sql);
            if(count($filas_pos)>0)
            {
            $sql="UPDATE $pos.stockcurrent SET UNITS=".$item['tomadef']." WHERE PRODUCT ='".$creado_pos[0]['ID']."'";

            $producto->ExecuteTrans($sql);
            }elseif(count($filas_pos)==0){
            $sql="INSERT INTO $pos.stockcurrent(PRODUCT, UNITS) 
            VALUES ('".$creado_pos[0]['ID']."', ".$item['tomadef'].")";
            $producto->ExecuteTrans($sql);
            }
        }
    }
    if ($item['mov_sugerido']<0) {
       $cantidad=$item['mov_sugerido']*(-1);

       $sql="INSERT INTO kardex_almacen_detalle 
       (id_transaccion, id_almacen_entrada, id_almacen_salida, id_item, cantidad, id_ubi_entrada, precio) 
       VALUES 
       (".$id_transaccion.",".$almacen.",".$almacen.",".$item['id_item'].",".$cantidad.",".$ubicacion_entrada[0]['id'].",".$item['precio1'].")";
       $producto->ExecuteTrans($sql);
       
        }elseif ($item['mov_sugerido']>0) {
        $sql="INSERT INTO kardex_almacen_detalle 
       (id_transaccion, id_almacen_entrada, id_almacen_salida, id_item, cantidad, id_ubi_entrada, precio) 
       VALUES 
       (".$id_transaccion.",".$almacen.",".$almacen.",".$item['id_item'].",".$item['mov_sugerido'].",".$ubicacion_entrada[0]['id'].",".$item['precio1'].")";
       $producto->ExecuteTrans($sql);
        }

    
    }


//Procedimiento de los productos en donde los movimientos sean negativos (traslados)
$sql="SELECT a.id_item, b.tomadef, b.mov_sugerido, a.precio1, a.codigo_barras, a.descripcion1 FROM item a, tomas_fisicas_detalle b WHERE a.codigo_barras=b.cod_bar and b.id_mov=".$_GET['cod']." and b.mov_sugerido<0";
$productos=$producto->ObtenerFilasBySqlSelect($sql);
$sql="INSERT INTO kardex_almacen
    (tipo_movimiento_almacen, autorizado_por, observacion, fecha,  usuario_creacion, fecha_creacion, estado, fecha_ejecucion) 
    VALUES
    (10, 'SISTEMA', 'AJUSTE AUTOMATICO', '".$fecha."', '".$login->getUsuario()."', CURRENT_TIMESTAMP, 'Ejecutado', CURRENT_TIMESTAMP)";
$producto->ExecuteTrans($sql);
$id_transaccion = $producto->getInsertID();

foreach ($productos as $key => $item) {

    //Para las ubicaciones que sean distintas a PISO DE VENTA hago las operaciones en item_existencia_almacen
    if ($ubicacion_pro[0]['descripcion']!='PISO DE VENTA') {
        //Consulto si el producto existe en item existencia almancen, para hacer insert o update dependiendo sea el caso
        $sql="SELECT id_item FROM WHERE id_item=".$item['id_item']." AND id_ubicacion=".$ubicacion." AND cod_almacen=".$almacen."";
        $filas_existencia=$producto->ObtenerFilasBySqlSelect($sql);

        if(count($filas_existencia)>0)
        {
        $sql="UPDATE item_existencia_almacen SET cantidad=".$item['tomadef']." WHERE 
        id_item=".$item['id_item']."
        AND id_ubicacion=".$ubicacion."
        AND cod_almacen=".$almacen."";
        $producto->ExecuteTrans($sql);
        }elseif(count($filas_existencia)==0){
        $sql="INSERT INTO item_existencia_almacen(cod_almacen, id_item, cantidad, id_ubicacion) 
        VALUES (".$almacen.", ".$item['id_item'].", ".$item['tomadef'].", ".$ubicacion.")";
        $producto->ExecuteTrans($sql);
        }

    }elseif($ubicacion_pro[0]['descripcion']=='PISO DE VENTA'){
        //Valido que el producto se encuentre creado en el POS de lo contrario se detiene el proceso para que el usuario corra el catalogo de sede 
        $sql="SELECT CODE, ID FROM $pos.products WHERE CODE='".$item['codigo_barras']."'";
        $creado_pos=$producto->ObtenerFilasBySqlSelect($sql);
        if(count($creado_pos)==0)
        {
            echo '<script language="javascript">alert("Error:El Producto: '.$item["codigo_barras"].' - '.$item["descripcion1"].' no se encuentra registrado en el catalogo de productos POS, realizar la sincronizacion del Catalogo de Productos de Sede Central y volver a realizar esta operacion");window.history.go(-2);</script>';
            exit();
        }elseif (count($creado_pos)>0) {
        //Si el producto esta creado en el POS valido que si existe en stockcurrent, para hacer insert o update dependiendo sea el caso
        $sql="SELECT PRODUCT FROM $pos.stockcurrent WHERE CODE='".$creado_pos[0]['ID']."'";
        $filas_pos=$producto->ObtenerFilasBySqlSelect($sql);
            if(count($filas_pos)>0)
            {
            $sql="UPDATE $pos.stockcurrent SET PRODUCT='".$creado_pos[0]['ID']."',UNITS=".$item['tomadef']." WHERE PRODUCT ='".$creado_pos[0]['ID']."'";
            $producto->ExecuteTrans($sql);
            }elseif(count($filas_pos)==0){
            $sql="INSERT INTO $pos.stockcurrent(PRODUCT, UNITS) 
            VALUES ('".$creado_pos[0]['ID']."', ".$item['tomadef'].")";
            $producto->ExecuteTrans($sql);
            }
        }
    }
    
    if ($item['mov_sugerido']<0) {
       $cantidad=$item['mov_sugerido']*(-1);

       $sql="INSERT INTO kardex_almacen_detalle 
       (id_transaccion, id_almacen_entrada, id_almacen_salida, id_item, cantidad, id_ubi_entrada, id_ubi_salida, precio) 
       VALUES 
       (".$id_transaccion.",".$almacen.",".$almacen.",".$item['id_item'].",".$cantidad.",".$ubicacion_entrada[0]['id'].", ".$ubicacion.", ".$item['precio1'].")";
       $producto->ExecuteTrans($sql);
       
        }elseif ($item['mov_sugerido']>0) {
        $sql="INSERT INTO kardex_almacen_detalle 
       (id_transaccion, id_almacen_entrada, id_almacen_salida, id_item, cantidad, id_ubi_entrada, id_ubi_salida, precio) 
       VALUES 
       (".$id_transaccion.",".$almacen.",".$almacen.",".$item['id_item'].",".$item['mov_sugerido'].",".$ubicacion_entrada[0]['id'].",".$ubicacion.",".$item['precio1'].")";
       $producto->ExecuteTrans($sql);
        }
    }

    //Actualizo los productos que se encuentran en existencia en negativo para dejar en 0
    $sql="UPDATE item_existencia_almacen SET cantidad=0 WHERE cantidad<0";
    $producto->ExecuteTrans($sql);
    $sql="UPDATE $pos.stockcurrent SET UNITS=0 WHERE UNITS<0";
    $producto->ExecuteTrans($sql);
    //Cambio el estatus de la toma a ejecutada
    $sql="UPDATE tomas_fisicas SET estatus_toma=1 WHERE id=".$_GET['cod']."";
    $producto->ExecuteTrans($sql);

    if ($producto->errorTransaccion == 1)
    {    
        //echo "paso";   
        echo '<script language="javascript">alert("Ajuste de Inventario Registrado");window.history.go(-2);</script>'; 
    }
    elseif($producto->errorTransaccion == 0)
    {
        //echo "no paso";
        echo '<script language="javascript">alert("Error:Ajuste de Inventario No Registrado");window.history.go(-2);</script>';
    }
    $producto->CommitTrans($producto->errorTransaccion);
}


?>
