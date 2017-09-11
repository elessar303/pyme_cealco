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
$tipo_red = $_GET['tipo_red'];

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
    ->setCellValue('A1', ' RESUMEN RED '.strtoupper($tipo_red).' AL MES DE '.strtoupper(mesaletras($mes)));


$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleHeaderArray);


//$comunes = new ConexionComun();
$global = new bd("selectrapyme_central");



$ventasCol = $global->query("SELECT distinct tipo 
                    from siga s 
                    where tipo_red='".$tipo_red."'  order by tipo asc ");
$columnas = " ";
$i = 1;



$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A3');

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A2', '      TIPO RED      ')
->setCellValue('A4', '      TIPO PUNTO      ')
->setCellValue('A5', '      ESTADO      ')
->setCellValue('B2', '      RED '.strtoupper($tipo_red).'      ')
->setCellValue('B3', '      '.(($tipo_red=="directa")?"":"NO").' ADMINISTRADA POR PDVAL      ');

$objPHPExcel->getActiveSheet()->getStyle('A4:A5')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A4:A5')->applyFromArray($styleHeaderColumnaTipo3);


$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(4));

while($data = $ventasCol->fetch_assoc()){
    $columnas .= "sum(case when tipo='".$data['tipo']."' and status='Activo' then 1 else 0 end) '".$data['tipo']."_activo',";
    $columnas .= "sum(case when tipo='".$data['tipo']."' and status='Inactivo' then 1 else 0 end) '".$data['tipo']."_inactivo',";
    $columnas .= "sum(case when tipo='".$data['tipo']."' and status='Cerrado' then 1 else 0 end) '".$data['tipo']."_cerrado',";


    //$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    //$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'4', ' '.$data['tipo'].' ');
    //$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $colIniGrupo = $proximaColumna;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'5', '     ACTIVOS     ');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'4', ' '.$data['tipo'].' ');
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->applyFromArray($styleHeaderColumnaTipo3);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo3);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'5', '     INACTIVOS     ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo3);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'5', '     CERRADOS     ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo3);

    
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($colIniGrupo.'4:'.$proximaColumna.'4');

    


    //$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
//$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
//$colIniGrupo = $proximaColumna;
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'4', ' SUB-TOTAL RED ');
//$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());

$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$colIniGrupo = $proximaColumna;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'5', '     ACTIVOS     ');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'4', ' SUB-TOTAL RED ');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo3);


$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'5', '     INACTIVOS     ');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo3);

$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'5', '     CERRADOS     ');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo3);


$objPHPExcel->setActiveSheetIndex(0)->mergeCells($colIniGrupo.'4:'.$proximaColumna.'4');

$objPHPExcel->getActiveSheet()->getStyle($colIniGrupo.'4')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($colIniGrupo.'4')->applyFromArray($styleHeaderColumnaTipo3);
    


$objPHPExcel->getActiveSheet()->getStyle($colIniGrupo.'4:'.$proximaColumna.'5')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($colIniGrupo.'4:'.$proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo1);


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:'.$proximaColumna.'2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:'.$proximaColumna.'3');


$objPHPExcel->getActiveSheet()->getStyle('B2:'.$proximaColumna.'2')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('B2:'.$proximaColumna.'2')->applyFromArray($styleHeaderColumnaTipo5);

$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHeaderColumnaTipo4);
$objPHPExcel->getActiveSheet()->getStyle('B3:'.$proximaColumna.'3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('B3:'.$proximaColumna.'3')->applyFromArray($styleHeaderColumnaTipo4);





$queryReporte = " SELECT s.estado,".$columnas."s.siga 
                    FROM  siga s 
                    where tipo_red='".$tipo_red."' 
                    group by s.estado  
                    order by estado asc,tipo asc ";
//echo $queryReporte;exit;
$ventas = $global->query($queryReporte);
$indexFila = 6;
$filaDatos = 0;

$objPHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleHeaderColumnaData);
while($data = $ventas->fetch_assoc()){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, $data['estado']);

    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumnaData2);
    
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    
    $totalEstadoActivo = 0;
    $totalEstadoInactivo = 0;
    $totalEstadoCerrado = 0;
    
    $ventasCol->data_seek(0);
    while($data2 = $ventasCol->fetch_assoc()){
        
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $colIniData = $proximaColumna;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$data[$data2['tipo'].'_activo']);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$data[$data2['tipo'].'_inactivo']);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$data[$data2['tipo'].'_cerrado']);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));


        $objPHPExcel->getActiveSheet()->getStyle($colIniData.$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($colIniData.$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData2);


        //$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(false);

        $totalEstadoActivo += $data[$data2['tipo'].'_activo'];
        $totalEstadoInactivo += $data[$data2['tipo'].'_inactivo'];
        $totalEstadoCerrado += $data[$data2['tipo'].'_cerrado'];
    }
    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $colIniData = $proximaColumna;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$totalEstadoActivo);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$totalEstadoInactivo);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$totalEstadoCerrado);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

    $objPHPExcel->getActiveSheet()->getStyle($colIniData.$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($colIniData.$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData2);
    
    
    $filaDatos++;
    $indexFila++;
}

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, "SUB-TOTAL");

$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

$ventasCol->data_seek(0);
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
while($data2 = $ventasCol->fetch_assoc()){
        
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $colIniData = $proximaColumna;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."6:".$proximaColumna.($indexFila-1).')');
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."6:".$proximaColumna.($indexFila-1).')');
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."6:".$proximaColumna.($indexFila-1).')');
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);
        
        $totalEstadoActivo += $data[$data2['tipo'].'_activo'];
        $totalEstadoInactivo += $data[$data2['tipo'].'_inactivo'];
        $totalEstadoCerrado += $data[$data2['tipo'].'_cerrado'];
}

$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$colIniData = $proximaColumna;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."6:".$proximaColumna.($indexFila-1).')');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."6:".$proximaColumna.($indexFila-1).')');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);

$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."6:".$proximaColumna.($indexFila-1).')');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);
//$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
//$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);


$indexFila++;


$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, "TOTAL");

$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumnaTipo3);


$ventasCol->data_seek(0);
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
while($data2 = $ventasCol->fetch_assoc()){
        
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $colIniData = $proximaColumna;
        $colFinData = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna+2);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells($colIniData.$indexFila.':'.$colFinData.$indexFila);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna.($indexFila-1).":".$colFinData.($indexFila-1).')');
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo3);

}

$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$colIniData = $proximaColumna;
$colFinData = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna+2);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells($colIniData.$indexFila.':'.$colFinData.$indexFila);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna.($indexFila-1).":".$colFinData.($indexFila-1).')');


$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo3);












$objPHPExcel->getActiveSheet()->setTitle('RESUMEN RED '.strtoupper($tipo_red)); 
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->getProtection()->setSheet(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="resumen_red_'.$tipo_red.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
$objWriter->save('php://output');
exit;
?>