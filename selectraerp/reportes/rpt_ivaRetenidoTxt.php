<?php
include('config_reportes.php');
include('../../menu_sistemas/lib/common.php');

$fecha = @$_GET["fecha"];

$dias=substr($fecha,0,2);
$mes=mesaletras(substr($fecha,3,2));
$anio=substr($fecha,6,4);
if($dias<=15)
{
	$diaIni=$anio."-".substr($fecha,3,2)."-01";
	$diaFin=$anio."-".substr($fecha,3,2)."-15";
	$cad="1 Quincena ".$mes." ".$anio;
}
else
{
	$diaIni=$anio."-".substr($fecha,3,2)."-16";
	$diaFin=$anio."-".substr($fecha,3,2)."-".date("t",mktime(0, 0, 0, substr($fecha,3,2), 1,$anio));
	$cad="2 Quincena ".$mes." ".$anio;
}
$periodo=$anio.substr($fecha,3,2);
$comunes = new ConexionComun();

$datosGenerales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");
$fechaz=$fecha."-01";
$arrayFacturas =   $comunes->ObtenerFilasBySqlSelect("SELECT cxpf.*, pro.rif as prorif , pro.descripcion as prodesc  FROM cxp_factura cxpf JOIN cxp_edocuenta cxpe ON ( cxpf.id_cxp_edocta = cxpe.cod_edocuenta ) JOIN proveedores pro ON ( pro.id_proveedor = cxpe.id_proveedor ) where cxpf.fecha_recepcion BETWEEN '".$diaIni."' and '".$diaFin."' and cxpf.cod_correlativo_iva<>0 AND cxpf.cod_estatus<>3 ORDER BY cxpf.cod_correlativo_iva");

//$dir=$_SERVER['DOCUMENT_ROOT'].'/sisalud/selectraerp/reportes/';

//$directorio=$dir."txt/arch".date("Y_m_d_H_i_s");
$directorio="txt/arch".date("Y_m_d_H_i_s");
if(mkdir($directorio))
{
	$ruta=$directorio."/iva_retenido.txt";
	$archivo= fopen($ruta,"w");
	chmod($directorio,0777);
	chmod($ruta,0777);
}
else
{
	echo "No se pudo crear el directrorio";
}
$cad='';
$i=0;
while($arrayFacturas[$i])
{
	if($arrayFacturas[$i][cod_correlativo_iva]!=0)
	{
		$cad='';
		$cad.=$datosGenerales[0]["rif"];
		$cad.="	".$periodo;
		$cad.="	".$arrayFacturas[$i][fecha_factura];
		$cad.="	C";
		$cad.="	01";
		$cad.="	".$arrayFacturas[$i][prorif];
		$cad.="	".$arrayFacturas[$i][cod_factura];
		$cad.="	".str_replace ("-","",$arrayFacturas[$i][cod_cont_factura]);
		$cad.="	".number_format($arrayFacturas[$i][monto_total_con_iva],2,".","");
		$cad.="	".number_format($arrayFacturas[$i][monto_base],2,".","");
		$cad.="	".number_format($arrayFacturas[$i][monto_retenido],2,".","");
		if($arrayFacturas[$i][factura_afectada]==''){
				$cad.="	0";
		}else{
				$cad.="	".$arrayFacturas[$i][factura_afectada];
		}
		$cad.="	".$arrayFacturas[$i][cod_correlativo_iva];
		$cad.="	".number_format($arrayFacturas[$i][monto_exento],2,".","");
		$cad.="	".number_format($arrayFacturas[$i][porcentaje_iva_mayor],2,".","");
		$cad.="	0";
		$cad.="\r\n";
		fwrite($archivo,$cad);
	}
	$i++;
}
fclose($archivo);
//echo "Content-Disposition: attachment; filename=".$ruta;
header("Content-type: application/octet-stream");
readfile($ruta); 
header('Content-Disposition: attachment; filename="iva_retenido.txt"');
?>
