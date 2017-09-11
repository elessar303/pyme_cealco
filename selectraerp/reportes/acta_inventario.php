<?php
include('config_reportes.php');
require_once '../libs/php/clases/dompdf/dompdf_config.inc.php';
$comunes = new ConexionComun();
$id=$_GET['id_transaccion'];
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
$sql="select * from actas_inventario where id=".$id."";
$array_cabecera = $comunes->ObtenerFilasBySqlSelect($sql);
$sql="select * from actas_inventario_detalle where id_acta=".$id."";
$array_detalles = $comunes->ObtenerFilasBySqlSelect($sql);
$sql="select * from actas_inventario_detalle where id_acta=".$id." and procedencia=1"; 
$array_por_punto = $comunes->ObtenerFilasBySqlSelect($sql);
$sql="select * from actas_inventario_detalle where id_acta=".$id." and procedencia=2 or procedencia=3 ";
$array_por_sede = $comunes->ObtenerFilasBySqlSelect($sql);
$sql="select * from actas_inventario_detalle where id_acta=".$id." and procedencia=3";
$encargado = $comunes->ObtenerFilasBySqlSelect($sql);

$i=0;
$por_punto='';
while ($array_por_punto[$i]) {
	$por_punto.=$array_por_punto[$i]['nombre'].", titular de la cédula de identidad Nº V-".$array_por_punto[$i]['cedula']." cargo: ".$array_por_punto[$i]['cargo']; 
	$i=$i+1;
}

$i=0;
$por_sede='';
while ($array_por_sede[$i]) {
	$por_sede.=$array_por_sede[$i]['nombre'].", titular de la cédula de identidad Nº V-".$array_por_sede[$i]['cedula']." cargo: ".$array_por_sede[$i]['cargo'].", "; 
	$i=$i+1;
}
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
$fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
$hora=date('g:i a');
//Salida: Viernes 24 de Febrero del 2012

$html='
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
.page { page-break-before:always; }
</style>
<title>Actividades Realizadas a Usuarios</title>
</head>
<body class="page-break">
<img src="../imagenes/logo_pdval.png" align="left" width="200">
<div align="right">Acta Nro '.$id.'</div>
<br>
<h1 align="center">ACTA DE INVENTARIO</h1>
<table width="80%" align="center">
<tr>
<td>
<p align="justify">
En el día de hoy '.$fecha.', siendo las '.$hora.', reunidos en <b>'.$array_parametros_generales[0]['nombre_empresa'].'</b>, '.$array_parametros_generales[0]['direccion'].', por <b>'.$array_parametros_generales[0]['nombre_empresa'].'</b>:  '.$por_punto.'. Por <b>PDVAL SEDE CENTRAL</b>: '.$por_sede.' quienes actúan en este acto, en carácter de participes en el conteo con la finalidad de la toma física de inventario de  mercancía para la apertura del sistema SISCOL . En consecuencia, se procedió a imprimir el inventario de mercancías con existencias del día <b>'.$fecha.'</b>, correspondientes a: Deposito identificado con la letra (A), los cuales se anexan  a la presente acta debidamente  firmados y sellados, tal como se evidencia la hoja de conteo de Inventario físico del piso de Deposito, almacén efectuados, que al ser cotejados los inventarios según el conteo físico con el inventario del  almacén que proporciono el  encargado del mismo; se  deja constancia que en el inventario físico de Productos 	de alimentos y no alimentos, se deben realizar según los procedimientos correspondientes a los fines de determinar responsabilidades laborales, civiles, penales y cualquier otras estipuladas en las normativas y procedimientos legales vigentes. Así mismo, se deja constancia que a partir del día de hoy  <b>'.$fecha.'</b>, sigue siendo responsable de los inventarios que se realicen en el <b>'.$array_parametros_generales[0]['nombre_empresa'].'</b> y de los resultados que estos arrojen el encargado del mismo, '.$encargado[0]['nombre'].' ('.$encargado[0]['cargo'].'), titular de la cédula de identidad N° V- '.$encargado[0]['cedula'].'. Se realizan dos (02) ejemplares a un solo tenor y a un mismo efecto. Es todo, los presentes firman en señal de conformidad:

</p>
</td>
</tr>
</table>



<table width="80%" align="center" border=0>
<tr>
<td>&nbsp; <br> &nbsp; <br>&nbsp; <br>&nbsp;</td>
</tr>
<tr>
<td align="center" colspan="2">Por <b>'.$array_parametros_generales[0]['nombre_empresa'].'</b></td>
</tr>
<tr>';
$i=0;
while ($array_por_punto[$i]) {
	$html.='<td align="center">________________<br>'.$array_por_punto[$i]['nombre'].'<br>V -'.$array_por_punto[$i]['cedula'].'</td>';
	$i=$i+1;
	if($i==2){
	$html.="</tr><tr>";
	}
}
$html.='</tr><tr><td align="center" colspan="2">Por <b>SEDE CENTRAL</b></td></tr><tr>';
$i=0;
while ($array_por_sede[$i]) {
	$html.='<td align="center">________________<br>'.$array_por_sede[$i]['nombre'].'<br>V -'.$array_por_sede[$i]['cedula'].'</td>';
	$i=$i+1;
	if($i==2 || $i==4 ||$i==6 ||$i==8 ||$i==10 ||$i==12 ||$i==14){
	$html.="</tr><tr>";
	}

}


$html.='
</table>
</body>
</html>';


echo $html; exit();
$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();

# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("letter", "portrait");

# Cargamos el contenido HTML.
$mipdf ->load_html(utf8_decode($html));

# Renderizamos el documento PDF.
$mipdf ->render();

# Enviamos el fichero PDF al navegador.
$mipdf ->stream('Acta de Inventario.pdf');
?>