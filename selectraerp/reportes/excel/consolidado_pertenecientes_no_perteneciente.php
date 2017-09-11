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
$tipo_producto = $_GET['tipo_producto'];//basico,no_basico,ambos

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
    //'fill' => array(
      //      'type' => PHPExcel_Style_Fill::FILL_SOLID,
            //'color' => array('rgb' => '3E7DAB')
    //),
    'font' => array(
        'bold' => true,
        'size' => 10,
        'name' => 'Arial',
        //'color' => array('argb' => 'FFFFFFFF')
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);
$styleHeaderColumnaTipo2 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '89BAFA')
    ),
    'font' => array(
        'bold' => true,
        'size' => 10,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);
$styleHeaderColumnaTipo3 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'EFFA89')
    ),
    'font' => array(
        'bold' => true,
        'size' => 10,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);
$styleHeaderColumnaTipo4 = array(
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'F2F218')
    ),
    'font' => array(
        'bold' => true,
        'size' => 10,
        'name' => 'Arial'
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    )
);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:N2');

if($tipo_producto=='basico'){
  $tit = "";  
  $tit2 = "";
  $tit3 = "";
}
elseif ($tipo_producto=='no_basico') {
    $tit = 'NO';
    $tit2 = "no";
     $tit3 = "NO";
}
else{
    $tit= 'PERTENECIENTES Y NO';
    $tit2= "pertenecientes_y_no";
    $tit3 = "\nPERTENECIENTES Y NO";
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', ' CONSOLIDADO DE VENTAS DE PRODUCTOS '.$tit.' PERTENECIENTES  A LA CESTA BASICA EN TN POR TIPO DE ESTABLECIMIENTO: ')
->setCellValue('A2', ' ACUMULADO AL: '.$hoy);


$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleHeaderArray);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHeaderArray);


//$comunes = new ConexionComun();
$global = new bd("selectrapyme_central");

if($tipo_producto=="basico"){
    $whereBasica = " and i.cesta_basica=1";
}
elseif($tipo_producto=="no_basico"){
    $whereBasica =  " and i.cesta_basica=0";   
}
else{
    $whereBasica =  "";   
}

/*
$ventasCol = $global->query("SELECT distinct  s.tipo_red,s.tipo
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    INNER JOIN grupo g ON g.cod_grupo = i.cod_grupo 
                    where year(vp.fecha) = $anio and s.tipo_red='directa' and s.programa='RED COMERCIAL'  ".$whereBasica." 
                    union all(
                        SELECT distinct  s.tipo_red,s.tipo
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    INNER JOIN grupo g ON g.cod_grupo = i.cod_grupo 
                    where year(vp.fecha) = $anio and s.tipo_red='indirecta' and s.programa='RED COMERCIAL'  ".$whereBasica."
                    )
                    union all(
                        SELECT distinct  'PROGRAMA ESPECIAL' tipo_red,s.tipo
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    INNER JOIN grupo g ON g.cod_grupo = i.cod_grupo 
                    where year(vp.fecha) = $anio and s.programa='PROGRAMA ESPECIAL'  ".$whereBasica."
                    )
                    order by tipo_red,tipo asc ");
*/

$ventasCol = $global->query("SELECT distinct  s.tipo_red,s.tipo
                    FROM  siga s 
                    where s.tipo_red='directa' and s.programa='RED COMERCIAL' 
                    union all(
                        SELECT distinct  s.tipo_red,s.tipo
                    FROM  siga s 
                    where s.tipo_red='indirecta' and s.programa='RED COMERCIAL'  
                    )
                    union all(
                        SELECT distinct  'PROGRAMA ESPECIAL' tipo_red,s.tipo
                    FROM  siga s 
                    where s.programa='PROGRAMA ESPECIAL'  
                    )
                    order by tipo_red,tipo asc ");
$columnasRed = " ";
$i = 1;


$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(30);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:A6');

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A3', '       ESTADO       ');

$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleHeaderColumnaTipo1);

$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
//$objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(100);

$filaDatos=0;
$i=0;
$colIniTipoRed = "B";
while($data = $ventasCol->fetch_assoc()){
    $columnasRed .= "sum(case when s.tipo='".$data['tipo']."' then (vp.total_kilos*0.001) else 0 end) '".$data['tipo']."',";


    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'5', '    '.$data['tipo'].'    ');
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->getAlignment()->setWrapText(true);
    //$objPHPExcel->setActiveSheetIndex(0)->getStyle($proximaColumna.'2')->getAlignment()->setTextRotation(90);
    //$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'3', '  TM  ');
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell($proximaColumna.'5')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setWidth($width);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));

    if($data['tipo_red'] == "directa"){
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo2);
    }
    elseif($data['tipo_red'] == "indirecta"){
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo3);
    }
    else{        
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'5')->applyFromArray($styleHeaderColumnaTipo4);
    }
    
    
    $ventasCol->data_seek($filaDatos+1);
    $row = $ventasCol->fetch_row();
    if($row[0] != $data['tipo_red']){
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells($colIniTipoRed.'4:'.$proximaColumna.'4');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells($colIniTipoRed.'6:'.$proximaColumna.'6');
        if($data['tipo_red']=="directa"){
            $tipoRedText = "RED DIRECTA";
        }
        elseif($data['tipo_red']=="indirecta"){
            $tipoRedText = "RED INDIRECTA";
        }
        else{
            $tipoRedText = $data['tipo_red'];
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colIniTipoRed.'4', '  '.$tipoRedText.'  ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colIniTipoRed.'6', '     TN     ');
        if($data['tipo_red'] == "directa"){
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'4')->applyFromArray($styleHeaderColumna);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'4')->applyFromArray($styleHeaderColumnaTipo2);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'6')->applyFromArray($styleHeaderColumna);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'6')->applyFromArray($styleHeaderColumnaTipo2);
        }
        elseif($data['tipo_red'] == "indirecta"){
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'4')->applyFromArray($styleHeaderColumna);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'4')->applyFromArray($styleHeaderColumnaTipo3);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'6')->applyFromArray($styleHeaderColumna);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'6')->applyFromArray($styleHeaderColumnaTipo3);
        }
        else{        
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'4')->applyFromArray($styleHeaderColumna);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'4')->applyFromArray($styleHeaderColumnaTipo4);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'6')->applyFromArray($styleHeaderColumna);
            $objPHPExcel->getActiveSheet()->getStyle($colIniTipoRed.'6')->applyFromArray($styleHeaderColumnaTipo4);
        }
        
        $colIniTipoRed = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
    }
    $ventasCol->data_seek($filaDatos);
    $row = $ventasCol->fetch_row();

    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

    $filaDatos++;
}
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:'.$proximaColumna.'3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', ' RED COMERCIAL ');
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleHeaderColumnaTipo1);


$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$colFinGrupo = $proximaColumna;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells($proximaColumna.'4:'.$proximaColumna.'5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'4', "TOTAL\nVENTAS");
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->getAlignment()->setWrapText(true);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell($proximaColumna.'4')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setWidth($width);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'6', '         TN         ');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'6')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'6')->applyFromArray($styleHeaderColumnaTipo1);
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);


$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells($proximaColumna.'4:'.$proximaColumna.'5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'4', "RED\nCOMERCIAL");
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->getAlignment()->setWrapText(true);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell($proximaColumna.'4')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setWidth($width);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'6', '         TN         ');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->applyFromArray($styleHeaderColumnaTipo4);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'6')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'6')->applyFromArray($styleHeaderColumnaTipo4);
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);


$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells($proximaColumna.'4:'.$proximaColumna.'5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'4', "PROGRAMAS\nSOCIALES");
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->getAlignment()->setWrapText(true);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell($proximaColumna.'4')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setWidth($width);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.'6', '         TN         ');
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn(5));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'4')->applyFromArray($styleHeaderColumnaTipo4);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'6')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.'6')->applyFromArray($styleHeaderColumnaTipo4);
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);


$objPHPExcel->setActiveSheetIndex(0)->mergeCells($colFinGrupo.'3:'.$proximaColumna.'3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colFinGrupo.'3', "    PRODUCTOS ".$tit3." PERTENECIENTES A LA CESTA BASICA    ");
$objPHPExcel->getActiveSheet()->getStyle($colFinGrupo.'3')->getAlignment()->setWrapText(true);
    $valorColumnaA =  $objPHPExcel->getActiveSheet()->getCell($colFinGrupo.'3')->getValue();
    $width = mb_strwidth ($valorColumnaA); //Return the width of the string
    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setWidth($width);
$objPHPExcel->getActiveSheet()->getStyle($colFinGrupo.'3')->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle($colFinGrupo.'3')->applyFromArray($styleHeaderColumnaTipo1);




$queryReporte = " SELECT s.estado,".$columnasRed."vp.siga 
                    FROM  vproducto vp 
                    inner join siga s on s.siga = vp.siga 
                    INNER JOIN item i ON i.cod_item = vp.referencia
                    INNER JOIN grupo g ON g.cod_grupo = i.cod_grupo 
                    where year(vp.fecha) = $anio  ".$whereBasica."
                    group by s.estado  
                    order by estado asc ";
                    //echo $queryReporte;exit;
$ventas = $global->query($queryReporte);
//echo $queryReporte;exit;
$indexFila = 7;
$filaDatos = 0;

$objPHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleHeaderColumnaData2);
while($data = $ventas->fetch_assoc()){

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$indexFila, $data['estado']);

    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumnaData2);
    
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
    
    $totalRedComercial = 0;
    $totalProgramas = 0;
    
    $ventasCol->data_seek(0);
    while($data2 = $ventasCol->fetch_assoc()){
        
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$data[$data2['tipo']]);
        //echo "columna: ".$data2['grupo']." valor: ".$data[$data2['grupo']]."<br>";
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $ultimacolumnadatos = $ultimaColumna;
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData2);


        $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

        if($data2['tipo_red'] == "directa" || $data2['tipo_red'] == "indirecta"){
            $totalRedComercial += $data[$data2['tipo']];
        }
        else{
            $totalProgramas += $data[$data2['tipo']];
        }
        $totalToneladas += $data[$data2['tipo']];
    }
    
    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM(B'.$indexFila.":".$ultimacolumnadatos.$indexFila.')');
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData2);

    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$totalRedComercial);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData2);

    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

    $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '='.$totalProgramas);
    $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));

    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
    $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaData2);

    $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
    
    
    $filaDatos++;
    $indexFila++;
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$indexFila, 'TOTAL ');
$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila)->applyFromArray($styleHeaderColumna);

$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
$ventasCol->data_seek(0);
while($data2 = $ventasCol->fetch_assoc()){
        
        $proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);

        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."7:".$proximaColumna.($indexFila-1).')');
        if($filaDatos==0)
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=0');
        else
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."7:".$proximaColumna.($indexFila-1).')');
        
        $ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
        $objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
        $objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);
}
$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
if($filaDatos==0)
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=0');
else
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."7:".$proximaColumna.($indexFila-1).')');
        
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
if($filaDatos==0)
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=0');
else
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."7:".$proximaColumna.($indexFila-1).')');
        
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);

$proximaColumna = PHPExcel_Cell::stringFromColumnIndex($ultimaColumna);
if($filaDatos==0)
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=0');
else
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($proximaColumna.$indexFila, '=SUM('.$proximaColumna."7:".$proximaColumna.($indexFila-1).')');
        
$ultimaColumna = PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn($indexFila));
$objPHPExcel->getActiveSheet()->getStyle($proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getColumnDimension($proximaColumna)->setAutoSize(true);



//$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumna);
$objPHPExcel->getActiveSheet()->getStyle("A".$indexFila.":".$proximaColumna.$indexFila)->applyFromArray($styleHeaderColumnaTipo3);



$objPHPExcel->getActiveSheet()->setTitle('CONSOLIDADO PERT NO PERT'); 
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex(0)->getProtection()->setSheet(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="consolidado_de_venta_'.$tit2.'_pertenecientes.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
$objWriter->save('php://output');
exit;
?>