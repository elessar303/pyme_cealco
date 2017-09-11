<?php

// PHPMaker 4 configuration
// Table level constants

define("ewTblVar", "proveedores", true);
define("ewTblRecPerPage", "RecPerPage", true);
define("ewSessionTblRecPerPage", "proveedores_RecPerPage", true);
define("ewTblStartRec", "start", true);
define("ewSessionTblStartRec", "proveedores_start", true);
define("ewTblShowMaster", "showmaster", true);
define("ewSessionTblMasterKey", "proveedores_MasterKey", true);
define("ewSessionTblMasterWhere", "proveedores_MasterWhere", true);
define("ewSessionTblDetailWhere", "proveedores_DetailWhere", true);
define("ewSessionTblAdvSrch", "proveedores_AdvSrch", true);
define("ewTblBasicSrch", "psearch", true);
define("ewSessionTblBasicSrch", "proveedores_psearch", true);
define("ewTblBasicSrchType", "psearchtype", true);
define("ewSessionTblBasicSrchType", "proveedores_psearchtype", true);
define("ewSessionTblSearchWhere", "proveedores_SearchWhere", true);
define("ewSessionTblSort", "proveedores_Sort", true);
define("ewSessionTblOrderBy", "proveedores_OrderBy", true);
define("ewSessionTblKey", "proveedores_Key", true);

// Table level SQL
define("ewSqlSelect", "SELECT * FROM `proveedores`", true);
define("ewSqlWhere", "", true);
define("ewSqlGroupBy", "", true);
define("ewSqlHaving", "", true);
define("ewSqlOrderBy", "", true);
define("ewSqlOrderBySessions", "", true);
define("ewSqlKeyWhere", "`cod_proveedor` = @cod_proveedor", true);
define("ewSqlUserIDFilter", "", true);
?>
