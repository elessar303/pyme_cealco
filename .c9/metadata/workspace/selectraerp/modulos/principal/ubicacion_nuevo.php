{"filter":false,"title":"ubicacion_nuevo.php","tooltip":"/selectraerp/modulos/principal/ubicacion_nuevo.php","undoManager":{"mark":-1,"position":-1,"stack":[[{"start":{"row":0,"column":0},"end":{"row":26,"column":0},"action":"remove","lines":["<?php","include(\"../../libs/php/clases/almacen.php\");","$almacen = new Almacen();","if(isset($_POST[\"aceptar\"])){","","","$instruccion = \"","INSERT INTO `ubicacion` (","","`descripcion`,","`puede_vender`,","`id_almacen`",")","VALUES ("," '\".$_POST[\"descripcion_ubicacion\"].\"', '\".$_POST[\"puede_vender\"].\"', '\".$_POST[\"id_almacen\"].\"'",");","\";","$almacen->Execute2($instruccion);","\tif(isset($_GET[\"loc\"])){","\t\theader(\"Location: ?opt_menu=\".$_POST[\"opt_menu\"].\"&opt_seccion=\".$_POST[\"opt_seccion\"].\"&opt_subseccion=ubicacion&cod=\".$_GET[\"cod\"].\"&idLocalidad=\".$_GET[\"idLocalidad\"].\"&loc=1\");","\t}else{","\t\theader(\"Location: ?opt_menu=\".$_POST[\"opt_menu\"].\"&opt_seccion=\".$_POST[\"opt_seccion\"].\"&opt_subseccion=ubicacion&cod=\".$_GET[\"cod\"]);","\t}","","","}",""],"id":2},{"start":{"row":0,"column":0},"end":{"row":30,"column":0},"action":"insert","lines":["<?php","include(\"../../libs/php/clases/almacen.php\");","$almacen = new Almacen();","if(isset($_POST[\"aceptar\"])){","","","$instruccion = \"","INSERT INTO `ubicacion` (","","`descripcion`,","`puede_vender`,","`id_almacen`",")","VALUES ("," '\".$_POST[\"descripcion_ubicacion\"].\"', '\".$_POST[\"puede_vender\"].\"', '\".$_POST[\"id_almacen\"].\"'",");","\";","$almacen->Execute2($instruccion);","\tif(isset($_GET[\"loc\"])){","\t\theader(\"Location: ?opt_menu=\".$_POST[\"opt_menu\"].\"&opt_seccion=\".$_POST[\"opt_seccion\"].\"&opt_subseccion=ubicacion&cod=\".$_GET[\"cod\"].\"&idLocalidad=\".$_GET[\"idLocalidad\"].\"&loc=1\");","\t}else{","\t\theader(\"Location: ?opt_menu=\".$_POST[\"opt_menu\"].\"&opt_seccion=\".$_POST[\"opt_seccion\"].\"&opt_subseccion=ubicacion&cod=\".$_GET[\"cod\"]);","\t}","","","}","","","","?>",""]}]]},"ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":29,"column":2},"end":{"row":29,"column":2},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":{"row":40,"mode":"ace/mode/php"}},"timestamp":1505921158139,"hash":"15038241de6c7374e30b7b8a283d51db6a1b7afa"}