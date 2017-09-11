<?php 
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
$estado = $_GET['estado'];

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

$styleHeaderColumnaData33 = array(
    'font' => array(
        'bold' => true,
        'size' => 8,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
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
        'size' => 9,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);
$styleHeaderColumnaTipo3 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E0E0CA')
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', ' RED COMERCIALIZACION ');//AL MES DE '.strtoupper(mesaletras($mes)));


$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleHeaderArray);


//$comunes = new ConexionComun();
$global = new bd("selectrapyme_central");



$ventas = $global->query("SELECT 'directa' tipo_red, 
                    sum(case when status='Activo' and tipo_red='directa'  then 1 else 0 end) _activo,
                    sum(case when status='Inactivo' and tipo_red='directa'  then 1 else 0 end) _inactivo,
                    sum(case when status='Cerrado' and tipo_red='directa'  then 1 else 0 end) _cerrado
                    from siga s 
                    where estado='".$estado."'  limit 1
                    union all(
                        SELECT 'indirecta' tipo_red, 
                        sum(case when status='Activo' and tipo_red='indirecta' then 1 else 0 end) _activo,
                        sum(case when status='Inactivo' and tipo_red='indirecta' then 1 else 0 end) _inactivo,
                        sum(case when status='Cerrado' and tipo_red='indirecta' then 1 else 0 end) _cerrado
                        from siga s 
                        where estado='".$estado."' limit 1
                    )
                    order by tipo_red asc ");
$columnas = " ";
$i = 1;



$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:F2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:F3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:B4');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:B5');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:B6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:B7');

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A2', '      RESUMEN DE RED ESTADO '.$estado.'      ')
->setCellValue('A3', '      '.$hoy.'      ')//mesaletras($mes).'-'.$anio.
->setCellValue('A4', '      TIPO RED      ')
->setCellValue('C4', '      ACTIVO      ')
->setCellValue('D4', '      INACTIVO      ')
->setCellValue('E4', '      CERRADO      ')
->setCellValue('F4', '      TOTAL      ');

$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($styleHeaderColumna);

$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHeaderColumnaTipo3);
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleHeaderColumnaTipo3);
$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->applyFromArray($styleHeaderColumnaTipo1);
//$objPHPExcel->getActiveSheet()->getStyle('A4:A5')->applyFromArray($styleHeaderColumnaTipo3);


$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$indexFila = 5;
while($data = $ventas->fetch_assoc()){
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, ($data['tipo_red']=="directa")?"RED DIRECTA":"RED INDIRECTA");
    $objPHPExcel->getActiveSheet()->getStyle('A'.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$indexFila)->applyFromArray($styleHeaderColumnaData33);

    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $colIniGrupo = $proximaColumna;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, $data['_activo']);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData3);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, $data['_inactivo']);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData3);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $colFinGrupo = $proximaColumna;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, $data['_cerrado']);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData3);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$colIniGrupo.$indexFila.":".$colFinGrupo.$indexFila.")");
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);
    
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$indexFila.':'."B".$indexFila);

    
    $indexFila++;

    //$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, "TOTAL");
    $objPHPExcel->getActiveSheet()->getStyle('A'.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $colIniGrupo = $proximaColumna;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna."5".":".$proximaColumna.($indexFila-1).")");
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna."5".":".$proximaColumna.($indexFila-1).")");
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna."5".":".$proximaColumna.($indexFila-1).")");
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna."5:".$proximaColumna.($indexFila-1).")");
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

    
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$indexFila.':'."B".$indexFila);





$objPHPExcel->getActiveSheet()->setTitle('RESUMEN REDES'); 
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->getProtection()->setSheet(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="resumen_redes_estado.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
$objWriter->save('php://output');
exit;
?>