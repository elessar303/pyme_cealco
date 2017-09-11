<?php

set_time_limit(0);

include("../../menu_sistemas/lib/common.php");

$conexion = new bd("licoreria_administracion");

#$db = dbase_open('/home/asys/Descargas/DBF_R14/datos3.dbf', 0) or die("¡Error! No se pudo abrir el archivo de base de datos dbase");
$db = dbase_open('C:\PRINTSPOOL\datos3.dbf', 0) or die("Error! No se pudo abrir el archivo de base de datos dbase");
$array = array();

if ($db) {
    $num_registros = dbase_numrecords($db);
    for ($i = 1; $i <= $num_registros; $i++) {
        $fila = dbase_get_record_with_names($db, $i);
        $array['REFINT'][$i] = $fila['REFINT'];
        $array['NUMDOC'][$i] = $fila['NUMDOC'];
        $array['TIPODOC'][$i] = $fila['TIPODOC'];
        $array['FSTATUS'][$i] = $fila['FSTATUS'];
    }
}
dbase_close($db);

for ($i = 1; $i <= $num_registros; $i++) {
    #echo "$i REFINT: " . $array['REFINT'][$i] . " - NUMDOC: " . $array['NUMDOC'][$i] . " - TIPODOC: " . $array['TIPODOC'][$i] . " - FSTATUS: " . $array['FSTATUS'][$i] . " <br/>";
    echo $sql = "UPDATE factura SET cod_factura_fiscal = '" . $array['NUMDOC'][$i] . "' WHERE id_factura = '" . $array['REFINT'][$i] . "'";
    echo "<br/>";
    $conexion->query($sql);
}
?>