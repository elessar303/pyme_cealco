<?php

// PHPMaker 4 configuration
// Table level constants

define("ewTblVar", "item", true);
define("ewTblRecPerPage", "RecPerPage", true);
define("ewSessionTblRecPerPage", "item_RecPerPage", true);
define("ewTblStartRec", "start", true);
define("ewSessionTblStartRec", "item_start", true);
define("ewTblShowMaster", "showmaster", true);
define("ewSessionTblMasterKey", "item_MasterKey", true);
define("ewSessionTblMasterWhere", "item_MasterWhere", true);
define("ewSessionTblDetailWhere", "item_DetailWhere", true);
define("ewSessionTblAdvSrch", "item_AdvSrch", true);
define("ewTblBasicSrch", "psearch", true);
define("ewSessionTblBasicSrch", "item_psearch", true);
define("ewTblBasicSrchType", "psearchtype", true);
define("ewSessionTblBasicSrchType", "item_psearchtype", true);
define("ewSessionTblSearchWhere", "item_SearchWhere", true);
define("ewSessionTblSort", "item_Sort", true);
define("ewSessionTblOrderBy", "item_OrderBy", true);
define("ewSessionTblKey", "item_Key", true);

// Table level SQL
define("ewSqlSelect", "SELECT * FROM `item`", true);
define("ewSqlWhere", "", true);
define("ewSqlGroupBy", "", true);
define("ewSqlHaving", "", true);
define("ewSqlOrderBy", "`cod_item` ASC", true);
define("ewSqlOrderBySessions", "x_cod_item,ASC", true);
define("ewSqlKeyWhere", "`cod_item` = @cod_material", true);
define("ewSqlUserIDFilter", "", true);
?>
