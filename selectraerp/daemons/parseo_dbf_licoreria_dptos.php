<?php

set_time_limit(0);

include("../../menu_sistemas/lib/common.php");

#$proveedores = new Proveedores();
#$$correlativos = new Correlativos();

$conexion = new bd("pyme_administracion");

$db = dbase_open('C:\Users\pc\Downloads\SISADMIX\DEPARTA.dbf', 0) or die("Error! No se pudo abrir el archivo de base de datos dbase");

$array = array();
#echo "T1: " . date("h:i:s") . "<br/>";

if ($db) {
    $num_registros = dbase_numrecords($db);
    $total = $num_registros - 4;
    #Dejo 5 registros sin parsear para ense�ar con ellos al usuari a cargar la data via sistema
    #Aqui comienzo por el registro 2 xq hay un cliente CONTADO que no voy a parsear
    for ($i = 1; $i <= $total; $i++) {
        $fila = dbase_get_record_with_names($db, $i);
        $array['NOMBRE'][$i] = trim($fila['NOMBRE']);
    }
}
dbase_close($db);

#$proveedores->BeginTrans();

for ($i = 1; $i <= $total; $i++) {
    if ($array['NOMBRE'][$i]!='?') {
        $sql = "INSERT INTO departamentos (`descripcion`) VALUES ('".$array['NOMBRE'][$i]."')";
        $conexion->query($sql);
        #$conexion->query("update correlativos set contador = '" . $nro_cliente . "' where campo = 'cod_producto'");
    }
}
?>
