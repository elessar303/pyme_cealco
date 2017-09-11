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
        'size' => 9,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:B1');
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' CONSOLIDADO AL: ')
->setCellValue('C1', ' '.$hoy .' ');


$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleHeaderArray);
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleHeaderArray2);


//$comunes = new ConexionComun();
$global = new bd("selectrapyme_central");
$queryReporte = "select s.estado,s.programa,s.descripcion,s.tipo,s.municipio,s.parroquia,
                    ".getColumnasDiasMontoPuntoVenta(str_pad($mes, 2, "0", STR_PAD_LEFT),$anio)."vp.siga 
                    from vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    where year(vp.fecha) = $anio and month(vp.fecha)=$mes 
                    group by vp.siga 
                    order by estado asc ";
//echo $queryReporte;exit;
//$queryReporte = "select * from vcajero";
//$array_factura = $comunes->ObtenerFilasBySqlSelect($queryReporte);

$ventas = $global->query($queryReporte);

//print_r($fila['estado']);exit;
/*
    COLUMNAS DEL REPORTE
*/
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A3', '      ESTADO      ')
->setCellValue('B3', '       PROGRAMA       ')
->setCellValue('C3', '               PUNTO              ')
->setCellValue('D3', '      TIPO      ')
->setCellValue('E3', '   MUNICIPIO   ')
->setCellValue('F3', '   PARROQUIA   ');

$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleHeaderColumnaTipo1);


$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());
$columnaGrupo1 = "";
$columnaGrupo2 = "";
$columnaGrupo3 = "";

//echo $ultimaColumna." ----- ".PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn())." -------------- ".PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);;


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setIndent(1);

for($i=1;$i <= $diasMes;$i++){
    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'1', ' '.getDiaSemanaFecha($anio.'-'.str_pad($mes, 2, "0", STR_PAD_LEFT).'-'.str_pad($i, 2, "0", STR_PAD_LEFT)).' ');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'2', ' '.str_pad($i, 2, "0", STR_PAD_LEFT).'-'.mesaletras($mes).' ');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'3', ' UND ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());
    $columnaGrupo1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'3', ' TM ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());
    $columnaGrupo2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'3', ' Bs. ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());
    $columnaGrupo3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($columnaGrupo1.'1:'.$columnaGrupo3.'1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($columnaGrupo1.'2:'.$columnaGrupo3.'2');


    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo1.'1')->applyFromArray($styleHeaderArray3);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo1.'1')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo1.'2')->applyFromArray($styleHeaderArray4);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo1.'2')->applyFromArray($styleHeaderColumna);

    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo1.'3')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo2.'3')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo3.'3')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo1.'3')->applyFromArray($styleHeaderColumnaTipo1);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo2.'3')->applyFromArray($styleHeaderColumnaTipo1);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo3.'3')->applyFromArray($styleHeaderColumnaTipo1);


    $objPHPExcel->getActiveSheet()->getColumnDimension($columnaGrupo1)->setAutoSize(false);
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnaGrupo2)->setAutoSize(false);
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnaGrupo3)->setAutoSize(false);
}

    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell('A3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth($width);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell('B3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth($width);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell('C3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth($width);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell('D3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth($width);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell('E3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth($width);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell('F3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth($width);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'3', '    TOTAL UND    ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());
    $columnaGrupo1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell($proximaColumna.'3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setWidth($width);


    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'3', '    TOTAL TM    ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());
    $columnaGrupo2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell($proximaColumna.'3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setWidth($width);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'3', '    TOTAL Bs.    ');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());
    $columnaGrupo3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell($proximaColumna.'3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setWidth($width);


    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo1.'3')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo2.'3')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo3.'3')->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo1.'3')->applyFromArray($styleHeaderColumnaTipo1);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo2.'3')->applyFromArray($styleHeaderColumnaTipo1);
    $objPHPExcel->getActiveSheet()->getStyle($columnaGrupo3.'3')->applyFromArray($styleHeaderColumnaTipo1);

    $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(-1);

$indexFila = 4;
$mesField = str_pad($mes, 2, "0", STR_PAD_LEFT);
$estadoIni = "------";

$totalEstadoBs = 0;
$totalEstadoToneladas = 0;
$totalEstadoUnidades = 0;



$filaDatos = 0;
while($data = $ventas->fetch_assoc()){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, $data['estado'])
        ->setCellValue('B'.$indexFila, $data['programa'])
        ->setCellValue('C'.$indexFila, $data['descripcion'])
        ->setCellValue('D'.$indexFila, $data['tipo'])
        ->setCellValue('E'.$indexFila, $data['municipio'])
        ->setCellValue('F'.$indexFila, $data['parroquia']);

    
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    //echo $ultimaColumna;exit;
    $totalBs = 0;
    $totalToneladas = 0;
    $totalUnidades = 0;

    for($i=1;$i <= $diasMes;$i++){
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        
        if($i==1)
            $columnaEstiloTotales = $proximaColumna;

        $diaField = str_pad($i, 2, "0", STR_PAD_LEFT);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$data['unidades_'.$anio.$mesField.$diaField], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$data['toneladas_'.$anio.$mesField.$diaField], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$data['total_'.$anio.$mesField.$diaField], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        $totalBs += $data['total_'.$anio.$mesField.$diaField];
        $totalToneladas += $data['toneladas_'.$anio.$mesField.$diaField];
        $totalUnidades += $data['unidades_'.$anio.$mesField.$diaField];
    }

    $totalEstadoBs += $totalBs;
    $totalEstadoToneladas += $totalToneladas;
    $totalEstadoUnidades += $totalUnidades;
    
    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$totalUnidades, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $columnaGrupo1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);


    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$totalToneladas, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $columnaGrupo2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$totalBs, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $columnaGrupo3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

    $objPHPExcel->getActiveSheet()->getStyle('A'.$indexFila.':'.$columnaGrupo3.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$indexFila.':F'.$indexFila)->applyFromArray($styleHeaderColumnaData);
    $objPHPExcel->getActiveSheet()->getStyle($columnaEstiloTotales.$indexFila.':'.$columnaGrupo3.$indexFila)->applyFromArray($styleHeaderColumnaData2);

    $ventas->data_seek($filaDatos+1);
    $row = $ventas->fetch_row();
    //echo $data['estado']." --- ".$row[0]."**";
    if($row[0] != $data['estado']){
        $indexFila++;
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());

        $columnaTotalEstado1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);
        $columnaTotalEstado2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-2);
        $columnaTotalEstado3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-3);
        $columnaTotalEstado4 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-4);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnaTotalEstado1.$indexFila, "=".$totalEstadoBs, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnaTotalEstado2.$indexFila, "=".$totalEstadoToneladas, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnaTotalEstado3.$indexFila, "=".$totalEstadoUnidades, PHPExcel_Cell_DataType::TYPE_NUMERIC);

        $objPHPExcel->getActiveSheet()->getStyle('A'.$indexFila.':'.$columnaTotalEstado4.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$indexFila.':'.$columnaTotalEstado4.$indexFila)->applyFromArray($styleHeaderColumnaTipo1);
        
        $objPHPExcel->getActiveSheet()->getStyle($columnaTotalEstado3.$indexFila.':'.$columnaTotalEstado1.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($columnaTotalEstado3.$indexFila.':'.$columnaTotalEstado1.$indexFila)->applyFromArray($styleHeaderColumnaData3);

        $totalEstadoBs = 0;
        $totalEstadoToneladas = 0;
        $totalEstadoUnidades = 0;
    }
    $ventas->data_seek($filaDatos);
    $row = $ventas->fetch_row();
    
    $filaDatos++;
    $indexFila++;
}


$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".$indexFila, "TOTAL VENTAS DEL DIA PRODUCTOS PUNTOS DE VENTA");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("C".$indexFila.':'."F".$indexFila);

$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

for($i=1;$i <= $diasMes;$i++){
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        
        if($i==1)
            $columnaEstiloTotales = $proximaColumna;

        $diaField = str_pad($i, 2, "0", STR_PAD_LEFT);
        //echo $proximaColumna.$indexFila;exit;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna."4:".$proximaColumna.($indexFila-1).")");
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna."4:".$proximaColumna.($indexFila-1).")");
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna."4:".$proximaColumna.($indexFila-1).")");
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        //$totalBs += $data['total_'.$anio.$mesField.$diaField];
        //$totalToneladas += $data['toneladas_'.$anio.$mesField.$diaField];
        //$totalUnidades += $data['unidades_'.$anio.$mesField.$diaField];
}
$objPHPExcel->getActiveSheet()->getStyle("C".$indexFila.':'.$columnaGrupo3.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("C".$indexFila.':'.$columnaGrupo3.$indexFila)->applyFromArray($styleHeaderColumnaData4);

$indexFila++;

$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".$indexFila, "RESUMEN DE LAS VENTAS DIARIAS POR PROGRAMA");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("C".$indexFila.':'."F".$indexFila);
$objPHPExcel->getActiveSheet()->getStyle("C".$indexFila)->applyFromArray($styleHeaderColumnaData);

$indexFila++;
//$objPHPExcel->getActiveSheet()->getStyle('G4:CX9')->getNumberFormat()->setFormatCode('#,##');

//-----------------------------------------------------
//-----------------------------------------------------
// COMIENZA EL RESUMEN POR PROGRAMA
//-----------------------------------------------------
//-----------------------------------------------------
$queryReporte = "select s.tipo,s.programa,
                    ".getColumnasDiasMontoPuntoVenta(str_pad($mes, 2, "0", STR_PAD_LEFT),$anio)." 'resumen' tipoq 
                    from vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    where year(vp.fecha) = $anio and month(vp.fecha)=$mes 
                    group by s.tipo 
                    order by programa asc ";

$ventas = $global->query($queryReporte);

$totalEstadoBs = 0;
$totalEstadoToneladas = 0;
$totalEstadoUnidades = 0;



$filaDatos = 0;
$filaIniciaPrograma = $indexFila;
while($data = $ventas->fetch_assoc()){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('C'.$indexFila, $data['tipo'])
        ->setCellValue('D'.$indexFila, "")
        ->setCellValue('E'.$indexFila, "")
        ->setCellValue('F'.$indexFila, "");

    
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    //echo $ultimaColumna;exit;
    $totalBs = 0;
    $totalToneladas = 0;
    $totalUnidades = 0;

    for($i=1;$i <= $diasMes;$i++){
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        
        if($i==1)
            $columnaEstiloTotales = $proximaColumna;

        $diaField = str_pad($i, 2, "0", STR_PAD_LEFT);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$data['unidades_'.$anio.$mesField.$diaField], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$data['toneladas_'.$anio.$mesField.$diaField], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$data['total_'.$anio.$mesField.$diaField], PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $columnaGrupo3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

        $totalBs += $data['total_'.$anio.$mesField.$diaField];
        $totalToneladas += $data['toneladas_'.$anio.$mesField.$diaField];
        $totalUnidades += $data['unidades_'.$anio.$mesField.$diaField];
    }

    $totalEstadoBs += $totalBs;
    $totalEstadoToneladas += $totalToneladas;
    $totalEstadoUnidades += $totalUnidades;
    
    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$totalUnidades, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $columnaGrupo1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);


    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$totalToneladas, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $columnaGrupo2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=".$totalBs, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    $columnaGrupo3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);

    $objPHPExcel->getActiveSheet()->getStyle('C'.$indexFila.':'.$columnaGrupo3.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$indexFila.':F'.$indexFila)->applyFromArray($styleHeaderColumnaData);
    $objPHPExcel->getActiveSheet()->getStyle($columnaEstiloTotales.$indexFila.':'.$columnaGrupo3.$indexFila)->applyFromArray($styleHeaderColumnaData2);

    $ventas->data_seek($filaDatos+1);
    $row = $ventas->fetch_row();
    //echo $data['estado']." --- ".$row[0]."**";
    if($row[1] != $data['programa']){
        $indexFila++;
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn());

        $columnaTotalEstado = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
        $columnaTotalEstado1 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-1);
        $columnaTotalEstado2 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-2);
        $columnaTotalEstado3 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-3);
        $columnaTotalEstado4 = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna-4);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("C".$indexFila, "TOTAL ".$data['programa'])
            ->setCellValue("D".$indexFila, "")
            ->setCellValue("E".$indexFila, "")
            ->setCellValue("F".$indexFila, "");

        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        for($i=1;$i <= $diasMes;$i++){
            $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna.$filaIniciaPrograma.":".$proximaColumna.($indexFila-1).")", PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

            $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna.$filaIniciaPrograma.":".$proximaColumna.($indexFila-1).")", PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

            $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, "=SUM(".$proximaColumna.$filaIniciaPrograma.":".$proximaColumna.($indexFila-1).")", PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        }

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnaTotalEstado1.$indexFila, "=".$totalEstadoBs, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnaTotalEstado2.$indexFila, "=".$totalEstadoToneladas, PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnaTotalEstado3.$indexFila, "=".$totalEstadoUnidades, PHPExcel_Cell_DataType::TYPE_NUMERIC);

        $objPHPExcel->getActiveSheet()->getStyle('C'.$indexFila.':'.$columnaTotalEstado1.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$indexFila.':'.$columnaTotalEstado1.$indexFila)->applyFromArray($styleHeaderColumnaTipo2);
        
        $objPHPExcel->getActiveSheet()->getStyle($columnaTotalEstado3.$indexFila.':'.$columnaTotalEstado1.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($columnaTotalEstado3.$indexFila.':'.$columnaTotalEstado1.$indexFila)->applyFromArray($styleHeaderColumnaData3);

        $totalEstadoBs = 0;
        $totalEstadoToneladas = 0;
        $totalEstadoUnidades = 0;
    }
    $ventas->data_seek($filaDatos);
    $row = $ventas->fetch_row();
    
    $filaDatos++;
    $indexFila++;
}



$objPHPExcel->getActiveSheet()->setTitle('PUNTOS DE VENTAS'); 
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->getProtection()->setSheet(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="puntos_de_venta.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
$objWriter->save('php://output');
exit;
?>