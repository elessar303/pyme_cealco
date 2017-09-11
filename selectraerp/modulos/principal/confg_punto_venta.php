<?php
include("../../libs/php/clases/almacen.php");
include("../../../menu_sistemas/lib/common.php");
$anioActual = date("Y");
for ($i=$anioActual; $i >= ($anioActual-5);$i--) {
    $arrayanioN[] = $i;
    $arrayanio[] = $i;
}

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

$smarty->assign("option_values_nombre_anio", $arrayanioN);
$smarty->assign("option_values_id_anio", $arrayanio);

$ubicacion=new Almacen();
$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from selectrapyme_central.estados");

foreach ($ubica as $key => $item) {
    $arrayubiN[] = $item["nombre_estado"];
    $arrayubi[] = $item["codigo_estado"];
    
}
$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

$ubica=$ubicacion->ObtenerFilasBySqlSelect("select * from selectrapyme_central.puntos_tipo");

foreach ($ubica as $key => $item) {
    $arrayubiNi[] = $item["descripcion_tipo"];
    $arrayubii[] = $item["id_tipo"];
    
}
$smarty->assign("option_values_descripcion_tipo", $arrayubiNi);
$smarty->assign("option_values_id_tipo", $arrayubii);


// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";
$cliente = new Almacen();
mysql_set_charset('utf8');
$punto = $cliente->ObtenerFilasBySqlSelect("SELECT nombre_punto,codigo_siga_punto as siga  from selectrapyme_central.puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectOutPut1);

if (isset($_POST['cancelar'])){
    $vacio='';
    $smarty->assign("nombrepunto",$vacio);
    $smarty->assign("puntodeventa",$vacio);
    $smarty->assign("estado",$vacio);
}

if (isset($_POST['aceptar'])){
    $aceptar=$_POST['aceptar'];
    $valor='prueba';
    $nombrepunto=$_POST['nombrepunto'];
    $puntodeventa=$_POST['punto'];
    $estado=$_POST['estados'];
    $tipo_punto=$_POST['tipo_punto'];
    $opcion_menu=$_GET['opt_menu'];
    $smarty->assign("nombrepunto",$nombrepunto);
    $smarty->assign("puntodeventa",$puntodeventa);
    $smarty->assign("estado",$estados);
    $smarty->assign("opcion_menu",$opcion_menu);

    $search="";
    if($estado!='00')
        {
        $search=$search.' AND codigo_estado="'.$estado.'"';
        }
    if($puntodeventa!='0')
        {
        $search=$search.'AND codigo_siga_punto="'.$puntodeventa.'"';
        }
    
 if($nombrepunto!='')
        {
        $search=$search.'AND nombre_punto like "%'.$nombrepunto.'%"';
        }
if($tipo_punto!='')
        {
        $search=$search.'AND c.id_tipo ='.$tipo_punto.'';
        }
    
    $sql="SELECT a.id, codigo_siga_punto, nombre_punto, codigo_estado_punto, codigo_estado, nombre_estado, tipo_sincro, estatus, ip_punto_venta, descripcion_tipo
        FROM selectrapyme_central.puntos_venta a, selectrapyme_central.estados b, selectrapyme_central.puntos_tipo c
        WHERE codigo_estado_punto=codigo_estado AND a.id_tipo=c.id_tipo
        ".$search." order by nombre_estado, estatus";

//echo $sql; exit();

    $smarty->assign("sql", $sql);    
    $query=$ubicacion->ObtenerFilasBySqlSelect($sql);
    $i=0;
    $resultado=$ubicacion->getFilas($query);
    while($i<$resultado){
    $datos[$i]=$query[$i]; //se guardan los datos en un arreglo
    $i++;
    }
    
    $smarty->assign("consulta",$datos);
 
    $smarty->assign("query", $query);
    $smarty->assign("aceptar", $aceptar);
    $smarty->assign("cedula", $cedula);
    
} else {
    $instruccion = "SELECT * FROM clientes ";
}
                
