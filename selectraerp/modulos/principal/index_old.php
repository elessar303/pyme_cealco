<?php

/*
 * Pagina Principal del sistema de facturacion.
 */

require_once("../../config.ini.php");
# La siguiente linea comentada sobra
#require_once(RAIZ_PROYECTO . "/libs/php/clases/login.php");

$login = new Login();

if (isset($_GET["logout"])) {

    $javascript1 = "<script> location = '../../../'; </script>";
    $smarty->assign("javascript1", $javascript1);
    $login->logout();
    $smarty->display("salida.tpl");
    exit();
}
if ($login->validarLoginON() != 1) {
    header("Location: ../sesion/");
}

$menu = new Menu();
$parametrosgenerales = new ParametrosGenerales();




if (isset($_GET["opt_menu"])) {
    if (!is_numeric($_GET["opt_menu"])) {
        header("Location: ?opt_menu=54");
    }
} else {
    header("Location: ?opt_menu=54");
}

if (isset($_GET["opt_menu"]) && !isset($_GET["opt_seccion"]) && !isset($_GET["opt_subseccion"])) {
    // Entra aqui si o solo si el usuario ha seleccionado
    // una de las opciones Principales del Menu para cargar las secciones.
    $cabeceraSeccionesByOptMenu = $menu->getCabeceraSeccionesByOptMenu($_GET["opt_menu"]); //Devuelve el nombre de option de menu principal que
    //seleccionamos asi como la imagen que debera cargar de lado izquierdo.
    $seccionesByOptMenu = $menu->getSeccionesByOptMenu($_GET["opt_menu"]); //Devuelve todas las secciones de acuerdo a la
    //Opcion pedida del menu principal.
    $archivotpl = "secciones_by_opt-menu.tpl"; // este es el contenedor que carga las secciones
    // de acuerdo a la opcion de menu principal seleccionada.
    $smarty->assign("cabeceraSeccionesByOptMenu", $cabeceraSeccionesByOptMenu);
    $smarty->assign("seccionesByOptMenu", $seccionesByOptMenu);
} elseif (isset($_GET["opt_menu"]) && isset($_GET["opt_seccion"]) && !isset($_GET["opt_subseccion"])) {
    // Entra aqui si o solo si el usuario
    // solo ha seleccionado una de las opciones de una seccion (opt_seccion)
    $smarty->assign("ruta", "?opt_menu=".$_GET["opt_menu"]);
    $archivo = $menu->getArchivosPHPTPL($_GET["opt_menu"], $_GET["opt_seccion"]);
    if ($archivo != "") {
        $archivophp = $archivo[0]["archivo_php"];
        $archivotpl = $archivo[0]["archivo_tpl"];
        if (file_exists($archivo[0]["archivo_php"])) {
            include($archivophp);
            #echo "<script> alert ('$archivophp');</script>";
        }#Opcion pedida del menu principal.
    }
} else {
    #if (isset($_GET["opt_menu"]) && isset($_GET["opt_seccion"]) && isset($_GET["opt_subseccion"])) {
    // Opcion pedida en donde se especifica que tipo de mantenimiento se desea hacer sobre un submodulo: add, edit, delete
    $instruccion = "
            SELECT
                opt_menu.cod_modulo as optmenu,
                subseccion.*,
                opt_seccion.img_ruta
            FROM modulos opt_menu
            INNER JOIN
                modulos opt_seccion ON opt_menu.cod_modulo = opt_seccion.cod_modulo_padre
            INNER JOIN
                subseccion ON subseccion.cod_seccion  = opt_seccion.cod_modulo
            WHERE
                opt_menu.cod_modulo = {$_GET["opt_menu"]} AND
                subseccion.cod_seccion = {$_GET["opt_seccion"]} AND
                subseccion.opt_subseccion = '{$_GET["opt_subseccion"]}'
            ORDER BY opt_menu.orden";
    $archivo = $menu->ObtenerFilasBySqlSelect($instruccion);
    if ($archivo != "") {
        $archivophp = $archivo[0]["archivo_php"];
        $archivotpl = $archivo[0]["archivo_tpl"];
        if (file_exists($archivo[0]["archivo_php"])) {
            #include $archivophp?file_exists($archivophp):"error.php";
            include $archivophp;
        }
        $smarty->assign("subseccion", $archivo);
        $smarty->assign("ruta", "?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]);
    }//if($archivo!=-1){
    #}//if(isset($_GET["opt_menu"])==true&&isset($_GET["opt_seccion"])==true&&isset($_GET["opt_subseccion"])==true){ // Opcion pedida en donde se especifica que tipo de mantenimiento se desea hacer sobre un submodulo: add, edit, delete
}

$campos = $parametrosgenerales->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
$smarty->assign("DatosGenerales", $campos);

$version = $parametrosgenerales->ObtenerFilasBySqlSelect("SELECT max(numero_version) as version FROM version_pyme");
$smarty->assign("version", $version);

$msgAUsuario2 = Msg::getMessage();
Msg::setMessage("");

$smarty->assign("idsesion", $login->getIdSessionActual());
$array_menu = $menu->getMenu($_SESSION["cod_usuario"]);
$smarty->assign("msgAUsuario", $msgAUsuario2);
$smarty->assign("DB_HOST", DB_HOST);
$smarty->assign("DB_SELECTRA_FAC", DB_SELECTRA_FAC);
$smarty->assign("archivotpl", $archivotpl);
$smarty->assign("codusuario", $_SESSION["cod_usuario"]);
$smarty->assign("stringLoginUsuario", $_SESSION["usuario"]);
$smarty->assign("nombre", ucwords(strtolower($_SESSION['nombreyapellido'])));
$t = new DateTime($_SESSION["ultimo_login"]);
$smarty->assign("stringUltimaSesionFecha", $t->format("d-m-Y"));
$smarty->assign("stringUltimaSesionHora", $t->format("h:i:s"));
$smarty->assign("itemMenuPrincipal", $array_menu);
$fecha = date('d/m/Y');

$campos = $parametrosgenerales->ObtenerFilasBySqlSelect("select id_divisa, Nombre, Abreviatura from parametros_generales,divisas where id_divisa = moneda_base ");
$campos2 = $parametrosgenerales->ObtenerFilasBySqlSelect("select * from moneda,divisas where id_divisa = moneda_actual ");
$campos3 = $parametrosgenerales->ObtenerFilasBySqlSelect("select cambio_unico from moneda");
$moneda_base = $parametrosgenerales->ObtenerFilasBySqlSelect("select moneda_base from parametros_generales");

$iddivisa2 = $campos2[0]['id_divisa'];
$cadena3 = "select id from tasas_cambio where date_format(fecha,'%d/%m/%Y')='$fecha' and divisa = $iddivisa2  and monedabase = '" . $moneda_base[0]['moneda_base'] . "'";
$campos4 = $parametrosgenerales->ObtenerFilasBySqlSelect($cadena3);

$abrev1 = $campos[0]['Abreviatura'];
$abrev2 = $campos2[0]['Abreviatura'];
$moneda1 = $campos[0]['Nombre'];
$moneda2 = $campos2[0]['Nombre'];

if ($campos3[0]['cambio_unico'] == 0 && $campos4[0]['id'] == "" && $_GET['stop'] != 'true') {
    echo '<script> location = "?opt_menu=1&opt_seccion=105&opt_subseccion=add&stop=true&mensaje=true";  </script>';
    exit();
}

$smarty->assign("cambio", $javascript1);

$layout = "index.tpl";
if (isset($_GET["layout"])) {
    if($_GET["layout"]==2){
        $layout = "index_modal.tpl";
    }
}
$smarty->display($layout);
?>
