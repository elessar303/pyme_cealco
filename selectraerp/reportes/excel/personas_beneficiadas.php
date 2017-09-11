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

$styleHeaderArray = array(
    'font' => array(
        'bold' => true,
        'size' => 12,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
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

$styleHeaderColumnaTipo4 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'A1A19C')
    ),
    'font' => array(
        'bold' => true,
        'size' => 9,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);
$styleHeaderColumnaTipo5 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'EBEEF2')
    ),
    'font' => array(
        'bold' => true,
        'size' => 16,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:D2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:D3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:D4');
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' Gerencia de Mercadeo y Ventas')
->setCellValue('A2', ' Unidad de Ventas')
->setCellValue('A3', ' RESUMEN DE VENTAS ACUMULADAS POR ESTADO')
->setCellValue('A4', ' '.$hoy.' ');


$objPHPExcel->getActiveSheet()->getStyle('A1:D4')->applyFromArray($styleHeaderArray);


//$comunes = new ConexionComun();
$global = new bd("selectrapyme_central");

$queryReporte = " SELECT s.estado,
                    sum((vp.total_kilos*0.001)) toneladas,
                    sum(((vp.precio+vp.iva)*vp.unidad)) bolivares,
                    vb.beneficiados 
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    left join vbeneficiados_acumulado_anio vb on vb.anio = $anio and vb.siga = vp.siga 
                    where year(vp.fecha) = $anio  
                    group by s.estado  
                    order by estado asc ";

$ventas = $global->query($queryReporte);
$i = 1;




$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A7', '      ESTADO        ')
->setCellValue('B7', '      TOTAL TM      ')
->setCellValue('C7', '      TOTAL BS      ')
->setCellValue('D7', '    BENEFICIADOS    ');


$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($styleHeaderColumnaTipo1);

$indexFila = 8;
$filaDatos = 0;
while($data = $ventas->fetch_assoc()){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, $data['estado'])
        ->setCellValue('B'.$indexFila, $data['toneladas'])
        ->setCellValue('C'.$indexFila, $data['bolivares'])
        ->setCellValue('D'.$indexFila, $data['beneficiados']);

    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumnaData2);
    $objPHPExcel->getActiveSheet()->getStyle("B".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("B".$indexFila)->applyFromArray($styleHeaderColumnaData2);
    $objPHPExcel->getActiveSheet()->getStyle("C".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("C".$indexFila)->applyFromArray($styleHeaderColumnaData2);
    $objPHPExcel->getActiveSheet()->getStyle("D".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("D".$indexFila)->applyFromArray($styleHeaderColumnaData2);
    
    $indexFila++;
    $filaDatos++;
}   
    if($filaDatos!=0){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$indexFila, " TOTAL RESULTADO ")
            ->setCellValue('B'.$indexFila, "=SUM(B7:B".($indexFila-1).")")
            ->setCellValue('C'.$indexFila, "=SUM(C7:C".($indexFila-1).")")
            ->setCellValue('D'.$indexFila, "=SUM(D7:D".($indexFila-1).")");
    }
    else{
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$indexFila, " TOTAL RESULTADO ")
            ->setCellValue('B'.$indexFila, "=0")
            ->setCellValue('C'.$indexFila, "=0")
            ->setCellValue('D'.$indexFila, "=0");
    }

    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumnaTipo1);
    $objPHPExcel->getActiveSheet()->getStyle("B".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("B".$indexFila)->applyFromArray($styleHeaderColumnaTipo1);
    $objPHPExcel->getActiveSheet()->getStyle("C".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("C".$indexFila)->applyFromArray($styleHeaderColumnaTipo1);
    $objPHPExcel->getActiveSheet()->getStyle("D".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("D".$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->setTitle('PERSONAS BENEFICIADAS'); 
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->getProtection()->setSheet(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="personas_beneficiadas.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
$objWriter->save('php://output');
exit;
?>