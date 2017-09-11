<?php

ob_start();
require_once("../../libs/php/adodb5/adodb.inc.php");
include("../../../general.config.inc.php");
include("../../../generalp.config.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/configuracion/config.php");
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
require_once("../../libs/php/clases/login.php");
ob_end_clean();    header("Content-Encoding: None", true);
session_start();
$bd_pyme=DB_SELECTRA_FAC;
$bd_pos=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;
$con = mysql_connect($host,$user,$pass);
$nro_cuenta=$_POST['banco'];
$monto_dep=$_POST['monto'];
if(isset($_POST['sobrante']))
$sobrante=$_POST['sobrante'];
else
$sobrante=0;
$cta_sobrante=$_POST['cta_sobrante'];
$dia=date("d");
$mes=date("m");
$ano=date("Y");
$hora=date("H");
$min=date("i");
$seg=date("s");
//$path_ingresos="/var/www/pyme/selectraerp/uploads/control_ingresos";
$path_ingresos="C:/wamp/www/siscolp_pyme/selectraerp/uploads/control_ingresos";
$fecha=date('Y-m-d H:i:s');
$fecha2=date('Y-m-d');
//se instancia a la clase de conexion
 $conn = new ConexionComun();
//$conn = new Producto();
$login = new Login();
 $regs=$conn->ObtenerFilasBySqlSelect("select max(id_deposito)+1 as id_deposito from deposito");
 //echo "select max(id_deposito)+1 as id_deposito from deposito"; exit();
//$sql="SELECT max(id_deposito) FROM $bd_pyme.deposito";
//$consulta=mysql_query($sql);
//$num=mysql_num_rows($consulta);
//$regs=mysql_fetch_array($consulta);
//correlativo
if($regs[0]['id_deposito']==''){
	$correlativo='000001';
}

if($regs[0]['id_deposito']!=''){
	//$correlativo=$regs[0]['id_deposito']+1;

	$correlativo = sprintf("%06d", $regs[0]['id_deposito']);
//echo "aaaaaa".$correlativo;

}
//codigo siga
//$sql="SELECT codigo_siga FROM $bd_pyme.parametros_generales";
$codigo=$conn->ObtenerFilasBySqlSelect("SELECT codigo_siga FROM parametros_generales");
$codigo_siga=$codigo[0]['codigo_siga'];

$codigo=$conn->ObtenerFilasBySqlSelect("SELECT banco FROM cuentas_contables WHERE nro_cuenta='".$nro_cuenta."'");
$banco=$codigo[0]['banco'];

$nro_deposito=$codigo_siga.$banco.$correlativo;
//echo $nro_deposito; exit();
// insert del deposito
$conn->BeginTrans();
$insertar=$conn->ExecuteTrans("INSERT INTO deposito (nro_deposito, monto, fecha_deposito, cod_banco, usuario_creacion)
    VALUES 
    ('".$nro_deposito."', ".$monto_dep.", '".$fecha."', '".$nro_cuenta."', '".$login->getNombreApellidoUSuario()."')");
//guardando en la tabla ingresos
$insert_ingreso=$conn->ExecuteTrans("INSERT INTO ingresos_xenviar (nro_deposito, fecha_deposito, monto_deposito, cuenta_banco, usuario_creacion)
    VALUES
    ('".$nro_deposito."', date('".$fecha."'), ".$monto_dep.",  '".$nro_cuenta."', '".$login->getNombreApellidoUSuario()."')");
    
if($sobrante>0){
$insertar=$conn->ExecuteTrans("INSERT INTO deposito (nro_deposito, monto, fecha_deposito, cod_banco, usuario_creacion)
    VALUES 
    ('".($nro_deposito+1)."', ".$cta_sobrante.", '".$fecha."', '".$sobrante."', '".$login->getNombreApellidoUSuario()."')");
//guardando sobrante en la tabla ingresos
$insert_ingreso=$conn->ExecuteTrans("INSERT INTO ingresos_xenviar (nro_deposito, fecha_deposito, monto_deposito, cuenta_banco, usuario_creacion)
    VALUES
    ('".($nro_deposito+1)."', date('".$fecha."'), '".$cta_sobrante."', '".$sobrante."', '".$login->getNombreApellidoUSuario()."')");

}

foreach ($_POST['arqueos_id'] as $key ) {
  $arqueos_id.=$key.",";  
}
$arqueos_id = trim($arqueos_id, ',');
$arqueos_id = str_replace("Array", "",$arqueos_id);
$arqueo=$conn->ExecuteTrans("update arqueo_cajero set id_deposito=".$nro_deposito.", id_deposito2=".($nro_deposito+1)." where id in (".$arqueos_id.")");
//echo "update arqueo_cajero set id_deposito=".$nro_deposito." where id in (".$arqueos_id.")"; exit();


$ver= $conn->CommitTrans(1);

//echo $ver; exit();
 
//se manda  a imprimir los pdf
if($sobrante>0){
echo"<script language='javascript'>window.open('../../reportes/depositosobrantefpdf.php?nro_deposito=".($nro_deposito+1)."&nro_cuenta=".$sobrante."');</script>";
echo"<script language='javascript'>window.open('../../reportes/depositofpdf.php?nro_deposito=".$nro_deposito."&nro_cuenta=".$nro_cuenta."');</script>";

}else{

    echo"<script language='javascript'>window.open('../../reportes/depositofpdf.php?nro_deposito=".$nro_deposito."&nro_cuenta=".$nro_cuenta."');</script>";
}

echo "
<script language='javascript'>
window.history.back();
//window.opener.location.reload();

</script>
";

/*


// se genera los archivos no se tocará.
$nomb=$codigo_siga.'_'.$ano.$mes.$dia.'_'.$hora.$min.$seg.'.csv';
$contenido_ingresos="";

$contenido_ingresos.=$codigo_siga.",".$fecha2.",".$monto_dep.",".$nro_deposito.",".$banco.",DEPE".("\r\n");


$pf_inv=fopen($path_ingresos."/".$nomb,"w+");
fwrite($pf_inv, utf8_decode($contenido_ingresos));
fclose($pf_inv);

$sql="SELECT * FROM parametros, parametros_generales";
$consulta=mysql_query($sql);
$datosgenerales=mysql_fetch_array($consulta);

$sql="SELECT nro_deposito, monto, fecha_deposito, c.nro_cuenta as cuenta, banco.descripcion as banco FROM deposito, banco, cuentas_contables as c WHERE c.banco=banco.cod_banco AND deposito.cod_banco = c.nro_cuenta AND nro_deposito='".$nro_deposito."' and deposito.cod_banco='".$nro_cuenta."'";
//echo $sql; exit();
$consulta=mysql_query($sql);
$datosdeposito=mysql_fetch_array($consulta);

$sql="SELECT a.efectivo as monto_caja,  c.NAME as host, a.fecha_venta_fin as fecha FROM arqueo_cajero a, deposito b, ".POS.".people as c WHERE a.id_deposito = b.nro_deposito AND a.id_deposito='".$nro_deposito."' and a.id_cajero=c.ID";
$consulta2=mysql_query($sql);

require('../../fpdf/fpdf.php');

class PDF extends FPDF
{

function Header()
	{

        $this->Image($this->datosgenerales["imagen_der"] ? $this->datosgenerales["imagen_der"] : $this->datosgenerales["imagen_izq"], 10, 8, 50, 20);
        $this->SetY(15);
        $this->SetLeftMargin(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(10, 50, 100);
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales["nombre_empresa"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, $this->datosgenerales["id_fiscal"] . ": " . $this->datosgenerales["rif"] . " - Telefonos: " . $this->datosgenerales["telefonos"], 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, utf8_decode($this->datosgenerales["direccion"]), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 0, "Fecha Emision: " . date("d-m-Y"), 0, 0, 'R');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("PLANILLA DE DEPÓSITO"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(40, 75, 20 ,40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->Row(array( utf8_decode('Nro Depósito'), 'Cuenta - Banco', 'Monto','Fecha'), 1);        
		$this->SetWidths(array(40, 75, 20, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->Row(array($this->datoscampos["nro_deposito"], $this->datoscampos["cuenta"]."-".$this->datoscampos["banco"], number_format($this->datoscampos["monto"],2,'.', ''), $this->datoscampos["fecha_deposito"]), 1);
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("DETALLE DEL DEPÓSITO"), 0, 0, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(45, 50, 40, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->Row(array( 'Caja', 'Secuencia Cierre','Fecha', 'Monto'), 1);
	}

	function ChapterBody() {


	}
	function Footer()
	{
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print centered page number
    $this->Cell(0,10,'Nro '.$this->PageNo(),0,0,'C');
	}

	function DatosGenerales($array) {
        $this->datosgenerales = $array;
    }

    function DatosCampos($array) {
        $this->datoscampos = $array;
    }

        function DatosDetalle($array) {
        $this->datosdetalle = $array;
    }

        function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }
}

$pdf = new PDF();
$pdf->DatosGenerales($datosgenerales);
$pdf->DatosCampos($datosdeposito);
$pdf->DatosDetalle($datosdetalle);

$pdf->SetTitle('Planilla de Deposito'); 
$pdf->AliasNbPages();
$pdf->PrintChapter();
while($resultado = mysql_fetch_array($consulta2)){
$monto_caja_format=number_format($resultado['monto_caja'],2,'.', '');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(45,5,$resultado['host'],1,0,'C');
$pdf->Cell(50,5,$resultado['secuencia_cierre'],1,0,'C');
$pdf->Cell(40,5,$resultado['fecha'],1,0,'C');
$pdf->Cell(40,5,$monto_caja_format,1,0,'R');
$monto_dep_format+=$monto_caja_format;
$pdf->Ln(); 
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(135,5,'TOTAL',1,0,'C');
$pdf->Cell(40,5,number_format($monto_dep_format,'2'),1,0,'R');
$pdf->SetDisplayMode('default');
ob_end_clean();
$pdf->Output('Planilla de Deposito.pdf','I');
*/
?>