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
$conn = new ConexionComun();
$bd_pyme=DB_SELECTRA_FAC;
$bd_pos=POS;
$pass=DB_CLAVE;
$user=DB_USUARIO;
$host=DB_HOST;
$con = mysql_connect($host,$user,$pass);
mysql_select_db($bd_pyme) or die('No se pudo seleccionar la base de datos');
$login = new Login();
$bolsas=$_POST['depositos'];
$nro_cataporte=$_POST['nro_cataporte'];
$monto_usuario=(isset($_POST['monto_usuario']) && $_POST['monto_usuario']!="") ? $_POST['monto_usuario'] : 0 ;
$observacion=(isset($_POST['observacion']) && $_POST['observacion']!="") ? "'".$_POST['observacion']."'" : 'NULL';
if(!is_numeric($monto_usuario))
{
    echo
    "
      <script language='javascript' type='text/JavaScript'>
      alert('Campo Monto debe Ser Numerico');
      history.back(-1);
      </script>
    ";
    exit();
}
if($observacion=='NULL'  || empty($monto_usuario))
{
    echo
    "
      <script language='javascript' type='text/JavaScript'>
      alert('Campos Vacios, Verificar');
      history.back(-1);
      </script>
    ";
    exit();
}
session_start();
$count=count($bolsas);
$fecha=date('Y-m-d H:i:s');
$i=0;
$num=$conn->ObtenerFilasBySqlSelect("Select count(*) as contar from caja_principal where id_cataporte='".$nro_cataporte."'");
if($num[0]['contar']==0)
{
    // insert del deposito
    $conn->BeginTrans();
    //insertar en comprobante
    $insertC=$conn->ExecuteTrans("insert into comprobante (caja, banco, tipo_mov) values ('".$monto_usuario."', '".$monto_usuario."', 'DEPE' )");
    for ($i=0;$i<$count;$i++)
    {
    	$insertar=$conn->ExecuteTrans(
            "INSERT INTO 
            bolsas_cataporte(nro_cataporte, nro_bolsa) 
            VALUES 
            ('".$nro_cataporte."','".$bolsas[$i]."')
            ");
    }//Fin for
	
    $insertar2=$conn->ExecuteTrans(
        "INSERT INTO 
        cataporte (nro_cataporte, cant_bolsas, fecha, cod_usuario, monto_usuario, observacion) 
        VALUES 
        ('".$nro_cataporte."',".$count.",'".$fecha."',".$_SESSION['cod_usuario'].", ".$monto_usuario.", ".$observacion.")
        ");
	foreach ($_POST['nro_depositos'] as $key) 
    {
        $num1=$conn->ObtenerFilasBySqlSelect("Select monto_acumulado from caja_principal where nro_deposito='".$key."'");
        $acumuladototal=$num1[0]['monto_acumulado']-$monto_usuario;
        if($monto_usuario==0)
        {
            $monto_usuario=$acumuladototal;
            $acumuladototal=0;
        }
        $codigo=$conn->ObtenerFilasBySqlSelect("SELECT codigo_siga FROM parametros_generales");
        $codigo_siga=$codigo[0]['codigo_siga'];
        $regs=$conn->ObtenerFilasBySqlSelect("select max(id_deposito)+1 as id_deposito from caja_principal");
        $regs_deposito=$conn->ObtenerFilasBySqlSelect("select max(nro_deposito) as id_deposito from caja_principal");
        
        if($regs[0]['id_deposito']=='')
        {
            $correlativo='000001';
            
        }
        if($regs[0]['id_deposito']!='')
        {
            $correlativo = sprintf("%06d", $regs[0]['id_deposito']);
        }
        $correlativo=$codigo_siga.$correlativo;
        //insertamos
        $insertar3=$conn->ExecuteTrans(
            "INSERT INTO caja_principal
            (id_deposito, nro_deposito, monto, monto_acumulado, id_cataporte, fecha_deposito, cod_banco, usuario_creacion, monto_tarjeta, monto_acumulado_tarjeta, monto_deposito, monto_acumulado_deposito, monto_tickets, monto_acumulado_tickets, monto_cheque, monto_acumulado_cheque, monto_credito, monto_acumulado_credito) 
            (select (id_deposito+1), '".$correlativo."', ".$monto_usuario.", ".$acumuladototal.", '".$nro_cataporte."', now(), cod_banco, '".$login->getNombreApellidoUSuario()."', 0, monto_acumulado_tarjeta, 0, monto_acumulado_deposito, 0, monto_acumulado_tickets, 0, monto_acumulado_cheque, 0, monto_acumulado_credito from caja_principal where nro_deposito='".$key."')
            ");

        //se modifica depositos de esas cajas principales
        $select_depositos=$conn->ObtenerFilasBySqlSelect("select * from caja_principal_depositos where id_caja_principal='".($regs_deposito[0]['id_deposito'])."'");
        if(isset($select_depositos) && $select_depositos!='')
        {
            foreach ($select_depositos as $key => $value)
            {
                $conn->ExecuteTrans("update caja_principal_depositos set id_caja_principal='".$correlativo."' where id=".$value['id']);
            }
        }

        //$insertar3=$conn->ExecuteTrans("UPDATE caja_principal SET id_cataporte='".$nro_cataporte."' WHERE nro_deposito='".$."'");

        $verificar_deposito=$conn->ObtenerFilasBySqlSelect("Select count(id) as existe  from ingresos_xenviar where nro_deposito='".$correlativo."'");
       
        //preguntamos si el deposito existe en la tabla ingreso_xenviar
        if($verificar_deposito[0]["existe"]>0)
        {   //si el usuario no modifico el monto, se realiza el proceso normal
            if($monto_usuario==NULL)
            {
                $ingre_update=$conn->ExecuteTrans("update ingresos_xenviar set nro_cataporte='".$nro_cataporte."', fecha_cataporte='".$fecha."', usuario_creacion_cataporte='".$login->getNombreApellidoUSuario()."' where nro_deposito='".$correlativo."'");
            }
            else
            {
                $ingre_update=$conn->ExecuteTrans("update ingresos_xenviar set nro_cataporte='".$nro_cataporte."', fecha_cataporte='".$fecha."', usuario_creacion_cataporte='".$login->getNombreApellidoUSuario()."', monto_usuario_cataporte='".$monto_usuario."', observacion=".$observacion." where nro_deposito='".$correlativo."'");
            }
            
        }
        else
        {
            $deposito1=$conn->ObtenerFilasBySqlSelect("Select * from caja_principal where nro_deposito='".$correlativo."'");
            
            
                $ingre_insert=$conn->ExecuteTrans("insert into ingresos_xenviar 
                (nro_deposito, fecha_deposito, monto_deposito, cuenta_banco, usuario_creacion, nro_cataporte, fecha_cataporte, usuario_creacion_cataporte)
                VALUES
                ('".$correlativo."', '".$deposito1[0]['fecha_deposito']."', '".$monto_usuario."', '".$deposito1[0]['cod_banco']."', '".$deposito1[0]['usuario_creacion']."', '".$nro_cataporte."', '".$fecha."', '".$login->getNombreApellidoUSuario()."')");
            
            /*else
            {
                $ingre_insert=$conn->ExecuteTrans("insert into ingresos_xenviar 
                (nro_deposito, fecha_deposito, monto_deposito, cuenta_banco, usuario_creacion, nro_cataporte, fecha_cataporte, usuario_creacion_cataporte, monto_usuario_cataporte, observacion)
                VALUES
                ('".$key."-S', '".$deposito1[0]['fecha_deposito']."', '".$deposito1[0]['monto']."', '".$deposito1[0]['cod_banco']."', '".$deposito1[0]['usuario_creacion']."', '".$nro_cataporte."', '".$fecha."', '".$login->getNombreApellidoUSuario()."', '".$monto_usuario."', ".$observacion.")");
            }*/
        }
    }//fin foreach



}


    //$sql=$conn->ExecuteTrans("UPDATE cataporte SET monto=(SELECT sum(monto) as monto_cataporte FROM caja_principal WHERE id_cataporte='".$nro_cataporte."') WHERE nro_cataporte='".$nro_cataporte."'");
    $sql=$conn->ExecuteTrans("UPDATE cataporte SET monto=(SELECT monto as monto_cataporte FROM caja_principal WHERE id_cataporte='".$nro_cataporte."') WHERE nro_cataporte='".$nro_cataporte."'");
    
$ver= $conn->CommitTrans(1);

//se mantiene por ahora este tipo de conexion a la bd

$sql="SELECT * FROM $bd_pyme.parametros, $bd_pyme.parametros_generales";
$consulta=mysql_query($sql);
$datosgenerales=mysql_fetch_array($consulta);

$sql="SELECT * FROM $bd_pyme.cataporte WHERE nro_cataporte='".$nro_cataporte."'";
$consulta=mysql_query($sql);
$datoscataporte=mysql_fetch_array($consulta);

$sql="SELECT * FROM $bd_pyme.bolsas_cataporte WHERE nro_cataporte='".$nro_cataporte."'";
$consulta1=mysql_query($sql);

/*require('../../fpdf/fpdf.php');

class PDF extends FPDF
{

function Header()
	{

        $this->Image('../../../includes/imagenes/'.$this->datosgenerales["img_izq"], 10, 8, 50, 20);
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
        $this->SetX(17);
        $this->Cell(0, 0, utf8_decode("PLANILLA DE CATAPORTE"), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(40,40, 25, 40));
        $this->SetAligns(array("C", "C", "C", "C"));
        $this->SetX(25);
        $this->Row(array( utf8_decode('Nro Cataporte'), 'Cant. Bolsas', 'Monto Retiro', 'Fecha'), 1);        
		$this->SetWidths(array(40,40, 25, 40));
        $this->SetAligns(array("C", "C", "C"));
        $this->SetFont('Arial', '', 10);
        $this->SetX(25);
        if($this->datoscampos["monto_usuario"]!=0)
        {
            $this->datoscampos["monto"]=$this->datoscampos["monto_usuario"];
        }
        $this->Row(array($this->datoscampos["nro_cataporte"], $this->datoscampos["cant_bolsas"], number_format($this->datoscampos["monto"],2,'.', ''), $this->datoscampos["fecha"]), 1);
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, utf8_decode("DETALLE DEL CATAPORTE"), 0, 0, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetLineWidth(0.1);
        $this->SetWidths(array(45, 100));
        $this->SetAligns(array("C", "C"));
        $this->SetX(25);
        $this->Row(array( 'Nro', 'Bolsa'), 1);
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
$pdf->DatosCampos($datoscataporte);
$pdf->DatosDetalle($datosdetalle);
$pdf->SetTitle('Planilla de Cataporte'); 
$pdf->AliasNbPages();
$pdf->PrintChapter();

$j=1;
while($resultado = mysql_fetch_array($consulta1)){
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(45,5,$j,1,0,'C');
$pdf->Cell(100,5,$resultado['nro_bolsa'],1,0,'C');
$pdf->Ln(); 
$j++;
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetX(17);
/*$pdf->Cell(0, 0, utf8_decode("DEPÓSITOS ASOCIADOS"), 0, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(25);
$pdf->SetWidths(array(25, 80 ,40));
$pdf->SetAligns(array("C", "C", "C"));
$pdf->Row(array( utf8_decode('Nro Depósito'), 'Cuenta - Banco', 'Fecha'), 1);*/ 
/*
$sql="SELECT nro_deposito, nro_cuenta, fecha_deposito, cuentas_contables.descripcion as descripcion, banco.descripcion as banco FROM caja_principal, banco, cuentas_contables WHERE deposito.cod_banco=cuentas_contables.nro_cuenta and banco.cod_banco=cuentas_contables.banco AND id_cataporte='".$nro_cataporte."'";
//echo "SELECT nro_deposito, nro_cuenta, fecha_deposito, cuentas_contables.descripcion as descripcion, banco.descripcion as banco FROM deposito, banco, cuentas_contables WHERE deposito.cod_banco=cuentas_contables.nro_cuenta and banco.cod_banco=cuentas_contables.banco AND id_cataporte='".$nro_cataporte."'"; exit();
$consulta3=mysql_query($sql);
$j=1;
while($resultado = mysql_fetch_array($consulta3)){
$pdf->SetFont('Arial', '', 10);
$pdf->SetX(25);
$pdf->Cell(25,5,$resultado['nro_deposito'],1,0,'C');
$pdf->Cell(80,5,$resultado['descripcion']."-".$resultado['banco'],1,0,'C');
$pdf->Cell(40,5,$resultado['fecha_deposito'],1,0,'C');
$pdf->Ln(); 
$j++;
}

$pdf->SetDisplayMode('default');
ob_end_clean();
//$pdf->Output('Planilla de Cataporte.pdf','I');
ob_end_flush();*/
echo"<script language='javascript' type='text/JavaScript'>window.open('../../reportes/imprimir_copia_cataporte.php?codigo=".$datoscataporte['id']."_');</script>";
echo"<script language='javascript' type='text/JavaScript'>window.open('../../reportes/comprobante_contable_banco.php');</script>";
echo"<script language='javascript' type='text/JavaScript'>history.back(1);</script>";
//exit();
?>
