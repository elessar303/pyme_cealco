<?php
include('config_reportes.php');
include('fpdf.php');



class PDF extends RPDF {
    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
  

 function ChapterBody() {



        $sizeletra = 20;
        $this->SetY(6);


        
        $this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, utf8_decode("Avila"),0,0,'C');
        $this->Ln(7);
        $this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, utf8_decode("Fecha: 01/11/2009"),0,0,'C');
        $this->Ln(7);
	
	$this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, utf8_decode("Valor: 35.00 Bs."),0,0,'C');
	$this->Ln(7);
	
	$this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, utf8_decode("Promoción: General"),0,1,'C');
	$this->Ln(7);
	$this->SetFont('Arial','B',$sizeletra);
        $this->Code128(62,31,"9500121809111146",90,8);
	$this->Ln(9);
        $this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, "9500121809111146",0,1,'C');
	$this->Ln(7);

        $this->SetFont('Arial','B',30);
        $this->TextWithDirection(15,220,"Ventel, C.A",'U');
        $this->TextWithDirection(30,220,utf8_decode("R.I.F. G-20008550-9"),'U');
        $this->TextWithDirection(45,220,utf8_decode("Inf: 0800 Turismo 8874766"),'U');
        $this->TextWithDirection(60,220,utf8_decode("Boleto de Transporte"),'U');
        $this->TextWithDirection(75,220, utf8_decode("Fecha: 01/11/2009"),'U');
        $this->TextWithDirection(90,220, utf8_decode("Valor: 35.00 Bs."),'U');
        $this->TextWithDirection(105,220, utf8_decode("Promoción: General"),'U');


        $this->Ln(7);
	$this->SetFont('Arial','B',$sizeletra);
        $this->Code128(62,235,"9500121809111146",90,8);
	$this->Ln(174);
        $this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, "9500121809111146",0,1,'C');

       
        $sizeletra = 30;
        $this->Ln(7);
	$this->SetFont('Arial','B',$sizeletra);
        $this->Code128(10,280,"9500121809111146",90,10);
	$this->Ln(37);
        $this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, "9500121809111146",0,1,'L');
	$this->Ln(22);
        $this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, utf8_decode("Fecha: 01/11/2009"),0,0,'L');
        $this->Ln(13);

	$this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, utf8_decode("Valor: 35.00 Bs."),0,0,'L');
	$this->Ln(10);

	$this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, utf8_decode("Promoción: General"),0,1,'L');

        $this->Ln(10);
	$this->SetFont('Arial','B',$sizeletra);
        $this->Cell(0,0, utf8_decode("Luis Viera"),0,1,'L');


        
    }

    function Footer(){

	}
    function PrintChapter() {
        $this->AddPage();
        $this->ChapterBody();
    }

    function DatosGenerales($array) {
        $this->datosgenerales = $array;
    }

    function ArrayFactura($array) {
        $this->array_factura = $array;
    }


}


$nro_factura = @$_GET["codigo"];
$comunes = new ConexionComun();

$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("select * from parametros_generales");

$array_factura =   $comunes->ObtenerFilasBySqlSelect("
SELECT f.*, c.nombre, c.direccion, c.rif,c.cod_cliente, c.telefonos,c.direccion, v.nombre as nom_vendedor
, fd.*, i.cod_item, ifor.descripcion as tipo_item_
FROM boleto_factura f inner join clientes c on c.id_cliente = f.id_cliente
inner join boleto_factura_detalle fd on fd.id_factura = f.id_factura
inner join vendedor v on v.cod_vendedor = f.cod_vendedor
 inner join item i on i.id_item = fd.id_item inner join
 item_forma ifor on ifor.cod_item_forma = i.cod_item_forma
    where f.cod_factura = '".$nro_factura."'");

$pdf=new PDF('P','mm','legal');
$title='Factura Nro.';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->PrintChapter();


$pdf->SetDisplayMode('default');
$pdf->Output();

?>
