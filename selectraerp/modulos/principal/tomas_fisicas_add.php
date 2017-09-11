<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
require_once("../../libs/php/clases/login.php");
$bdCentral= "selectrapyme_central";
$almacen = new Almacen();
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

if (isset($_POST['aceptar']) || isset($_GET['editar'])){

    $aceptar=$_POST['aceptar'];
    $almacen=$_POST['almacen_entrada'];
    $ubicacion=$_POST['ubicacion'];
    $tipo_toma=$_POST['tipo_toma'];
    $fecha_apertura=$_POST['fecha_apertura'];
//validamos que no exista uno pendiente
    if(isset($_POST['aceptar'])){
    $buscar=$producto->ObtenerFilasBySqlSelect("select a.id from tomas_fisicas as a, tomas_fisicas_detalle as b where a.id=b.id_mov and id_almacen=".$almacen." and id_ubicacion=".$ubicacion." and (toma1 is null or toma2 is null or tomadef is null)");
        $verificar=$producto->getFilas($buscar);
        if($verificar!=0){
            echo "<script type='text/javascript'>
             alert('Tiene una solicitud pendiente, numero solicitud :".$buscar[0]['id']."');
             history.back(1); 
             </script>
        ";
        exit();
            
        }

        }


    if(isset($_GET['editar'])){

    $obtener_edicion=$producto->ObtenerFilasBySqlSelect("select date(a.fecha_apertura) as fecha, a.tipo_toma, id_ubicacion, id_almacen from tomas_fisicas as a where  a.id=".$_GET['cod']);


    $aceptar='Mostrar';
    $almacen=$obtener_edicion[0]['id_almacen'];
    $ubicacion=$obtener_edicion[0]['id_ubicacion'];
    $tipo_toma=$obtener_edicion[0]['tipo_toma'];
    $fecha_apertura=$obtener_edicion[0]['fecha'];

    }

    if($almacen=="" || $ubicacion=="" || $tipo_toma=="" || $fecha_apertura==""){
        echo "<script type='text/javascript'>
             alert('Error Faltan Datos, Para Procesar La Solicitud');
             history.back(1); 
             </script>
        ";
        exit();
    }

    $update=isset($_POST['update']) ? $_POST['update'] : '';
    $id=$login->getIdUsuario();
    
    if(!isset($_GET['editar'])){
    $cabecera_toma=$producto->Execute2("INSERT INTO `tomas_fisicas`(`fecha_apertura`, `tipo_toma`, `id_almacen`, `id_ubicacion`, `id_usuario`) VALUES ('".$fecha_apertura."', ".$tipo_toma.", ".$almacen.", ".$ubicacion.", ".$id.")");
    $obtenerid=$producto->ObtenerFilasBySqlSelect("SELECT LAST_INSERT_ID() as id");
    
    $id_mov=$obtenerid[0]['id']; 
    $sql="SELECT descripcion FROM ubicacion WHERE id=".$ubicacion."";
    $ubicacion_pro=$producto->ObtenerFilasBySqlSelect($sql);
    //filtro para saber si es piso de venta, de ser piso de venta debe consultarse es la vista
    if($ubicacion_pro[0]['descripcion']=='PISO DE VENTA')
    {
        $tabla_ubicacion="vw_item_pisoventa";
    }
    else
    {
     $tabla_ubicacion="item_existencia_almacen";   
    }
    //si es toma rapida insertar todos los productos que tenga contenido y codigo de barras en la tomadetalle
    if($tipo_toma==1 )
    {
        $insert=$producto->Execute2("insert into tomas_fisicas_detalle (id_mov, cod_bar,inv_sistema, toma1, id_llenado)(SELECT ".$id_mov.", b.codigo_barras, a.cantidad, a.cantidad, 1 FROM ".$tabla_ubicacion." as a, item as b where a.id_item=b.id_item and a.cod_almacen=".$almacen." and id_ubicacion=".$ubicacion." and a.cantidad<>0)");
        //actualizamos la toma a 2
        $actualizar=$producto->Execute2("update tomas_fisicas set toma=2 where id=".$id_mov);
    }
    //este cambio es para cargar todos los productos en la ubicacion seleccionada en tipo completo, mas visualmente no se mostrarán en el template.
    if($tipo_toma==2)
    {
        $insert=$producto->Execute2("insert into tomas_fisicas_detalle (id_mov, cod_bar,inv_sistema)(SELECT ".$id_mov.", b.codigo_barras, a.cantidad FROM ".$tabla_ubicacion." as a, item as b where a.id_item=b.id_item and a.cod_almacen=".$almacen." and id_ubicacion=".$ubicacion." and a.cantidad<>0)");
        
    }
    }
    else
    {
        $id_mov=$_GET['cod'];
    }
    if($tipo_toma==1 && isset($_GET['editar']))
    {
        $query=$producto->ObtenerFilasBySqlSelect("SELECT a.id, a.id_mov, a.cod_bar, a.inv_sistema, a.toma1, a.toma2, a.tomadef, IF(a.mov_sugerido>0,concat('+',a.mov_sugerido), a.mov_sugerido) as mov_sugerido,b.descripcion1 as nombre_producto, c.toma from tomas_fisicas_detalle a, item b, tomas_fisicas as c WHERE c.id=a.id_mov and a.cod_bar=b.codigo_barras and id_mov=".$id_mov."");
    }
    else //mostrar solo los que ingrese el usuario
    {
     $query=$producto->ObtenerFilasBySqlSelect("SELECT a.id, a.id_mov, a.cod_bar, a.inv_sistema, a.toma1, a.toma2, a.tomadef, IF(a.mov_sugerido>0,concat('+',a.mov_sugerido), a.mov_sugerido) as mov_sugerido,b.descripcion1 as nombre_producto, c.toma from tomas_fisicas_detalle a, item b, tomas_fisicas as c WHERE c.id=a.id_mov and a.cod_bar=b.codigo_barras and a.id_llenado=1 and id_mov=".$id_mov."");   
    }
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
    $smarty->assign("tipo_toma", $tipo_toma);
    $smarty->assign("fecha_apertura", $fecha_apertura);
    $smarty->assign("id_mov", $id_mov);
    }
$nulltoma1="";
$nulltoma2="";
$nulltoma3="";
if((isset($_GET['editar']) || isset($_POST['aceptar'])) || $tipo_toma==1){

$sql="SELECT * FROM tomas_fisicas_detalle WHERE toma1 is NULL and id_mov=$id_mov";
$query=$producto->ObtenerFilasBySqlSelect($sql);
$nulltoma1=$producto->getFilas($query);


if($nulltoma1==0){
    $sql="SELECT * FROM tomas_fisicas_detalle WHERE toma2 is NULL and id_mov=$id_mov";
    $query=$producto->ObtenerFilasBySqlSelect($sql);
    $nulltoma2=$producto->getFilas($query);
       if($nulltoma2==0){
        $sql="SELECT * FROM tomas_fisicas_detalle WHERE tomadef is NULL and id_mov=$id_mov";
        $query=$producto->ObtenerFilasBySqlSelect($sql);
        $nulltoma3=$producto->getFilas($query);
    }
    }

}

$smarty->assign("numero_toma", $datos[0]['toma']);
$smarty->assign("nulltoma1", $nulltoma1);
$smarty->assign("nulltoma2", $nulltoma2);
$smarty->assign("nulltoma3", $nulltoma3);

if (isset($_POST['toma1_submit'])){

        $i=0;
        $cantidad_items=$_POST['cantidad_items'];
        $items=$_POST['codigo_barras'];
        $toma1=$_POST['toma1'];
        while($i<$cantidad_items){
        $sql="UPDATE tomas_fisicas_detalle SET toma1=".$toma1[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$producto->Execute2($sql);
        $i++;
        }
    
    
}

if (isset($_POST['toma2_submit'])){

        $i=0;
        $cantidad_items=$_POST['cantidad_items'];
        $items=$_POST['codigo_barras'];
        $toma2=$_POST['toma2'];
        $toma1_igual=$conn->ObtenerFilasBySqlSelect("select toma1 from tomas_fisicas_detalle where id_mov=".$_POST['id_mov']."");
        $bandera=0;
        while($i<$cantidad_items){

        $sql="UPDATE tomas_fisicas_detalle SET toma2=".$toma2[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$producto->Execute2($sql);
        if($toma1_igual[$i]['toma1']!=$toma2[$i])
            $bandera=1;
        $i++;
        }
        //si toma1 y toma2 son iguales se completa toma definitiva
        $i=0;
        if($bandera==0){
        while($i<$cantidad_items){

        $sql="UPDATE tomas_fisicas_detalle SET tomdef=".$toma2[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$producto->Execute2($sql);
        }//fin while
    }//fin del if bandera
    
}

if (isset($_POST['toma3'])){

        $i=0;
        $cantidad_items=$_POST['cantidad_items'];
        $items=$_POST['codigo_barras'];
        $toma2=$_POST['toma3'];
        while($i<$cantidad_items){
        $sql="UPDATE tomas_fisicas_detalle SET tomadef=".$toma3[$i]." WHERE cod_bar='".$items[$i]."' and id_mov=".$_POST['id_mov']."";
        $detalle_toma=$producto->Execute2($sql);
        $i++;
        }
    
}
?>
