<?php
# Modificado el sábado, 28 de enero de 2012 
include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');

class PDF extends FPDF {

	public $title;
	public $conexion;
	public $datosgenerales;
	public $array_factura;

	function Header() {

		$width = 10;

		//$this->Image('../imagenes/banner_superior.jpg',10,5,190);


		$this->SetY(5);
		$this->SetFont('Arial', '', 6);
		//$this->SetFillColor(239,239,239);
		$this->SetFont('Arial', 'B', 6);
		$this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["nombre_empresa"]), 0, 0, 'C');
		$this->Ln(3);
		$this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["direccion"]), 0, 0, 'C');
		$this->Ln(3);
		$this->Cell(0, 0, $this->datosgenerales[0]["id_fiscal"] . ": " . $this->datosgenerales[0]["rif"] . " - Telefonos: " . $this->datosgenerales[0]["telefonos"], 0, 0, 'C');
		$this->Ln(3);

		$this->Cell(0, 0, utf8_decode("Fecha de Creación: " . fecha($this->array_factura[0]["fechaFactura"])), 0, 0, 'R');
		$this->Ln(3);
		$this->SetFont('Arial', '', 8);
		$this->Cell(0, 0, "Nro. Factura: " . $this->array_factura[0]["cod_factura"], 0, 0, 'R');
		$this->Ln(3);
		$this->SetX(14);

		$this->SetFont('Arial', '', 6);
		$this->Cell(0, 0, "CLIENTE: " . utf8_decode($this->array_factura[0]["nombre"]), 0, 0, 'L');
		$this->Ln(3);

		$this->SetX(14);
		$this->Cell(0, 0, utf8_decode($this->datosgenerales[0]["id_fiscal"] . ": " . $this->array_factura[0]["rif"]), 0, 0, 'L');
		$this->Ln(3);

		$this->SetX(14);
		$this->Cell(0, 0, utf8_decode("DIRECCIÓN: " . $this->array_factura[0]["direccion"]), 0, 0, 'L');
		$this->Ln(3);

		$this->SetX(14);
		$this->Cell(0, 0, utf8_decode("TELEFONOS: " . $this->array_factura[0]["telefonos"]), 0, 0, 'L');
		$this->Ln(3);


		$this->SetLeftMargin(50);
		$width = 5;
		$this->SetX(5);
		$this->SetFont('Arial', '', 6);
		$this->SetFillColor(10, 10, 10, 10, 10, 10, 10, 10, 10);
		$this->Cell(11, $width, 'Codigo', 1, 0, "C", 0);
		$this->Cell(80, $width, utf8_decode('Descripción'), 1, 0, "C", 0);
		$this->Cell(11, $width, utf8_decode('Cantidad'), 1, 0, "C", 0);
		$this->Cell(15, $width, utf8_decode('Precio'), 1, 0, "C", 0);
		$this->Cell(20, $width, utf8_decode('Descuento'), 1, 0, "C", 0);
		$this->Cell(15, $width, utf8_decode('%'), 1, 0, "C", 0);
		$this->Cell(20, $width, utf8_decode('Total Sin I.V.A'), 1, 0, "R", 0);
		$this->Cell(11, $width, utf8_decode('% I.V.A'), 1, 0, "R", 0);
		$this->Cell(20, $width, utf8_decode('Total con I.V.A'), 1, 0, "R", 0);
		$this->Ln(5);
	}

	function Footer() {
		$this->SetY(-15);
		$this->SetFont('Arial', 'I', 10);

		$this->Cell(0, 10, 'P gina ' . $this->PageNo(), 0, 0, 'C');
	}

	function dwawCell($title, $data) {
		$width = 8;
		$this->SetFont('Arial', 'B', 12);
		$y = $this->getY() * 20;
		$x = $this->getX();
		$this->SetFillColor(206, 230, 100);
		$this->MultiCell(175, 8, $title, 0, 1, 'L', 0);
		$this->SetY($y);
		$this->SetFont('Arial', '', 12);
		$this->SetFillColor(206, 230, 172);
		$w = $this->GetStringWidth($title) + 3;
		$this->SetX($x + $w);
		$this->SetFillColor(206, 230, 172);
		$this->MultiCell(175, 8, $data, 0, 1, 'J', 0);
	}

	function ChapterBody() {
		//$conn = new rp_Connect();
		//$conn->SQL("select * from esquema.almacen_ubicacion");
		$this->SetWidths(array(11, 80, 11, 15, 20, 15, 20, 11, 20));
		$this->SetAligns(array("C", "J", "C", "C", "C", "C", "R", "R", "R"));
		$this->SetFillColor(10, 10, 10, 10, 10, 10, 10, 10, 10);
		$cantidaditems = $this->array_factura[0]["cantidad_items"];

		$subtotal = 0;
		for ($i = 0; $i < $cantidaditems; $i++) {
			$this->SetLeftMargin(30);
			$width = 5;
			$this->SetX(5);
			$this->SetFont('Arial', '', 6);

			$subtotal += $this->array_factura[$i]["_item_cantidad"] * $this->array_factura[$i]["_item_preciosiniva"];
			$this->Row(
			array($this->array_factura[$i]["cod_item"],
			$this->array_factura[$i]["_item_descripcion"],
			$this->array_factura[$i]["_item_cantidad"],
			number_format($this->array_factura[$i]["_item_preciosiniva"], 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"],
			number_format($this->array_factura[$i]["_item_descuento"], 2, ',', '.') . " %",
			number_format($this->array_factura[$i]["_item_montodescuento"], 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"],
			number_format($this->array_factura[$i]["_item_totalsiniva"], 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"],
			number_format($this->array_factura[$i]["_item_piva"], 2, ',', '.') . " %",
			number_format($this->array_factura[$i]["_item_totalconiva"], 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"]), 1);
		}

		$this->Ln(2);
		//$this->SetLeftMargin(50);
		$width = 5;
		$this->SetX(173);
		$this->SetFont('Arial', '', 6);
		$this->Cell(15, $width, utf8_decode('Sub-Total'), 1, 0, "R", 0);
		$this->Cell(20, $width, number_format($subtotal, 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"], 1, 0, "R", 0);
		$this->Ln(5);
		$this->SetX(173);
		$this->Cell(15, $width, utf8_decode('Descuento'), 1, 0, "R", 0);
		$this->Cell(20, $width, number_format($this->array_factura[0]["descuentosItemFactura"], 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"], 1, 0, "R", 0);
		$this->Ln(5);
		$this->SetX(173);
		$this->Cell(15, $width, utf8_decode('I.V.A'), 1, 0, "R", 0);
		$this->Cell(20, $width, number_format($this->array_factura[0]["ivaTotalFactura"], 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"], 1, 0, "R", 0);
		$this->Ln(5);
		$this->SetX(173);
		$this->Cell(15, $width, utf8_decode('Retenciones'), 1, 0, "R", 0);
		$this->Cell(20, $width, number_format($this->array_factura[0]["totalizar_total_retencion"], 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"], 1, 0, "R", 0);
		$this->Ln(5);

		$this->SetX(173);
		$this->Cell(15, $width, utf8_decode('Total a Pagar'), 1, 0, "R", 0);
		$this->Cell(20, $width, number_format($this->array_factura[0]["TotalTotalFactura"], 2, ',', '.') . " " . $this->datosgenerales[0]["moneda"], 1, 0, "R", 0);
		$this->Ln(5);
	}

	function ChapterTitle($num, $label) {
		$this->SetFont('Arial', '', 10);
		$this->SetFillColor(200, 220, 255);
		$this->Cell(0, 6, "$label", 0, 1, 'L', 1);
		$this->Ln(8);
	}

	function SetTitle($title) {
		$this->title = $title;
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

$array_factura = $comunes->ObtenerFilasBySqlSelect("
SELECT f.*, c.nombre, c.direccion, c.nit,c.cod_cliente,c.rif,c.telefonos,c.direccion, v.nombre as nom_vendedor
, fd.*, fp.*, i.cod_item, ifor.descripcion as tipo_item_
FROM factura f inner join clientes c on c.id_cliente = f.id_cliente
inner join factura_detalle fd on fd.id_factura = f.id_factura
inner join vendedor v on v.cod_vendedor = f.cod_vendedor
 inner join factura_detalle_formapago fp on f.id_factura = fp.id_factura inner join item i on i.id_item = fd.id_item inner join
 item_forma ifor on ifor.cod_item_forma = i.cod_item_forma
    where f.cod_factura = '" . $nro_factura . "'");

#-------------------------------------

include("../libs/php/clases/factura.php");
include("../modulos/principal/txt.php");

$obj_txt = new txt();
$factura = new Factura();

# Comenzar a crear el archivo para el spooler:
# Directorio para guardar el archivo

$directorio = "C:\FACTURAS\\";
$nombre_archivo_spooler = "Selectra.001";
$ruta = $directorio . $nombre_archivo_spooler;
$archivo_spooler = fopen($ruta, "w");
chmod($directorio, 0777);
chmod($ruta, 0777);

$detalles.=$obj_txt->formatearLineasDetallesPago("FACTURA:", $obj_txt->completar($nro_factura, 8, "0"), 20) . "\n";

$detalles.="CLIENTE:    " . $obj_txt->formatearDatosCabecera($array_factura[0]['nombre']) . "\n";
$detalles.="DIRECCION1: " . $obj_txt->formatearDatosCabecera($array_factura[0]['direccion']) . "\n";
$detalles.="DIRECCION2:\n";/* . $obj_txt->formatearDatosCabecera()*/
$detalles.="TELEFONO:   " . $array_factura[0]['telefonos'] . "\n";
$detalles.="RIF/CI:     " . $array_factura[0]['rif'] . "\n";
$detalles.="DESCRIPCION                             CODIGO                    CANT      PRECIO    IVA\n";

fwrite($archivo_spooler, $detalles);

for ($i = 0; $i < $array_factura[0]['cantidad_items']; $i++) {
	$descrip_producto = $obj_txt->formatearDatosCabecera($array_factura[$i]["_item_descripcion"], ".", 39);
	$linea_producto = "";
	$codigo = $array_factura[$i]["id_item"];
	$cantidad = $obj_txt->formatearCantidadDecimales($array_factura[$i]["_item_cantidad"]);
	$precio = $obj_txt->formatearCantidadDecimales($array_factura[$i]["_item_preciosiniva"]);
	$iva = $obj_txt->formatearCantidadDecimales($array_factura[$i]["_item_piva"]);

	$whitespaces = 30 - (strlen($codigo) + strlen($cantidad));
	for ($j = 0; $j < $whitespaces; $j++) {
		$codigo.=" ";
	}
	$linea_producto.=$descrip_producto . " " . $codigo . $cantidad . $obj_txt->completar($precio, 12) . $obj_txt->completar($iva, 7) . "\n";

	$descrip_producto = $factura->ObtenerFilasBySqlSelect("SELECT descripcion2 FROM item WHERE id_item=" . $array_factura[0]["id_item"]);
	if ($descrip_producto[0]['descripcion2'] != "") {
		$linea_producto.=$descrip_producto[0]['descripcion2'] . "\n";
	}
	fwrite($archivo_spooler, $linea_producto);
}

$linea_producto = $obj_txt->formatearLineasDetallesPago("DESCUENTO:", $obj_txt->formatearCantidadDecimales($array_factura[0]["totalizar_pdescuento_global"] > 0 ? $array_factura[0]["totalizar_pdescuento_global"] : 0)) . " %\n";

$linea_producto.=$obj_txt->formatearLineasDetallesPago("TOTAL NETO:", $obj_txt->formatearCantidadDecimales($array_factura[0]["totalizar_base_imponible"])) . "\n";

$linea_producto.=$obj_txt->formatearLineasDetallesPago("TOTAL CANCELADO:", $obj_txt->formatearCantidadDecimales($array_factura[0]["totalizar_total_general"])) . "\n";

$linea_producto.=$obj_txt->formatearLineasDetallesPago("EFECTIVO:", $obj_txt->formatearCantidadDecimales($array_factura[0]["totalizar_monto_efectivo"])) . "\n";

$linea_producto.=$obj_txt->formatearLineasDetallesPago("CHEQUES:", $obj_txt->formatearCantidadDecimales($array_factura[0]["totalizar_monto_cheque"])) . "\n";

$linea_producto.=$obj_txt->formatearLineasDetallesPago("TARJETA:", $obj_txt->formatearCantidadDecimales($array_factura[0]["totalizar_monto_tarjeta"])) . "\n";
$linea_producto.=$obj_txt->formatearLineasDetallesPago("CREDITO:", $obj_txt->formatearCantidadDecimales($array_factura[0]["totalizar_saldo_pendiente"])) . "\n";

$serial = $factura->ObtenerFilasBySqlSelect("SELECT impresora_serial FROM parametros_generales");
//$linea_producto.="USUARIOS:         " . $login->getUsuario() . "\n";
$linea_producto.="COMENTARIO1:      NO SE ACEPTAN DEVOLUCIONES DESPUES DE 24 HORAS \n";
//$linea_producto.="COMENTARIO2:      REPRESENTACIONES 14, C.A.            \n";
//$linea_producto.="COMENTARIO2:      NO SE ACEPTAN DEVOLUCIONES DESPUES DE 24 HORAS                   \n";
$linea_producto.="DATOS PARA LAS  \"D E V O L U C I O N E S\"\n";
$linea_producto.="FACTDEVOL:        0000000000\n";
$linea_producto.="FECHADEVOL:       00/00/0000\n";
$linea_producto.="HORADEVOL:        00:00:00\n";
$linea_producto.="SERIALIMP:        " . $serial[0]['impresora_serial'] . "\n";
$linea_producto.="COO-BEMATECH:     000123\n";

fwrite($archivo_spooler, $linea_producto);
fclose($archivo_spooler);
#-------------------------------------
$pdf = new PDF('P', 'mm', 'letter');
$title = 'Factura Nro.';
$pdf->DatosGenerales($array_parametros_generales);
$pdf->ArrayFactura($array_factura);

$pdf->SetTitle($title);
$pdf->PrintChapter();
$pdf->SetDisplayMode('default');
$pdf->Output();
?>
