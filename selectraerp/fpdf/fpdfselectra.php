<?php

/*
 * @author: Charli Vivenes
 * @email:  cjvrinf@gmail.com - cvivenes@asys.com.ve
 *
 * Esta clase fue creada para aprovechar de manera correcta la reutilizaci�n de c�digo fuente
 * a trav�s del mecanismo de herencia de clases; refactorizando as� todo el c�digo repetido
 * en c/u de los archivos que crean reportes. De esta manera, esta clase viene a sustituir a la clase
 * PDF creada originalmente desde la que se heredaban atributos y metodos de la clase FPDF pero
 * repitiendo todas las funciones en cada archivo. Ahora dichas funciones est�n definidas
 * en esta clase y son heredadas por las antiguas clases PDF que ahora instancian los m�todos
 * comunes de FPDSelectra, quedando as� en las clases PDF los m�todos espec�ficos
 * de cada reporte particular.
 * NOTA: Aun se est� haciendo el trabajo de modificar c�digo para ajustarlo a la nueva filosof�a.
 * */
require('fpdf.php');
// require_once '../lib/config.php';
require_once '../lib/common.php';
require_once '../libs/php/clases/ConexionComun.php';
require_once '../libs/php/clases/login.php';
include('../lib/numerosALetras.class.php');

class FPDFSelectra extends FPDF {

    var $widths;
    var $aligns;
    var $celdas;
    var $ancho;
    var $nro_ocs;
    var $titulo_reporte;

    function setTituloReporte($titulo) {
        /*
         * M�todo creado con la finalidad de hacer m�s din�mica la configuraci�n de reportes,
         * en este caso pasando el t�tulo en la llamada de la funci�n en la clase que hereda.
         * */
        $this->titulo_reporte = $titulo;
    }

    function getTituloReporte() {
        return $this->titulo_reporte;
    }

    function SetWidths($w) {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    // Marco de la celda
    function Setceldas($cc) {

        $this->celdas = $cc;
    }

    // Ancho de la celda
    function Setancho($aa) {
        $this->ancho = $aa;
    }

    function CheckPageBreak($h) {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt) {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l+=$cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                }
                else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    function Row($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            //$this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w, $this->ancho[$i], $data[$i], $this->celdas[$i], $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function Header() {
        $Conn = conexion_conf();
        $var_sql = "SELECT * FROM parametros_generales";
        $rs = query($var_sql, $Conn);
        $row_rs = fetch_array($rs);

        $var_imagen_izq = "../../includes/imagenes/" . $row_rs['img_izq'];
        $var_imagen_der = "../../includes/imagenes/" . $row_rs['img_der'];
        $var_nomemp = $row_rs['nombre_empresa'];

        cerrar_conexion($Conn);

        $this->SetY(15);
        $this->SetLeftMargin(15);
        $this->SetFont("Arial", "B", 8);
        #$this->Image($var_imagen_izq, 10, 8, 33);
        //$this->Image($var_imagen_der ? $var_imagen_der : $var_imagen_izq, 10, 8, 50, 20);
        #$this->Cell(45);
        $this->Cell(0, 0, utf8_decode($var_nomemp), 0, 0, "C");
        //$this->Image($var_imagen_der,170,15,33);
        $this->Ln(7);
        $this->Cell(0, 0, utf8_decode($row_rs['direccion']), 0, 0, "C");

        $this->Ln(3);
        $this->Cell(0, 0, $row_rs['id_fiscal'] . ": " . utf8_decode($row_rs['rif']) . " - Telefonos: " . utf8_decode($row_rs['telefonos']), 0, 0, "C");

        $this->SetFont("Arial", "I", 8);
        $this->Ln(3);
        $this->Cell(0, 0, 'Fecha de Emision: ' . date('d-m-Y'), 0, 0, "R");
        //$this->SetFont("Arial", "B", 8);
        $this->Ln(10);
        $this->SetX(14);
        $this->SetFont('Arial', 'B', 14);
        $this->Ln(3);
        $this->Cell(0, 0, $this->getTituloReporte(), 0, 0, "C");
        $this->SetLineWidth(0.1);
        $this->Ln(10);
    }

    function Footer() {
        //Posición: a  cm del final
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        //Número de página
        $this->Cell(188, 5, utf8_decode('Pagina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetFont('Arial', 'I', 8);
        $this->Ln();
    }

}

?>
