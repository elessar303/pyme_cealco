<?php

if ($_GET["generar"] == "si") {
    $parametros = new ParametrosGenerales();
    $parametros_impresora_fiscal = $parametros->ObtenerFilasBySqlSelect("SELECT tipo_facturacion, swterceroimp FROM parametros_generales;");
    if ($parametros_impresora_fiscal[0]['tipo_facturacion'] == 1) {
        if ($parametros_impresora_fiscal[0]['swterceroimp'] == 1) {
            $directorio = "C:\FACTURAS\\";
            $nombre_archivo_spooler = "Selectra.001";
            $ruta = $directorio . $nombre_archivo_spooler;
            $archivo_spooler = fopen($ruta, "w");
            chmod($directorio, 0777);
            chmod($ruta, 0777);
            fwrite($archivo_spooler, "TIPO>Z</TIPO");
            fclose($archivo_spooler);
        } elseif ($parametros_impresora_fiscal[0]['swterceroimp'] == 0) {

            include ("../../libs/php/clases/tfhka/TfhkaPHP.php");
            $itObj = new Tfhka();
            $itObj->SenCmd("I0Z");
        }
    }
    header("Location: index.php?opt_menu=106");
}
?>
