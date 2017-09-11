<?php 
if (!isset($_SESSION)) {
  session_start();
}
require('fpdf.php');
require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../lib/pdf.php';

class PDF extends FPDF
{

//Cabecera de página
function Header()
{
        $Conn=conexion_conf();
	$var_sql="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq,imagen_der from parametros";
	$rs = query($var_sql,$Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1=$row_rs['encabezado1'];
	$var_encabezado2=$row_rs['encabezado2'];
	$var_encabezado3=$row_rs['encabezado3'];
	$var_encabezado4=$row_rs['encabezado4'];
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];	
	$var_sql="select codigo,nomemp,departamento,presidente,periodo,cargo,nivel,desislr,ctaisrl,desiva,ctaiva,por_isv,compra,servicio,rif,nit,direccion,telefono,por_im,por_bomberos,lugar,sobregirop,autorizacionodp,claveodp,contrato,gas_dir from parametros";
	$rsu = query($var_sql,$Conn);
	$row_rsu = fetch_array($rsu);
	$var_nomemp=$row_rsu['nomemp'];
	
	cerrar_conexion($Conn);
	$this->SetFont('Arial','B',12);
	$this->Image($var_imagen_izq,10,8,23);
	$this->Ln();
	$this->Cell(45);
	$this->Cell(100,8,utf8_decode($var_encabezado1),0,0,"C");
	$this->Image($var_imagen_der,170,10,30);
	$this->Ln(6);
	$this->Cell(35);
	$this->Cell(120,8,utf8_decode($var_encabezado2),0,0,"C");
	$this->Ln(6);
	$this->Cell(10);
	$this->Cell(170,8,utf8_decode($var_encabezado3),0,0,"C");
	$this->Ln(7);
	$this->SetFont('Arial','B',10);
	$this->Cell(188,8,utf8_decode('GERENCIA DE ADMINISTRACIÓN FINANCIERA'),0,1,"C");
	$this->Cell(188,8,utf8_decode('REGISTRO DE PROVEEDORES Y CONTRATISTAS'),0,1,"C");
	$this->Cell(188,8,utf8_decode('CERTIFICADO DE INSCRIPCIÓN'),0,1,"C");
	

}


//Hacer que sea multilinea sin que haga un salto de linea
var $widths;
var $aligns;
var $celdas;
var $ancho;
function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
} 
function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
} 
// Marco de la celda
function Setceldas($cc)
{
   
    $this->celdas=$cc;
} 
// Ancho de la celda
function Setancho($aa)
{
    $this->ancho=$aa;
}
function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
} 
function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
} 

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        //$this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,$this->ancho[$i],$data[$i],$this->celdas[$i],$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
function Footer(){
	$this->SetY(-40);
	$this->Cell(188,10,'','B',0);
	$conexion=conexion_conf();
	$conOCS = 'select * from parametros';
	$resOCS = query($conOCS,$conexion);
	$filaOCS = fetch_array($resOCS);
	
	$direccion=$filaOCS['direccion'].' '.$filaOCS['ciudad'].' '.$filaOCS['estado'];
	$this->Ln();
	$this->SetFont('Arial','I',8);
	$this->Cell(188,5,$direccion,'',0,'C');

	$telefono='Telefonos: '.$filaOCS['telefono'].' Fax: '.$filaOCS['telefonofax'];
	$this->Ln();
	$this->Cell(188,5,$telefono,'',0,'C');
	
	$this->SetY(-15);
	$this->SetFont('Arial','I',8);
	$this->Cell(188,10,'Elaborado Por: '.$_SESSION['nombre'],0,0,'L');

}

function detalles($codigo){
	$this->Ln(20);
	$conexion=conexion();
	$consulta="select * from proveedores where cod_proveedor=$codigo";
	$query=query($consulta,$conexion);
	$fila=fetch_array($query);

	$consulta2="select * from proveedores_dat_adi where cod_pro=$codigo";
	$query2=query($consulta2,$conexion);
	$fila2=fetch_array($query2);


	$this->SetWidths(array(108,40,40));
	$this->SetAligns(array('C','C','C'));
        $this->Setceldas(array(0,1,1));
	$this->Setancho(array(5,5,5));
	$this->SetFont('Arial','B',10);
	$this->Row(array('','CIRPC','FECHA'));
	$this->SetFont('Arial','I',10);
	$this->Row(array('',$fila['cod_proveedor'],fecha($fila['registro_fecha'])));

	$this->SetFont('Arial','B',8);
	$this->Cell(47,7,'NOMBRE DE LA EMPRESA ',1,0,'C');
	$this->SetFont('Arial','I',10);
	$this->MultiCell(141,7,utf8_decode($fila['compania']),1,'L');

	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,utf8_decode('Nº RIF'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(27,7,utf8_decode($fila['rif']),1,0,'C');

	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,utf8_decode('Nº NIT'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(27,7,utf8_decode($fila['nit']),1,0,'C');
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,utf8_decode('Nº RNC'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(27,7,utf8_decode($fila2['sol_snc']),1,0,'C');
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,utf8_decode('Nº NIL'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(27,7,utf8_decode(''),1,1,'C');

	$consulta3="select * from proveedores_tel where cod_proveedor=$codigo";
	$query3=query($consulta3,$conexion);
	$general='';
	$fax='';
	$otro='';
	while($fila3=fetch_array($query3)){
		if($fila3['tipo']=='General'){
			$general=$fila3['numero'];
		}
		if($fila3['tipo']=='Fax'){
			$fax=$fila3['numero'];
		}
		if($fila3['tipo']=='Celular'){
			$otro=$fila3['numero'];
		}
	}
	$this->SetFont('Arial','B',10);
	$this->Cell(47,7,utf8_decode('TELÉFONOS:'),1,0,'C');
	$this->Cell(20,7,utf8_decode('GENERAL'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(27,7,utf8_decode($general),1,0,'L');
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,utf8_decode('FAX'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(27,7,utf8_decode($fax),1,0,'L');
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,utf8_decode('OTRO'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(27,7,utf8_decode($otro),1,1,'L');

	$this->SetFont('Arial','B',8);
	$this->Cell(58,7,utf8_decode('NOMBRE DEL REPRESENTANTE LEGAL'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(83,7,utf8_decode($fila['rep_nombres']),1,0,'L');
	$this->SetFont('Arial','B',10);
	$this->Cell(20,7,utf8_decode('Nº CÉDULA'),1,0,'C');
	$this->SetFont('Arial','I',8);
	$this->Cell(27,7,utf8_decode($fila['rep_ci']),1,1,'L');

	$consulta4="select * from clasificacion_unica where codigo=$fila[clasificacion]";
	$query4=query($consulta4,$conexion);
	$fila4=fetch_array($query4);
	
	$this->SetFont('Arial','B',10);
	$this->Cell(188,7,'ACTIVIDADES COMERCIALES O PROFESIONALES :','LTR',1,'L');
	$this->SetFont('Arial','I',10);
	$this->MultiCell(188,7,utf8_decode($fila4['descripcion']),'LBR','L');

	$this->SetFont('Arial','B',10);
	$this->Cell(188,7,utf8_decode('DIRECCIÓN DE LA EMPRESA :'),'LTR',1,'L');
	$this->SetFont('Arial','I',10);
	$this->MultiCell(188,7,utf8_decode($fila1['direccion1'].$fila1['direccion2']),'LBR','L');


	$this->Ln(20);
	$this->SetFont('Arial','B',10);
	$this->Cell(188,7,'APROBADO',0,1,'C');
	$this->Ln(10);
	$this->SetFont('Arial','B',10);
	$this->Cell(188,7,utf8_decode('GERENCIA DE ADMINISTRACIÓN FINANCIERA'),0,1,'C');
}
}
//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$codigo=$_GET['codigo'];
$pdf->detalles($codigo);
$pdf->Output();
?>