<?php
ini_set("display_errors",1);
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
        if($registro_cheque[0]["cod_correlativo_islr"]==0){
            $dataCorre = $comunes->ObtenerFilasBySqlSelect("select * from correlativos where campo = 'cod_correlativo_islr'");
            $newNumero = $dataCorre[0]["contador"]+1;
            $correlativo = new Correlativos();
            $numeroCorrelativo = date("Ym"). $correlativo->FormatCorrelativo($dataCorre[0]["formato"], $newNumero);
            $comunes->Execute2("update cheque set cod_correlativo_islr = ".$numeroCorrelativo." where cod_cheque = ".$_GET["codigo"]);
            $comunes->Execute2("update correlativos set contador = ".$newNumero."  where campo = 'cod_correlativo_islr'");

        }else{
            $numeroCorrelativo  = $registro_cheque[0]["cod_correlativo_islr"];
        }
        $comunes->CommitTrans($comunes->errorTransaccion);
        //** FIN CORRELATIVO:


        $this->SetFont('Arial','B',11);
	$this->Cell(188,8,utf8_decode('REPUBLICA BOLIVARIANA DE VENEZUELA'),0,0,"C");
        $this->Ln(7);
        $this->SetFont('Arial','B',11);
	$this->Cell(188,8,utf8_decode($regPG[0]["nombre_empresa"]),0,0,"C");
        $this->Ln(7);
        $this->SetFont('Arial','B',11);
	$this->Cell(188,8,utf8_decode('COMPROBANTE DE RETENCIÓN'),0,0,"C");
        $this->Ln(7);

        $this->SetFont('Arial','',11);
	$this->Cell(188,8,utf8_decode('Impuesto sobre la Renta (I.S.L.R.)'),0,0,"C");
        $this->Ln(10);

        $this->SetFont('Arial','I',9);
	$this->Cell(188,8,utf8_decode('N° Comprobante: ')." ".$numeroCorrelativo,0,0,"R");
        $this->Ln(8);

        $this->SetFont('Arial','B',12);
	$this->Cell(188,8,utf8_decode('DATOS DEL AGENTE DE RETENCIÓN'),1,1,"C");

        $this->Ln(4);
        $this->SetFont('Arial','I',11);
        $this->SetWidths(array(90,98));
	$this->SetAligns(array('L','L'));

        $this->Row(array(utf8_decode("Nombre o Razón Social:"),  utf8_decode($regPG[0]["nombre_empresa"]) ));
        $this->Row(array(utf8_decode("RIF:"),utf8_decode($regPG[0]["rif"])));
        $this->Row(array(utf8_decode("Dirección Fiscal:"),utf8_decode($regPG[0]["direccion"])));
        $this->Row(array(utf8_decode("Teléfonos:"),utf8_decode($regPG[0]["telefonos"])));

        $this->Ln(4);
        $this->SetFont('Arial','B',12);
	$this->Cell(188,8,utf8_decode('DATOS DEL CONTRIBUYENTE'),1,1,"C");

        $this->Ln(4);
        $this->SetFont('Arial','I',11);
        $this->SetWidths(array(70,118));
	$this->SetAligns(array('L','L'));

        $dataCheque = $comunes->ObtenerFilasBySqlSelect("SELECT prov.*, cheque.*  FROM cheque inner join
proveedores prov on prov.id_proveedor = cheque.id_proveedor
 WHERE cheque.cod_cheque = ".$_GET["codigo"]);

        $this->Row(array(utf8_decode("Nombre o Razón Social:"),utf8_decode($dataCheque[0]["descripcion"])));
        $this->Row(array(utf8_decode("R.I.F. / C.I.:"),utf8_decode($dataCheque[0]["rif"])));
        $this->Row(array(utf8_decode("Dirección:"),utf8_decode($dataCheque[0]["direccion"])));
        $this->Row(array(utf8_decode("Teléfonos:"),utf8_decode($dataCheque[0]["telefonos"])));

        $this->Ln(4);
        $this->SetFont('Arial','B',12);
	$this->Cell(188,8,utf8_decode('INFORMACIÓN RELACIONADA AL IMPUESTO RETENIDO'),1,1,"C");


    }

    function Footer() {

                $conn = new ConexionComun();
        $dataCheque =  $conn->ObtenerFilasBySqlSelect("
SELECT cheque.* FROM cheque where cod_cheque = ".$_GET["codigo"]);

    //Posición: a  cm del final
        $this->SetY(-40);
        $this->SetFont('Arial','B',11);
        $this->Cell(14,0,"Lugar: ",0,0,'L');$this->SetFont('Arial','',11);
        $this->Cell(70,0,"Caracas",0,0,'L');

	$this->SetY(-40);
	$this->SetX(-10);
        $this->SetFont('Arial','',11);
        $this->Cell(70,0,"Firma y Sello del ",0,0,'C');
	$this->Cell(70,0,"Agente de Retencion",0,0,'C');

	//$this->SetY(-40);
  //      $this->SetFont('Arial','B',11);

//        $this->Cell(70,0,"Firma y Sello del Beneficiario ",0,0,'R');

	
        $this->SetX(60);
        $this->SetFont('Arial','',11);
        $this->Cell(70,0,utf8_decode("Firma y Sello "),0,0,'C');
 


        $this->SetX(130);
        $this->SetFont('Arial','',11);
        $this->Cell(70,0,utf8_decode("Firma y Sello del Beneficiario"),0,0,'L');


        $this->Ln(6);
        $this->SetFont('Arial','B',11);
        $this->Cell(16,0,"Estado: ",0,0,'L');$this->SetFont('Arial','',11);
        $this->Cell(70,0,"Distrito Capital",0,0,'L');

	$this->SetX(60);
	$this->SetFont('Arial','',11);
	$this->Cell(70,0,utf8_decode("del Agente de Retencion"),0,0,'C');	

        $this->Ln(6);
        $this->SetFont('Arial','B',11);
        $this->Cell(15,0,"Fecha: ",0,0,'L');$this->SetFont('Arial','',11);
        list($anio,$mes,$dia) =  explode("-",$dataCheque[0]["fecha"]);
        $this->Cell(70,0,$dia."-".$mes."-".$anio,0,0,'L');
	
	$this->Ln(6);
	$this->SetX(60);
        $this->SetFont('Arial','B',11);
        $this->Cell(70,0,utf8_decode("_______________________"),0,0,'C');

        $this->SetX(130);
        $this->SetFont('Arial','B',11);
        $this->Cell(70,0,utf8_decode("_______________________"),0,0,'L');



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

        $this->Ln(4);
        $this->SetFont('Arial','I',11);
        $this->SetWidths(array(31,31,31,31,31,31));
	$this->SetAligns(array('C','C','C','C','C','C'));
        $this->Row(array("Factura","Monto Total","Monto Objeto de Retencion","% Retencion",'',"Monto Retenido"));

       $sumBaseImp = 0;
       $sumMonReten = 0;
        foreach($this->dataRegistros as $reg){
//          $this->Ln(4);
            $this->SetFont('Arial','',11);
            $this->SetWidths(array(31,31,31,31,31,31));
            $this->SetAligns(array('C','C','C','C','C','C'));
            $this->Row(array($reg["numero_control_factura"], number_format($reg["TotalTotalcompra"],2,",","." ),number_format($reg["totalizar_base_imponible"],2,",","." ),number_format($reg["totalizar_pbase_retencion"],2,",","." ),'',number_format($reg["totalizar_monto_retencion"],2,",","." )));
            $sumBaseImp += $reg["totalizar_base_imponible"];
            $sumMonReten += $reg["totalizar_monto_retencion"];
        }

        $this->Ln(4);
        $this->SetFont('Arial','',11);
        $this->SetWidths(array(31,31,31,31,31,31));
        $this->SetAligns(array('C','C','C','C','C','C'));
        $this->Setceldas(array('LTB','TB','TB','TB','TB','RTB'));
        $this->Row(array("","Totales:",number_format($sumBaseImp, 2,".",","), "",  number_format($sumMonReten, 2,".",",")));

        $this->Ln(4);
        $this->SetFont('Arial','B',11);
        $this->Cell(70,6,"Datos Relacionados con el pago",0,0,'L');

        $conn = new ConexionComun();
        $dataCheque =  $conn->ObtenerFilasBySqlSelect("
SELECT banco.*, tesor_bancodet.nro_cuenta, cheque.cheque FROM cheque inner join chequera on 
 chequera.cod_chequera = cheque.cod_chequera inner join tesor_bancodet on
 tesor_bancodet.cod_tesor_bandodet = chequera.cod_tesor_bandodet  inner join banco on
 banco.cod_banco = tesor_bancodet.cod_banco where cheque.cod_cheque = ".$_GET["codigo"]);


        $x=95;
        $this->SetX($x);
        $this->SetFont('Arial','',11);
        $this->Cell(95,6, utf8_decode("N° de Cheque: ".$dataCheque[0]["cheque"]),0,0,'L');
        $this->Ln(6);
        $this->SetX($x);
        $this->SetFont('Arial','',11);
        $this->Cell(95,6,"Cuenta Bancaria: ".$dataCheque[0]["nro_cuenta"],0,0,'L');





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
    SELECT ti.*, c.TotalTotalcompra, c.montoItemscompra, ch.concepto FROM cheque ch
       inner join compra c on
            cast(c.cod_compra  as decimal(10,0))   in  (".$in.")
       inner join tabla_impuestos  ti on
            cast(ti.numero_control_factura  as decimal(10,0))  =  cast(c.cod_compra  as decimal(10,0))
       WHERE ti.tipo_documento = 'c' AND ch.cod_cheque = ".$_GET["codigo"]." AND ti.cod_tipo_impuesto =2");


if(count($registros)==0){

        $registros = $conn->ObtenerFilasBySqlSelect("
    SELECT  * from cheque
       WHERE cod_cheque = ".$_GET["codigo"]);

    echo "Disculpe, el cheque N° ".$registros[0]["cheque"]." no existe retención I.S.L.R. asociada, verifique.";
    exit;
}

$pdf=new PDF();
$pdf->getRegistros($registros);

$title='';
$pdf->AliasNbPages();

$pdf->SetTitle($title);
$pdf->PrintChapter();

$pdf->Output();

?>
