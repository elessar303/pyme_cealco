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
$diasMes = getDiaFinMes($mes,$anio);

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' VENTAS ACUMULADAS AL: '.$hoy);


$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleHeaderArray);
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleHeaderArray2);


//$comunes = new ConexionComun();
$global = new bd("selectrapyme_central");

$ventasCol = $global->query("SELECT distinct  g.descripcion grupo
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    INNER JOIN grupo g ON g.cod_grupo = i.cod_grupo 
                    where year(vp.fecha) = $anio  and month(vp.fecha)=$mes order by grupo asc ");
$columnasRubros = " ";
$i = 1;



$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A3');

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A2', '      ESTADO      ');

$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHeaderColumnaTipo1);

$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(2));
$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(100);

while($data = $ventasCol->fetch_assoc()){
    $columnasRubros .= "sum(case when g.descripcion='".$data['grupo']."' then (vp.total_kilos*0.001) else 0 end) '".$data['grupo']."',";


    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'2', ' '.$data['grupo'].' ');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($proximaColumna.'2')->getAlignment()->setTextRotation(90);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'3', '  TM  ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(2));

    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'2')->applyFromArray($styleHeaderArray4);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'2')->applyFromArray($styleHeaderColumna);

    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'3')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'3')->applyFromArray($styleHeaderColumnaTipo1);


    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(false);
}

$queryReporte = " SELECT s.estado,".$columnasRubros."vp.siga 
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    INNER JOIN grupo g ON g.cod_grupo = i.cod_grupo 
                    where year(vp.fecha) = $anio  and month(vp.fecha)=$mes 
                    group by s.estado  
                    order by estado asc ";
$ventas = $global->query($queryReporte);
//echo $queryReporte;exit;
$indexFila = 4;
$filaDatos = 0;

$objPHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleHeaderColumnaData2);
while($data = $ventas->fetch_assoc()){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, $data['estado']);

    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumnaData2);
    
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    
    $totalToneladas = 0;
    
    $ventasCol->data_seek(0);
    while($data2 = $ventasCol->fetch_assoc()){
        
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$data[$data2['grupo']]);
        //echo "columna: ".$data2['grupo']." valor: ".$data[$data2['grupo']]."<br>";
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData2);


        $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(false);

        $totalToneladas += $data[$data2['grupo']];
    }
    
    
    $filaDatos++;
    $indexFila++;
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$indexFila, 'TOTAL ');

$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
$ventasCol->data_seek(0);
while($data2 = $ventasCol->fetch_assoc()){
        
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        if($filaDatos != 0)
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."4:".$proximaColumna.($indexFila-1).')');
        else
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=0');
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

        $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(false);
}

$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

/*
//COMIENZA RESUMEN POR PROGRAMA
*/
" SELECT s.programa,
    sum(case when i.cesta_basica=1 then (vp.total_kilos*0.001) else 0 end) basicos,
    sum(case when i.cesta_basica=0 then (vp.total_kilos*0.001) else 0 end) no_basicos
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    INNER JOIN grupo g ON g.cod_grupo = i.cod_grupo 
                    where year(vp.fecha) = $anio and month(vp.fecha)=$mes 
                    group by s.programa  
                    order by programa asc ";exit;
$queryReporte = " SELECT s.programa,
    sum(case when i.cesta_basica=1 then (vp.total_kilos*0.001) else 0 end) basicos,
    sum(case when i.cesta_basica=0 then (vp.total_kilos*0.001) else 0 end) no_basicos
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    INNER JOIN grupo g ON g.cod_grupo = i.cod_grupo 
                    where year(vp.fecha) = $anio and month(vp.fecha)=$mes 
                    group by s.programa  
                    order by programa asc ";
$ventas = $global->query($queryReporte);
//echo $queryReporte;exit
$filaDatos = 0;
$indexFila++;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.$indexFila.':S'.$indexFila);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('J'.$indexFila, "CIERRE DE VENTAS ".$_GET['anio']);
$objPHPExcel->getActiveSheet()->getStyle("J".$indexFila.":S".($indexFila))->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("J".$indexFila.":S".($indexFila))->applyFromArray($styleHeaderColumnaTipo2);

$indexFila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.$indexFila.':M'.$indexFila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N'.$indexFila.':O'.$indexFila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('P'.$indexFila.':Q'.$indexFila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('R'.$indexFila.':S'.$indexFila);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('J'.$indexFila, "PROGRAMA ")
        ->setCellValue('N'.$indexFila, "PERTINECIENTE CESTA BAS.")
        ->setCellValue('P'.$indexFila, "NO PERTINECIENTE CESTA BAS. ")
        ->setCellValue('R'.$indexFila, "TOTAL ");

$objPHPExcel->getActiveSheet()->getStyle("J".$indexFila.":S".($indexFila))->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("J".$indexFila.":S".($indexFila))->applyFromArray($styleHeaderColumnaTipo2);



$indexFila++;
$filaIniResumen = $indexFila;
while($data = $ventas->fetch_assoc()){

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.$indexFila.':M'.$indexFila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('N'.$indexFila.':O'.$indexFila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('P'.$indexFila.':Q'.$indexFila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('R'.$indexFila.':S'.$indexFila);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('J'.$indexFila, $data['programa'])
        ->setCellValue('N'.$indexFila, $data['basicos'])
        ->setCellValue('P'.$indexFila, $data['no_basicos'])
        ->setCellValue('R'.$indexFila, "=SUM(N".$indexFila.":P".$indexFila.")");

    $indexFila++;
}

$objPHPExcel->getActiveSheet()->getStyle("J".$filaIniResumen.":S".($indexFila))->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("J".$filaIniResumen.":S".($indexFila))->applyFromArray($styleHeaderColumnaData2);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.$indexFila.':M'.$indexFila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N'.$indexFila.':O'.$indexFila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('P'.$indexFila.':Q'.$indexFila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('R'.$indexFila.':S'.$indexFila); 
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('J'.$indexFila, "TOTAL ")
        ->setCellValue('N'.$indexFila, "=SUM(N".$filaIniResumen.':O'.($indexFila-1).")")
        ->setCellValue('P'.$indexFila, "=SUM(P".$filaIniResumen.':Q'.($indexFila-1).")")
        ->setCellValue('R'.$indexFila, "=SUM(R".$filaIniResumen.':S'.($indexFila-1).")");

$objPHPExcel->getActiveSheet()->getStyle("J".$indexFila.":S".($indexFila))->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("J".$indexFila.":S".($indexFila))->applyFromArray($styleHeaderColumnaTipo2);


$objPHPExcel->getActiveSheet()->setTitle('CONSOLIDADO VENTAS'); 
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->getProtection()->setSheet(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="consolidado_de_venta.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
$objWriter->save('php://output');
exit;
?>