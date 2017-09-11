<?php

// PHPMaker 4 configuration
// Table level constants

define("ewTblVar", "materiales", true);
define("ewTblRecPerPage", "RecPerPage", true);
define("ewSessionTblRecPerPage", "materiales_RecPerPage", true);
define("ewTblStartRec", "start", true);
define("ewSessionTblStartRec", "materiales_start", true);
define("ewTblShowMaster", "showmaster", true);
define("ewSessionTblMasterKey", "materiales_MasterKey", true);
define("ewSessionTblMasterWhere", "materiales_MasterWhere", true);
define("ewSessionTblDetailWhere", "materiales_DetailWhere", true);
define("ewSessionTblAdvSrch", "materiales_AdvSrch", true);
define("ewTblBasicSrch", "psearch", true);
define("ewSessionTblBasicSrch", "materiales_psearch", true);
define("ewTblBasicSrchType", "psearchtype", true);
define("ewSessionTblBasicSrchType", "materiales_psearchtype", true);
define("ewSessionTblSearchWhere", "materiales_SearchWhere", true);
define("ewSessionTblSort", "materiales_Sort", true);
define("ewSessionTblOrderBy", "materiales_OrderBy", true);
define("ewSessionTblKey", "materiales_Key", true);

// Table level SQL
define("ewSqlSelect", "SELECT * FROM `materiales`", true);
define("ewSqlWhere", "", true);
define("ewSqlGroupBy", "", true);
define("ewSqlHaving", "", true);
define("ewSqlOrderBy", "`cod_material` ASC", true);
define("ewSqlOrderBySessions", "x_cod_material,ASC", true);
define("ewSqlKeyWhere", "`cod_material` = @cod_material", true);
define("ewSqlUserIDFilter", "", true);
?>
