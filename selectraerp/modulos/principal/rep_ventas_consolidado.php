<?php
include("../../libs/php/clases/almacen.php");
$anioActual = date("Y");
for ($i=$anioActual; $i >= ($anioActual-5);$i--) {
    $arrayanioN[] = $i;
    $arrayanio[] = $i;
}

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

if (isset($_POST['cancelar'])){
    $vacio='';
    $smarty->assign("producto",$vacio);
    $smarty->assign("categoria",$vacio);
    $smarty->assign("puntodeventa",$vacio);
    $smarty->assign("estado",$vacio);
}

if (isset($_POST['aceptar'])){
    $aceptar=$_POST['aceptar'];
    $valor='prueba';
    $estado=$_POST['estado'];
    $producto=$_POST['producto'];
    $fecha1=$_POST['fecha1'];
    $fecha2=$_POST['fecha2'];

    $mes_des=substr($fecha1,5,2);
    $anno_des=substr($fecha1,2,2);
    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;
    $cedula=$_POST['cedula'];
    $nac=$_POST['nac'];
    
    $cedula_nac=$nac.$cedula;
    $categoria=$_POST['categoria'];
    $puntodeventa=$_POST['puntodeventa'];
    $smarty->assign("producto", $producto);
    $smarty->assign("fecha1", $fecha1);
    $smarty->assign("fecha2", $fecha2);
    $smarty->assign("categoria", $categoria);
    $smarty->assign("puntodeventa", $puntodeventa);
    $smarty->assign("cedula_nac", $cedula_nac);
    $smarty->assign("estado", $estado);
    $smarty->assign("nac", $nac);
    $smarty->assign("cedula", $cedula);

    
    $fecha1_format = str_replace("-","-",$fecha1);
    $fecha2_format = str_replace("-","-",$fecha2);
    $fecha_complete1='00:00:00';
    $fecha_complete2='23:00:00';
    $search="";
    if($estado!='00')
        {
        $search=$search." AND codigo_estado='".$estado."'";
        }
    if($puntodeventa!='')
        {
        $search=$search." AND codigo_siga_punto='".$puntodeventa."'";
        }
    
 if($producto!='')
        {
        $search=$search." AND nombre_producto like '%".$producto."%'";
        }
 if($cedula_nac!='')
        {
        $search=$search." AND taxid like '%".$cedula_nac."%'";
        }
if($categoria!='')
        {
        $search=$search." AND descripcion like '%".$categoria."%'";
        }
if($restringido!='')
        {
        $search=$search." AND grupo.restringido =".$restringido."";
        }        
if($limite!='')
        {
        $search=$search." AND units >".$limite."";
        }
    
    $sql="SELECT nombre_producto, price, taxid, name_persona, codigo_siga, units, datenew_ticketlines, codigo_siga_punto, nombre_punto, codigo_estado_punto, codigo_estado, nombre_estado, descripcion  
    FROM selectrapyme_central.$ventas_mes_anno left join selectrapyme_central.grupo on grupo.grupopos=ventas.category, selectrapyme_central.puntos_venta, selectrapyme_central.estados
    WHERE codigo_estado_punto=codigo_estado
    AND codigo_siga_punto=codigo_siga
    AND datenew_ticketlines BETWEEN ('".$fecha1_format." ".$fecha_complete1."') AND ('".$fecha2_format." ".$fecha_complete2."')
    ".$search." order by nombre_estado";

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
} else {
    $instruccion = "SELECT * FROM clientes ";
}
                
