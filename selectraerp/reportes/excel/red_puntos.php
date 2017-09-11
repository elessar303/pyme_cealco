<?php 
set_time_limit(0);
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/Menu.php");
require_once("../../libs/php/clases/parametrosgenerales.php");
require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/ajax/numerosALetras.class.php");
require_once("../../libs/php/clases/Mensajes.php");
include('../../../menu_sistemas/lib/common.php');
require_once "../../libs/php/PHPExcel/Classes/PHPExcel.php";

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

$hoy = date("d/m/Y");
$mes = $_GET['mes'];
$anio = $_GET['anio'];
//$diasMes = getDiaFinMes($mes,$anio);

$styleHeaderArray = array(
    'font' => array(
        'bold' => true,
        'size' => 20,
        'name' => 'Arial'
    )
);
$styleHeaderArray2 = array(
    'font' => array(
        'bold' => false,
        'size' => 20,
        'name' => 'Arial'
    )
);
$styleHeaderArray3 = array(
    'font' => array(
        'bold' => false,
        'size' => 16,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);

$styleHeaderColumnaData = array(
    'font' => array(
        'bold' => false,
        'size' => 6,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
    )
);

$styleHeaderColumnaData2 = array(
    'font' => array(
        'bold' => false,
        'size' => 6,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);

$styleHeaderColumnaData3 = array(
    'font' => array(
        'bold' => true,
        'size' => 8,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);

$styleHeaderColumnaData4 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F53D3D')
    ),
    'font' => array(
        'bold' => true,
        'size' => 8,
        'name' => 'Arial',
        'color' => array('argb' => 'FFFFFFFF')
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);

$styleHeaderArray4 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'C7D0D6')
    ),
    'font' => array(
        'bold' => false,
        'size' => 6,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);


$styleHeaderColumna = array(
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        )
    )
);
$styleHeaderColumnaTipo1 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '3E7DAB')
    ),
    'font' => array(
        'bold' => true,
        'size' => 9,
        'name' => 'Arial',
        'color' => array('argb' => 'FFFFFFFF')
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);
$styleHeaderColumnaTipo2 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F7DF3E')
    ),
    'font' => array(
        'bold' => true,
        'size' => 6,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' RED DE PUNTOS '.$hoy);


$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleHeaderArray);
//$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleHeaderArray2);


//$comunes = new ConexionComun();
$global = new bd("selectrapyme_central");

$ventas = $global->query("SELECT s.* from siga s  
                    where 1 
                    order by estado asc ");
$i = 1;



$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:J1');
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A2', '  N° SIGA  ')
->setCellValue('B2', '      ESTADO      ')
->setCellValue('C2', '      NOMBRE DEL PUNTO DE VENTA      ')
->setCellValue('D2', '      TIPO      ')
->setCellValue('E2', '      MUNICIPIO      ')
->setCellValue('F2', '      PARROQUIA      ')
->setCellValue('G2', '      RIF      ')
->setCellValue('H2', '      NOMBRE      ')
->setCellValue('I2', '      CI      ')
->setCellValue('J2', '      TELEFONO      ')
->setCellValue('K2', '          DIRECCION          ')
->setCellValue('L2', '      TELEFONO LOCAL      ')
->setCellValue('M2', '      STATUS      ')
->setCellValue('N2', '      FECHA APERTURA      ')
->setCellValue('H1', '      RESPONSABLE DEL PUNTO      ');


$objPHPExcel->getActiveSheet()->getStyle('A2:N2')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A2:N2')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleHeaderColumnaTipo1);

$indexFila = 3;

while($data = $ventas->fetch_assoc()){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, $data['siga'])
        ->setCellValue('B'.$indexFila, $data['estado'])
        ->setCellValue('C'.$indexFila, $data['descripcion'])
        ->setCellValue('D'.$indexFila, $data['tipo'])
        ->setCellValue('E'.$indexFila, $data['municipio'])
        ->setCellValue('F'.$indexFila, $data['parroquia'])
        ->setCellValue('G'.$indexFila, $data['rif'])
        ->setCellValue('H'.$indexFila, $data['responsable'])
        ->setCellValue('I'.$indexFila, $data['responsable_ci'])
        ->setCellValue('J'.$indexFila, $data['responsable_telefono'])
        ->setCellValue('K'.$indexFila, $data['direccion'])
        ->setCellValue('L'.$indexFila, $data['telefono'])
        ->setCellValue('M'.$indexFila, $data['status'])
        ->setCellValue('N'.$indexFila, $data['fecha_apertura']);

    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila.":N".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila.":N".$indexFila)->applyFromArray($styleHeaderColumnaData2);
    
    $indexFila++;
}

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->setTitle('PUNTOS DE LA RED'); 
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->getProtection()->setSheet(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="red_puntos.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
$objWriter->save('php://output');
exit;
?>