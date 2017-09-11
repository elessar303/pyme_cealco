<?php

include('config_reportes.php');
include('fpdf.php');
include('../../menu_sistemas/lib/common.php');
session_start();

class PDF extends FPDF {

    public $title;
    public $conexion;
    public $datosgenerales;
    public $array_factura;
    public $cod_cheque;

    function Header() {

    }

    /*   function Footer() {

      //Posición: a  cm del final
      $this->SetY(-65);
      //       $this->Ln();
      //cheque($this->pdff);
      $this->SetFont('bienvenu','',8);
      $this->Cell(188,5,utf8_decode('Gerencia de Finanzas'),1,0,'C');
      $this->Ln();
      // Firmantes
      $this->SetFont('bienvenu','I',6);
      $this->Cell(63,20,'','LT',0);
      $this->Cell(63,20,'','LT',0);
      $this->Cell(62,20,'','LTR',0);
      $this->Ln();
      // llamado para hacer multilinea sin que haga salto de linea
      $this->SetWidths(array(63,63,62));
      $this->SetAligns(array('C','C','C'));
      $this->Setceldas(array('1','1','1'));
      $this->Row(array(utf8_decode('Administración'),'Gerencia General','Presidente'));
      // fin
      $this->SetWidths(array(63,63,62));
      $this->SetAligns(array('C','C','C'));
      $this->Setceldas(array('1','1','1'));


      $this->SetFont('bienvenu','',8);
      $this->Cell(188,5,'Beneficiario',1,0,'C');
      $this->Ln();

      $this->SetFont('bienvenu','I',8);
      $this->SetAligns(array('L','L','L'));
      $this->Setceldas(array('LT','LT','LTR'));

      $this->Row(array('Nombre: ','R.I.F. o C.I. : ',''));

      $this->SetFont('bienvenu','I',8);
      $this->Cell(63,5,'','LB',0);
      $this->Cell(63,5,'','LB',0);
      $this->Cell(62,5,'Firma:________________________________','LBR',0);
      $this->Ln();


      $this->SetFont('bienvenu','I',8);
      $this->Cell(0,5,'Elaborado Por: '.$_SESSION['usuario'],0,1,'L');
      $this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,1,'C');


      } */

    function dwawCell($title, $data) {
        $width = 8;
        $this->SetFont('bienvenu', '', 12);
        $y = $this->getY() * 20;
        $x = $this->getX();
        $this->SetFillColor(206, 230, 100);
        $this->MultiCell(175, 8, $title, 0, 1, 'L', 0);
        $this->SetY($y);
        $this->SetFont('bienvenu', '', 12);
        $this->SetFillColor(206, 230, 172);
        $w = $this->GetStringWidth($title) + 3;
        $this->SetX($x + $w);
        $this->SetFillColor(206, 230, 172);
        $this->MultiCell(175, 8, $data, 0, 1, 'J', 0);
    }

    function ChapterBody() {
        $comunes = new ConexionComun();
        $objNumLetra = new numerosALetras();
        $sql = "
            SELECT
            cheque.cod_cheque,
            prov.descripcion as beneficiario,
            prov.rif,
            cheque.ref,
            (
            SELECT date_format(fechacompra,'%d-%m-%Y')  from compra where
            CAST(cod_compra AS DECIMAL(5,0)) = cheque.ref
            ) as fechacompra,
            cheque.ref as nro_compra,
            date_format(cheque.fecha,'%d-%m-%Y') as fecha_cheque,
            cheque.concepto,
            cheque.cheque as nro_cheque,
            cheque.monto,
            tesor_bancodet.nro_cuenta,
            banco.descripcion banco
            FROM cheque inner join proveedores prov on
             prov.id_proveedor = cheque.id_proveedor
            inner join chequera on
             chequera.cod_chequera = cheque.cod_chequera
            inner join tesor_bancodet on
             tesor_bancodet.cod_tesor_bandodet =  chequera.cod_tesor_bandodet
            inner join banco on
             banco.cod_banco = tesor_bancodet.cod_banco
            where cheque.cod_cheque = " . $this->cod_cheque;
        $registros = $comunes->ObtenerFilasBySqlSelect($sql);

        $ciudad = $comunes->ObtenerFilasBySqlSelect("SELECT ciudad FROM parametros_generales");
        $monto = $registros[0]["monto"];
        list($dia, $mes, $anio) = explode("-", $registros[0]["fecha_cheque"]);

        $nombreContribuyente = $registros[0]["beneficiario"];
        $fecha_emision_cheque = $registros[0]["fecha_cheque"];
        $stringMes = array(
            "01" => "Enero",
            "02" => "Febrero",
            "03" => "Marzo",
            "04" => "Abrir",
            "05" => "Mayo",
            "06" => "Junio",
            "07" => "Julio",
            "08" => "Agosto",
            "09" => "Septiembre",
            "10" => "Octubre",
            "11" => "Noviembre",
            "12" => "Diciembre"
        );
        $stringmes = $stringMes[$mes];
        $dia = $dia;

        /**
         * INFORMACION DEL PAGO (CHEQUE)
         */
        $montoLetras = strtoupper($objNumLetra->numerosALetras($monto));
        //sacar ciudad
        //$this->Ln();
        $concepto = $registros[0]["concepto"];
        $this->SetFont('bienvenu', '', 11);
        $this->Cell(188, 7, '', '', 0);
        $this->Ln(8);
        $this->Cell(122, 7, '', '', 0);
        $this->Cell(45, 7, '**' . number_format($monto, 2, ',', '.') . '**', 0, 0, 'L');
        $this->Ln(14);
        $this->SetFont('bienvenu', '', 10);
        $this->Cell(20, 7, '', 0, 0);
        $this->Cell(138, 7, utf8_decode($nombreContribuyente), 0, 0, 'L');
        $this->Ln(8);
        $this->Cell(13, 5, '', 0, 0);
        $this->MultiCell(145, 5, $montoLetras, 0, 'L');
        //echo strlen($montoLetras);
        if (strlen($montoLetras) < 72) {
            $this->Ln(8);
        }
        $this->Ln(2);
        $this->SetFont('bienvenu', '', 10);
        $this->Cell(55, 5, "   {$ciudad[0]["ciudad"]}, " . $dia . "  de " . $stringmes, 0, 0);
        $this->Cell(58, 5, '        ' . $anio, 0, 0);
        $this->Ln(18);
        $this->SetFont('bienvenu', '', 10);
        $this->Cell(145, 7, '*** NO ENDOSABLE ***', 0, 0, 'R');
        $this->Ln();
        $this->Cell(145, 7, ' CADUCA A LOS 90 DIAS ', 0, 0, 'R');

        $this->Ln(25);

        /**
         * INFORMACION DEL PAGO (CHEQUE)
         */
        /**
         * INFORMACION DEL PAGO (PAGO Y CONTABLE)
         */
        if (strlen($montoLetras) < 76) {
            $this->Ln(-1);
        }
        $this->SetFont('bienvenu', '', 10);
        $this->MultiCell(148, 5, utf8_decode($concepto), 0, 'J');
        $valor = (int) (strlen($concepto) / 63);
        $espacio = 0;
        switch ($valor) {
            case 0:$espacio = 15;
                break;
            case 2:$espacio = 10;
                break;
            case 3:$espacio = 7;
                break;
        }

        $this->Ln($espacio);

        //$this->Cell(60,5,'Banco: ','LTR',0);
        //$this->Cell(60,5,utf8_decode('Nº de Cuenta : '),'LTR',0);
        //$this->Cell(40,5,utf8_decode('Nº de Cheque : '),'LTR',0);
        //$this->Cell(28,5,utf8_decode('Fecha de Emisión:  '),'LTR',0);
        //$this->Ln();
        //$this->SetFont('bienvenu','I',8);
        // llamado para hacer multilinea sin que haga salto de linea
        $this->SetWidths(array(75, 80));
        $this->SetAligns(array('C', 'R'));
        $this->Setceldas(array(0, 0));
        $this->SetFont('bienvenu', '', 11);
        //$this->Row(array($registros[0]["banco"],$registros[0]["nro_cuenta"],$registros[0]["nro_cheque"],$fecha_emision_cheque));
        $this->Row(array($registros[0]["nro_cheque"], $registros[0]["banco"]));
        $this->SetFont('bienvenu', '', 10);
        $this->Ln(12);
        //fin

        /* $this->SetFont('bienvenu','',8);
          $this->Cell(188,5,'SOLICITUD DE PAGO ',1,0,'C');
          $this->Ln(); */

//	$this->Cell(40,5,utf8_decode('Nº Orden : ').$oc,1,0,'L');
//	$this->Cell(40,5,utf8_decode('Nº Solicitud : ').$odp,1,0,'L');
        /*
          $this->Cell(60,5,utf8_decode("Nro. de Compra: ").$registros[0]["ref"],1,0,'L');
          $this->Cell(128,5,'Fecha : '.$registros[0]["fechacompra"],1,0,'L');


          $this->Ln();

         */

//      CUENTA CONTABLE
        /*       $this->SetFont('bienvenu','',8);
          $this->Cell(40,7,'Codigo Contable',1,0,'C');
          $this->Cell(68,7,utf8_decode('Descripción'),1,0,'C');
          $this->Cell(40,7,'Debito',1,0,'C');
          $this->Cell(40,7,'Credito',1,0,'C');
          $this->Ln();
         */
        // llamado para hacer multilinea sin que haga salto de linea
        $this->SetFont('bienvenu', '', 10);
        $this->SetWidths(array(33, 75, 23, 37));
        $this->SetAligns(array('L', 'L', 'C', 'R'));
        $this->Setceldas(array(0, 0, 0, 0));

        $data = $comunes->ObtenerFilasBySqlSelect("
            SELECT *,
                case tipo when 'd' then monto end as debito,
                case tipo when 'c' then monto end as credito
                    FROM cheque_bache_det where
                    cod_cheque = " . $this->cod_cheque . " order by tipo");

        $debitos = 0;
        $creditos = 0;
        $linea = 0;
        foreach ($data as $campos) {
            $debito = number_format($campos["debito"], 2, ',', '.');
            $credito = number_format($campos["credito"], 2, ',', '.');

            $this->Row(array($campos["cuenta_contable"], utf8_decode($campos["descripcion"]), ($campos["debito"] != 0) ? $debito : '', ($campos["credito"] != 0) ? $credito : ''));
            $debitos +=$campos["debito"];
            $creditos +=$campos["credito"];
            $linea+=1;
            $this->Ln(1);
        }
        $final = 16 - $linea;
        $valor = $final * 5;
        $this->Ln($valor);
        //$this->SetFont('SanserifB','B',10);
        // $this->Cell(68,7,'Total:  ',0,0,'R');
        $this->SetFont('bienvenu', '', 10);
        $this->Cell(108, 7, '', 0, 0);
        $this->Cell(22, 7, number_format($debitos, 2, ',', '.'), 0, 0, 'C');
        $this->Cell(35, 7, number_format($creditos, 2, ',', '.'), 0, 0, 'R');
        $this->Ln();
//      CUENTA CONTABLE

        /**
         * INFORMACION DEL PAGO (PAGO Y CONTABLE)
         */
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('bienvenu', '', 10);
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

    function setCodCheque($codCheque) {
        $this->cod_cheque = $codCheque;
    }

}

$codCheque = @$_GET["cod_cheque"];
#$comunes = new ConexionComun();
//define('FPDF_FONTPATH','/font/');
$pdf = new PDF();
$pdf->setCodCheque($codCheque);
$title = '';
$pdf->AddFont('bienvenu');
$pdf->AliasNbPages();
#$pdf->DatosGenerales($array_parametros_generales);
#$pdf->ArrayFactura($array_factura);

$pdf->SetTitle($title);
$pdf->PrintChapter();

$pdf->Output();
?>
