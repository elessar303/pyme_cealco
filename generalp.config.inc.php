<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
error_reporting(0);
define('impresora_serial','123456', true);
define('DB_USUARIO','root', true);
define('DB_CLAVE', 'admin.2040', true);
define('DB_HOST', '192.168.22.45', true);
define('DB_SELECTRA_BIE', '', true);
define('DB_SELECTRA_DEFAULT', '', true);
define('SELECTRA_CONF_PYME', 'selectra_conf_pyme_ceal',true);
define('SUGARCRM', 'sugarcrm',true);
define('POS', 'pos_prueba_standard',true);
define('RESTRICCIONES', 'SI',true);
if (isset($_SESSION['EmpresaContabilidad']))
 define('DB_SELECTRA_CONT',$_SESSION['EmpresaContabilidad'], true);
if (isset($_SESSION['Empresa_Nomina']))
 define('DB_SELECTRA_NOM',$_SESSION['EmpresaNomina'], true);
if (isset($_SESSION['EmpresaFacturacion']))
define('DB_SELECTRA_FAC',$_SESSION['EmpresaFacturacion'], true);
$_SESSION['ROOT_PROYECTO']= $_SERVER['DOCUMENT_ROOT']."/pyme"; // debe especificarse el nivel donde está instalada la aplicacion con respecto al root del sitio
/* * CONSTANTES UTILIZADAS POR LA INTERFAZ DE REGISTRO DE EVENTOS (LOG) */
define('REG_INFO',0, true);
define('REG_LOGIN_OK',1, true);
define('REG_LOGIN_FAIL',2, true);
define('REG_LOGOUT',3, true);
define('REG_SESSION_INVALIDATE',4, true);
define('REG_SESSION_READ_ERROR',5, true);
define('REG_SQL_OK',6, true);
define('REG_SQL_FAIL',7, true);
define('REG_ILLEGAL_ACCESS',8, true);
define('REG_ALL',9, true);
/** * $config es un "por ahora", mientras se define donde va a residir la configuracion general de selectra **/
 $ConnSys = array('server' => DB_HOST, 'user' => DB_USUARIO, 'pass' => DB_CLAVE, 'db' => DB_SELECTRA_DEFAULT);
 $config['bd']='mysql';
 require_once('funciones.inc.php');
define('DB_USUARIOP','root', true);
define('DB_CLAVEP', 'admin.2040', true);
define('DB_HOSTP', '201.248.68.244', true);
define('DB_SELECTRA_PYMEP', 'selectrapyme_central_ccs', true);
define('DB_PYME', 'pyme_prueba_standar_ceal', true );
?>
