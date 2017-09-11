<?php
ini_set("display_errors",0);
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
include("../libs/php/clases/correlativos.php");
class PDF extends FPDF {
    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    private $dataRegistros;
    public $cod_cheque;
    function Header() {
        $comunes = new ConexionComun();
        $regPG = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

        //** INICIO CORRELATIVO:
        $comunes->BeginTrans();
        $registro_cheque = $comunes->ObtenerFilasBySqlSelect("select * from cheque where cod_cheque = ".$_GET["codigo"]);
        if($registro_cheque[0]["cod_correlativo_iva"]==0){
            $dataCorre = $comunes->ObtenerFilasBySqlSelect("select * from correlativos where campo = 'cod_correlativo_iva'");
            $newNumero = $dataCorre[0]["contador"]+1;
            $correlativo = new Correlativos();
            $numeroCorrelativo = date("Ym"). $correlativo->FormatCorrelativo($dataCorre[0]["formato"], $newNumero);
            $comunes->Execute2("update cheque set cod_correlativo_iva = ".$numeroCorrelativo." where cod_cheque = ".$_GET["codigo"]);
            $comunes->Execute2("update correlativos set contador = ".$newNumero."  where campo = 'cod_correlativo_iva'");

        }else{
            $numeroCorrelativo  = $registro_cheque[0]["cod_correlativo_iva"];
        }
        $comunes->CommitTrans($comunes->errorTransaccion);
        //** FIN CORRELATIVO:


        $this->SetFont('Arial','B',11);
	$this->Cell(0,8,utf8_decode('REPUBLICA BOLIVARIANA DE VENEZUELA'),0,0,"C");
        $this->Ln(7);
        $this->SetFont('Arial','B',11);
	$this->Cell(0,8,utf8_decode($regPG[0]["nombre_empresa"]),0,0,"C");
        $this->Ln(7);
        $this->SetFont('Arial','B',11);
	$this->Cell(0,8,utf8_decode('COMPROBANTE DE RETENCIÓN DE IMPUESTO AL VALOR AGREGADO'),0,0,"C");
        $this->Ln(7);

        $this->SetFont('Arial','I',8);
	$this->MultiCell(0,5,utf8_decode('(Ley IVA - Art. 11: "Serán responsables del pago del impuesto en calidad de agente  de retención, los compradores o adquirientes de determinados bienes muebles y los y los receptores de ciertos servicios, a quienes la Administración Tributaria designe como tal.)'),0,"C");
        $this->Ln(2);

	$this->SetWidths(array(85,100,75));
	$this->SetAligns(array('L','C','C'));
	$this->Setceldas(array(1,1,1));
	$this->SetFont('Arial','B',10);
        $this->Row(array(utf8_decode('Nombre o Razón Social del Agente de Retención'),utf8_decode('Registro de Información Fiscal del Agente de Retención'),utf8_decode('Nro. de Comprobante')));
	$this->Ln(1);
        $this->SetWidths(array(85,100,75));
	$this->SetAligns(array('L','C','C'));
	$this->Setceldas(array(0,0,0));
	$this->SetFont('Arial','',10);
        $this->Row(array(utf8_decode($regPG[0]["nombre_empresa"]),utf8_decode($regPG[0]["rif"]),$numeroCorrelativo));
        $this->Ln(1);

        $dataCheque = $comunes->ObtenerFilasBySqlSelect("SELECT prov.*, cheque.*, date_format(cheque.fecha,'%Y') as anio, date_format(cheque.fecha,'%d/%m/%Y') as fecha_cheque  FROM cheque inner join
proveedores prov on prov.id_proveedor = cheque.id_proveedor
 WHERE cheque.cod_cheque = ".$_GET["codigo"]);

	$this->SetWidths(array(85,100,75));
	$this->SetAligns(array('L','C','C'));
	$this->Setceldas(array(1,1,1));
	$this->SetFont('Arial','B',10);
        $this->Row(array(utf8_decode('Dirección Fiscal del Agente de Retención'),utf8_decode('Período Fiscal'),utf8_decode('Fecha')));
	$this->Ln(1);
        $this->SetWidths(array(85,100,75));
	$this->SetAligns(array('L','C','C'));
	$this->Setceldas(array(0,0,0));
	$this->SetFont('Arial','',10);
        $this->Row(array(utf8_decode($regPG[0]["direccion"]),date("Y - m"),$dataCheque[0]["fecha_cheque"]));
        $this->Ln(1);

	$this->SetWidths(array(85,100));
	$this->SetAligns(array('L','C'));
	$this->Setceldas(array(1,1));
	$this->SetFont('Arial','B',10);
        $this->Row(array(utf8_decode('Nombre o Razón Social del Sujeto Retenido'),utf8_decode('Registro de Información Fiscal del Sujeto Retenido (RIF)')));
	$this->Ln(1);
        $this->SetWidths(array(85,100,75));
	$this->SetAligns(array('L','C','C'));
	$this->Setceldas(array(0,0,0));
	$this->SetFont('Arial','',10);
        $this->Row(array(utf8_decode($dataCheque[0]["descripcion"]),$dataCheque[0]["rif"]));
        $this->Ln(2);
    }

    function Footer() {

//                $conn = new ConexionComun();
//        $dataCheque =  $conn->ObtenerFilasBySqlSelect("
//SELECT cheque.* FROM cheque where cod_cheque = ".$_GET["codigo"]);
//
//    //Posición: a  cm del final
//        $this->SetY(-40);
//        $this->SetFont('Arial','B',11);
//        $this->Cell(14,0,"Lugar: ",0,0,'L');$this->SetFont('Arial','',11);
//        $this->Cell(70,0,"San Francisco",0,0,'L');
//
//        $this->SetX(130);
//        $this->SetFont('Arial','B',11);
//        $this->Cell(70,0,utf8_decode("Firma y sello de retención"),0,0,'L');
//
//        $this->Ln(6);
//        $this->SetFont('Arial','B',11);
//        $this->Cell(16,0,"Estado: ",0,0,'L');$this->SetFont('Arial','',11);
//        $this->Cell(70,0,"Zulia",0,0,'L');
//
//        $this->Ln(6);
//        $this->SetFont('Arial','B',11);
//        $this->Cell(15,0,"Fecha: ",0,0,'L');$this->SetFont('Arial','',11);
//        list($anio,$mes,$dia) =  explode("-",$dataCheque[0]["fecha"]);
//        $this->Cell(70,0,$dia."-".$mes."-".$anio,0,0,'L');
//
//        $this->SetX(130);
//        $this->SetFont('Arial','B',11);
//        $this->Cell(70,0,utf8_decode("_______________________"),0,0,'L');


    //Posición: a  cm del final
    $this->SetY(-25);
    //       $this->Ln();
    //cheque($this->pdff);
    $this->SetFont('Arial','',8);
    $this->Cell(0,0,'FIRMA Y SELLO DEL AGENTE DE RETENCION',0,0,'L');	
    $this->SetFont('Arial','',8);
    $this->Cell(0,0,'FIRMA Y SELLO DEL SUJETO RETENIDO',0,0,'R');
    //$this->Cell(0,5,'Elaborado Por: '.$_SESSION['usuario'],0,1,'L');
    //$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');

    }

    function dwawCell($title,$data) {
        $width = 8;
        $this->SetFont('Arial','B',12);
        $y =  $this->getY() * 20;
        $x =  $this->getX();
        $this->SetFillColor(206,230,100);
        $this->MultiCell(175,8,$title,0,1,'L',0);
        $this->SetY($y);
        $this->SetFont('Arial','',12);
        $this->SetFillColor(206,230,172);
        $w=$this->GetStringWidth($title)+3;
        $this->SetX($x+$w);
        $this->SetFillColor(206,230,172);
        $this->MultiCell(175,8,$data,0,1,'J',0);

    }


    function ChapterBody() {

        $this->SetWidths(array(15,23,15,20,15,15,18,19,25,30,18,16,17,17));
        $this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C','C'));
        $this->Setceldas(array(1,1,1,1,1,1,1,1,1,1,1,1,1,1));
        $this->Row(
                array(
                    utf8_decode('Oper. Nº'),
                    'Fecha Factura',
                    utf8_decode('Nº Factura'),
                    utf8_decode('Nº Control Factura'),
                    utf8_decode('Nº Nota Débito.'),
                    utf8_decode('Nº Nota Crédito.'),
                    'Tipo de Transacc.',
                    utf8_decode('Nº de Fac. Afect.'),
                    utf8_decode('Total Compras IVA'),
                    utf8_decode('Compras S/D Credito Fiscal'),
                    utf8_decode('Base Imponible'),
                    utf8_decode('% Alicuota'),
                    utf8_decode('Impuesto IVA'),
                    'I.V.A. Retenido'
                    )
                );

       $sumBaseImp = 0;
       $sumMonReten = 0;
        $i=1;
        foreach($this->dataRegistros as $reg){
            $this->SetFont('Arial','',10);
            $this->SetWidths(array(15,23,15,20,15,15,18,19,25,30,18,16,17,17));
            $this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C','C'));
            $this->Setceldas(array(1,1,1,1,1,1,1,1,1,1,1,1,1,1));
            $this->Row(
                    array(
                            $i,
                            date_format(date_create($reg['fechacompra']),'d-m-Y'),
                            $reg['num_factura_compra'],
                            $reg['num_cont_factura']
                            ,"-","-","01","-",
                            number_format($reg['TotalTotalcompra'], 2, ',', '.'),
                            number_format($reg['totalizar_monto_exento'], 2, ',', '.'),
                            number_format($reg['montoItemscompra'], 2, ',', '.'),
                            number_format($reg['totalizar_pbase_retencion'], 2, ',', '.'),
                            number_format($reg['ivaTotalcompra'], 2, ',', '.'),
                            number_format($reg['totalizar_monto_retencion'], 2, ',', '.')
                            )
                    );
            $i++;
        }



    }

    function ChapterTitle($num,$label) {
        $this->SetFont('Arial','',10);
        $this->SetFillColor(200,220,255);
        $this->Cell(0,6,"$label",0,1,'L',1);
        $this->Ln(8);
    }

    function SetTitle($title) {
        $this->title   = $title;
    }

    function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }

    function getRegistros($registros) {
        $this->dataRegistros = $registros;
    }



}


 $conn = new ConexionComun();

 $reg = $conn->ObtenerFilasBySqlSelect("SELECT ref
FROM cheque
WHERE cod_cheque =".$_GET["codigo"]); //Verificamos si tiene varias compras por un cheque

$cadena = explode("/",$reg[0]["ref"]);
$in ="";
foreach($cadena as $key => $registros){
$in .= (int)$registros;
if($key+1!=count($cadena)) $in .= ",";
}


        $registros = $conn->ObtenerFilasBySqlSelect("
    SELECT ti.*, c.TotalTotalcompra, c.montoItemscompra, c.*, ch.concepto FROM cheque ch
       inner join compra c on
            cast(c.cod_compra  as decimal(10,0))   in  (".$in.")
       inner join tabla_impuestos  ti on
            cast(ti.numero_control_factura  as decimal(10,0))   = cast(c.cod_compra  as decimal(10,0))
       WHERE ti.tipo_documento = 'c' AND ch.cod_cheque = ".$_GET["codigo"]." AND ti.cod_tipo_impuesto =1");




        if(count($registros)==0){



        $registros = $conn->ObtenerFilasBySqlSelect("
    SELECT  * from cheque
       WHERE cod_cheque = ".$_GET["codigo"]);

    echo "Disculpe, el cheque N° ".$registros[0]["cheque"]." no existe retención I.V.A. asociada, verifique.";
    exit;
}

$pdf=new PDF('L','mm','Letter');
$pdf->getRegistros($registros);

$title='';
$pdf->AliasNbPages();

$pdf->SetTitle($title);
$pdf->PrintChapter();

$pdf->Output();

?>
