<?php

include("../../menu_sistemas/lib/common.php");
include("../libs/php/clases/spooler/SpoolerConfDB.php");

$conexion = new bd("pyme_administracion_r14_18042012");

$spooler = new SpoolerConfDB();

$spooler->setDirDBF($dirdbf = '/home/asys/Descargas/DBF_R14/');
$spooler->setNameDBF($namedbf = 'datos3.dbf');
$dbf_path = $spooler->getPathDBF();

$db = dbase_open($dbf_path /* '/home/asys/Descargas/DBF_R14/datos3.dbf' */, 0) or die("¡Error! No se pudo abrir el archivo de base de datos dbase");
#$db = dbase_open('C:\AppServ\www\dbase\bbdd\datos3.dbf', 0) or die("Error! No se pudo abrir el archivo de base de datos dbase");
$array = array();

if ($db) {
    $num_registros = dbase_numrecords($db);
    for ($i = 1; $i <= $num_registros; $i++) {
        $fila = dbase_get_record_with_names($db, $i);
        $array['REFINT'][$i] = $fila['REFINT'];
        $array['NUMDOC'][$i] = $fila['NUMDOC'];
        $array['TIPODOC'][$i] = $fila['TIPODOC'];
        $array['FSTATUS'][$i] = $fila['FSTATUS'];
        $array['NROZ'][$i] = $fila['NROZ'];
        $array['IMPSERIAL'][$i] = $fila['IMPSERIAL'];
        /* $REFINT[$i] = (string) $fila['REFINT'];
          $NUMDOC[$i] = (string) $fila['NUMDOC'];
          $TIPODOC[$i] = (string) $fila['TIPODOC'];
          $FSTATUS[$i] = (string) $fila['FSTATUS']; */
    }
}
dbase_close($db);

$sql = "UPDATE factura SET cod_factura_fiscal = ''";
$conexion->query($sql);
$f = array();
$f = "Factura   ";
#$tipo_doc=$f['TIPODOC'][1];

for ($i = 1; $i <= $num_registros; $i++) {
    $tipo_doc_spooler = $array['TIPODOC'][$i];

    #echo $f['TIPODOC'][$i]."-".$array['TIPODOC'][$i]."->".strcmp($f['TIPODOC'][$i],$array['TIPODOC'][$i])."<br/>";
    #echo strlen($TIPODOC[$i]).",".var_dump($TIPODOC[$i])."-".strlen($f).",".  var_dump($f)."->".strcmp($TIPODOC[$i],$f)."<br/>";
    if (strcmp($array['TIPODOC'][$i], "Factura   ") == 0) {
        #echo $sql = "UPDATE factura SET cod_factura_fiscal = '" . $NUMDOC[$i] . "' WHERE id_factura = '" . $REFINT[$i] . "'";
        echo $i . " - " . $sql = "UPDATE factura SET cod_factura_fiscal = '" . $array['NUMDOC'][$i] . "', nroz = '" . $array['NROZ'][$i] . "', impresora_serial = '" . $array['IMPSERIAL'][$i] . "' WHERE id_factura = '" . $array['REFINT'][$i] . "'";
        echo "<br/>";
        $conexion->query($sql);
    }
}
?>