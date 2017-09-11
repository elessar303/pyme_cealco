<?php
include("../../../menu_sistemas/lib/common.php");
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

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);


$smarty->assign("option_values_nombre_estado", $arrayubiN);
$smarty->assign("option_values_id_estado", $arrayubi);

// punto de ventas
$arraySelectOption = "";
$arraySelectoutPut1 = "";
$cliente = new Almacen();
mysql_set_charset('utf8');
$punto = $cliente->ObtenerFilasBySqlSelect("SELECT `nombre_punto`,codigo_siga_punto as siga  from selectrapyme_central.puntos_venta where estatus='A'");
foreach ($punto as $key => $puntos) {
    $arraySelectOption[] = $puntos["siga"];
    $arraySelectOutPut1[] = $puntos["nombre_punto"];
}

$smarty->assign("option_values_punto", $arraySelectOption);
$smarty->assign("option_output_punto", $arraySelectOutPut1);

if (isset($_POST['aceptar'])){

    $aceptar=$_POST['aceptar'];
    $estado=$_POST['estado'];
    $fecha1=$_POST['fecha1'];
    $fecha2=$_POST['fecha2'];
    $reporto=$_POST['reporto'];
    $sincro=$_POST['sincro'];
    $puntodeventa=$_POST['puntodeventa'];
    $fecha1_format = str_replace("-","-",$fecha1);
    $fecha2_format = str_replace("-","-",$fecha2);
    $fecha_complete1='00:00:00';
    $fecha_complete2='23:00:00';


    $smarty->assign("fecha1", $fecha1);
    $smarty->assign("fecha2", $fecha2);
    $smarty->assign("puntodeventa", $puntodeventa);
    $smarty->assign("estado", $estado);
    $smarty->assign("reporto", $reporto);
    $smarty->assign("sincro", $sincro);


if($estado!='9999')
        {
        $search1='AND C.codigo_estado="'.$estado.'"';
 		}

if($puntodeventa!='0')
        {
        $search2='AND B.codigo_siga_punto="'.$puntodeventa.'"';
    	}

if($sincro!='00')
        {
        $search3='AND B.tipo_sincro="'.$sincro.'"';
 		}

 		//asignando tablas dinamicas


 		//if()


$total_true=0;

 //--AND B.estatus='A'

/*$sql="SELECT C.nombre_estado,A.codigo_siga,B.nombre_punto, B.tipo_sincro, A.fecha, A.fecha as color, reporto
FROM selectrapyme_central.reportes_ventas A, selectrapyme_central.puntos_venta B, selectrapyme_central.estados C
WHERE A.codigo_siga=B.codigo_siga_punto
AND B.codigo_estado_punto=C.codigo_estado
AND B.estatus='A'*/
$sql="SELECT C.nombre_estado,A.codigo_siga,B.nombre_punto, B.tipo_sincro, A.fecha, A.fecha as color, A.reporto, rvh.fecha
FROM selectrapyme_central.reportes_ventas A
INNER JOIN selectrapyme_central.puntos_venta B on A.codigo_siga=B.codigo_siga_punto
INNER JOIN selectrapyme_central.estados C on B.codigo_estado_punto=C.codigo_estado
INNER JOIN selectrapyme_central.reporte_ventas_historico rvh on A.codigo_siga=rvh.codigo_siga
WHERE B.estatus='A'
AND rvh.fecha = '$fecha1'


".$search1."
".$search2."
".$search3."

GROUP BY A.codigo_siga
ORDER BY C.nombre_estado,A.codigo_siga,B.nombre_punto	";

//echo $sql ;exit;
$query=$ubicacion->ObtenerFilasBySqlSelect($sql);

$i=0;
$reportaron="";

$resultado=$ubicacion->getFilas($query);
while($i<$resultado){
	
	if($query[$i]['reporto']==1){
	 $total_true++;}
	

	$datos[$i]=$query[$i]; //se guardan los datos en un arreglo
	$total[$i]=$query[$i];
	$reportaron=$reportaron."'".$query[$i]['codigo_siga']."',";
	$i++;
	$p=$i;
}


$i=0;
$fecha_actual=date ('Y-m-d H:m');
for($i=0;$i<count($datos);$i++){
	
$datos[$i]['color']=color($datos[$i]['color'],$fecha_actual);



}


/*if ($reporto!='S') { //LOS QUE NO HAN REPORTADO
	$datos='';
	$reportaron=substr($reportaron, 0,strlen($reportaron)-1);
	if(empty($reportaron)) $reportaron='0';
	$sql="SELECT C.nombre_estado,'--' as fecha,B.codigo_siga_punto as codigo_siga ,B.nombre_punto, B.tipo_sincro, 'NO' as reporto
	FROM selectrapyme_central.puntos_venta B,  selectrapyme_central.estados C
	WHERE B.codigo_estado_punto=C.codigo_estado AND B.estatus='A' AND B.codigo_siga_punto NOT IN (".$reportaron.")
	".$search1."
	".$search2."
	".$search3."
	ORDER BY C.nombre_estado,B.codigo_siga_punto,B.nombre_punto"; 
	$query=$ubicacion->ObtenerFilasBySqlSelect($sql);
	$resultado=$ubicacion->getFilas($query);
	$h=0;
	while($h<$resultado){
		$datos[$h]=$query[$h]; //se guardan los datos en un arreglo
		$total[$p]=$query[$h];
		$p++;
		$h++;
	}	
}

if($sincro=='3'){
	$datos='';
}



if($sincro!='1' && $reporto!='N' && $estado=='9999' && $puntodeventa=='') { //LOS DE CODIGO SIGA ERRADO

	$sql="SELECT '--' as nombre_estado,'Error' as fecha, codigo_siga, 'Codigo Siga Errado' as nombre_punto, '--' as tipo_sincro, 'SI' as reporto
	FROM selectrapyme_central.ventas_09_15 
	WHERE codigo_siga not in (select codigo_siga_punto from selectrapyme_central.puntos_venta)
	GROUP BY codigo_siga ORDER BY codigo_siga";	
	$query=$ubicacion->ObtenerFilasBySqlSelect($sql);
	$resultado=$ubicacion->getFilas($query);
	$j=0;
	while($j<$resultado){
		$datos[$j]=$query[$j]; //se guardan los datos en un arreglo
		$total[$p]=$query[$j];
		$p++;
		$j++;
	}
}*/

/*if($reporto!='N'){
	$datos=$total;
	sort($datos);
	$resultado=$p;
}*/



$porcentaje=number_format($total_true*100/$resultado, 2, ',', ' ');
$smarty->assign("porcentaje",$porcentaje);
$smarty->assign("reportaron1",$total_true);
$smarty->assign("resultado",$resultado);
$smarty->assign("consulta",$datos);
$smarty->assign("query", $query);
$smarty->assign("aceptar", $aceptar);
  




}


  function color($fecha_archivo,$fecha_actual){
$datetime1 = new DateTime($fecha_actual);
$datetime2 = new DateTime($fecha_archivo);
$since_start = $datetime1->diff($datetime2);

$since_start->i.' minutes<br>';



$since_start->d.' days<br>';
  
    if ( $since_start->h==0 || ($since_start->h==1 && $since_start->i==0)) {
        //return  "horas=".$since_start->h."minutos=".$since_start->i."fecha_actual=".$fecha_actual."fechar_archivo=".$fecha_archivo;// 'verde';
        return 'verde';
    }else{

    	if(($since_start->h==1 &&  $since_start->i>0 && $since_start->d==0) ||($since_start->h>1 && $since_start->h<12 && $since_start->d==0 )){

		return 'amarillo';

    	}

    	if($since_start->d==1 || ($since_start->h>12 && $since_start->d==0)){
    		return 'rojo';

    	}

    	if($since_start->d>1){
    		return 'negro';

    	}



    }

};//fin de la funcion




?>