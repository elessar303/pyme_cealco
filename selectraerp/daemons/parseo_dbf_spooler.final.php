<?php

set_time_limit(0);

include("../../menu_sistemas/lib/common.php");
include("../libs/php/clases/spooler/SpoolerConfDB.php");

$conexion = new bd("licoreria_administracion");
$spooler = new SpoolerConfDB();

$spooler->setDirDBF($dirdbf = 'C:\PRINTSPOOL\\');

$db = dbase_open($spooler->getPathDBF() /* '/home/asys/Descargas/DBF_R14/datos3.dbf' */, 0) or die("¡Error! No se pudo abrir el archivo de base de datos dbase");
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

#$sql = "UPDATE factura SET cod_factura_fiscal = ''";
#$conexion->query($sql);

for ($i = 1; $i <= $num_registros; $i++) {
    $tipo_doc_spooler = trim($array['TIPODOC'][$i]);

    #echo $f['TIPODOC'][$i]."-".$array['TIPODOC'][$i]."->".strcmp($f['TIPODOC'][$i],$array['TIPODOC'][$i])."<br/>";
    #echo $array["TIPODOC"][$i].":".strlen($array["TIPODOC"][$i]).",".var_dump($array["TIPODOC"][$i])."-".strlen("Devolucion").",".  var_dump("Devolucion")."->".strcmp($array["TIPODOC"][$i],"Devolucion")."<br/>";
    if (strcmp($tipo_doc_spooler, "Factura") == 0) {
        #echo $sql = "UPDATE factura SET cod_factura_fiscal = '" . $NUMDOC[$i] . "' WHERE id_factura = '" . $REFINT[$i] . "'";
        $sql = "UPDATE factura SET cod_factura_fiscal = '" . $array['NUMDOC'][$i] . "', nroz = '" . $array['NROZ'][$i] . "', impresora_serial = '" . $array['IMPSERIAL'][$i] . "' WHERE cod_factura = '" . $array['REFINT'][$i] . "' AND id_factura >=  '16158'";
        echo "<span style='color:green'>$i - $sql</span><br/>";
        $conexion->query($sql);
    }
    else {
        if (strcmp($array['TIPODOC'][$i], "Devolucion") == 0) {
            $sql = "UPDATE factura_devolucion SET cod_devolucion_fiscal = '" . $array['NUMDOC'][$i] . "', nroz = '" . $array['NROZ'][$i] . "', impresora_serial = '" . $array['IMPSERIAL'][$i] . "' WHERE cod_devolucion = '" . $array['REFINT'][$i] . "' AND cod_factura >=  '16158'";
            echo "<span style='color:blue'>$i - $sql</span><br/>";
            $conexion->query($sql);
        }
    }
}
?>
