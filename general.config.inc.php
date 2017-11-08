<?php
error_reporting(0);
define('DB_USUARIO','root', true);
define('DB_CLAVE', 'root', true);
define('DB_HOST', 'localhost', true);
define('DB_SELECTRA_CONF', 'selectra_conf_pyme', true);#sisalud_selectraconf
define('DB_SELECTRA_CONT', 'pyme_contabilidad', true);
define('DB_SELECTRA_NOM', 'pyme_nomina', true);
define('DB_SELECTRA_FAC', 'pyme_prueba_standar_cealco', true);
define('SUGARCRM', 'sugarcrm',true);
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
 $config['bd']='mysql';
 /** Archivo _temporal_ para ir colocando las funciones generales... :/ */
 require_once('funciones.inc.php');
?>
