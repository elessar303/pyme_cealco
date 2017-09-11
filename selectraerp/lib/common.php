<?php
#require_once($_SERVER['DOCUMENT_ROOT'].'/pyme/generalp.config.inc.php');
require_once('../../general.config.inc.php');

function envia_array($array) {
    $tmp = serialize($array);
    $tmp = urlencode($tmp);
    return $tmp;
}

function recibe_array ($url_array) {
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
   return $tmp;
} 

class bd{
	private $servidor=DB_HOST;
	private $usuario=DB_USUARIO;
	private $clave= DB_CLAVE;
	private $base;
	public function bd($var){
		
		$this->base=$var;
	}
	public function create_database($bdnueva){
		$conexion= new mysqli($this->servidor,$this->usuario,$this->clave);
		if (mysqli_connect_errno()) {
 		   printf("Connect failed: %s\n", mysqli_connect_error());
		   return;
		}
		$sentencia="create database ".$bdnueva;
		$resultado=$conexion->query($sentencia);
		
	}
	private function connect(){
		$conexion= new mysqli($this->servidor,$this->usuario,$this->clave,$this->base);
		if (mysqli_connect_errno()) {
 		   printf("Fallo de conexion: %s\n", mysqli_connect_error());

		}
		return $conexion;
	}

	
	public function query($sentencia){
		$conexion=$this->connect();
		$resultado=$conexion->query($sentencia);
		
		if (!$resultado) {
    		printf("Error: %s\n", $conexion->error);
		}
		$conexion->close();
		return $resultado;
	
	}
	public function multi_query($sentencia){
		$conexion=$this->connect();
		$resultado=$conexion->multi_query($sentencia);
		
		if (!$resultado) {
    		printf("Error: %s\n", $conexion->error);
		}
		$conexion->close();
		return $resultado;
	
	}
	
	
}
class procesos{

	public function final_chequera($banco,$chequera,$cheque){
		$conexion=new bd(DB_SELECTRA_DEFAULT);	
		$consulta="select max(cheque) as maximo from cheques where banco='".$banco."' and chequera='".$chequera."'";
		$resultado=$conexion->query($consulta);
		$fila=$resultado->fetch_assoc();
		if($fila['maximo']==$cheque){
			$consulta="update chequera set situacion='C' where banco='".$banco."' and numero='".$chequera."'";
			$resultado=$conexion->query($consulta);
			return 1;
		}else{
			return 0;
		}
		
		
	
	}


}


function busqueda($url,$parametros,$array){
?>
<table class="tb-head" width="100%">
  <tr>
	<td><input type="text" name="buscar" size="20"></td>
	<td><SELECT name="sel_busqueda">
		<?foreach($array as $valor){?>
		<option><?echo $valor?></option>
		<?}?>
     </SELECT></td>
	<td><? btn('search',$url,1); ?></td>
	<td><? btn('show_all',$url.".php?".$parametros); ?></td>
	<td width="120"><input onclick="javascript:actualizar(this);" checked="true" type="radio" name="palabra" value="exacta">Palabra exacta</td>
	<td width="140"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="todas">Todas las palabras</td>
	<td width="150"><input onclick="javascript:actualizar(this);" type="radio" name="palabra" value="cualquiera">Cualquier palabra</td>
	<td colspan="3" width="386"></td>
  </tr>
</table>
<?
}
function mesaletras($mes){

$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
return $meses[intval($mes)];

}
function titulo_mejorada($nombre,$icono,$lista,$regresar){
$botones=explode('|',$lista);

?>
<table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
	<tbody>
		<tr class="row-br">
		<?if($icono!=""){?>
		<td width="25"><img src="../imagenes/<?=$icono?>" width="22" height="22" class="icon"></td>
		<?}?>
		<td><?echo $nombre?></td>
		<?foreach($botones as $boton){
		?>
		<td width="75" align="right"><?eval($boton);?></td>
		<?}?>
		<td width="75" align="right"><?btn('back',$regresar)?></td>
		</tr>
	</tbody>
</table>
<?
}

function buscar_exacta($modulo,$valor,$campo){
		$consulta="select * from ".$modulo." where ".$campo." LIKE '%".$valor."%' ";
	return $consulta;
}

function buscar_exacta_join($modulo,$valor,$campo,$join,$orden){
		$consulta="select * from ".$modulo." ".$join." and ".$campo." LIKE '%".$valor."%' ".$orden;
	return $consulta;
}

function buscar_comienzo($modulo,$valor,$campo){
		$consulta="select * from ".$modulo." where ".$campo." LIKE '".$valor."%' ";
	return $consulta;
}

function buscar_comienzo_join($modulo,$valor,$campo,$join,$orden){
		$consulta="select * from ".$modulo." ".$join." and ".$campo." LIKE '".$valor."%' ".$orden;
	return $consulta;
}


function buscar_todas($modulo,$temp,$campo){

	$cadenas=split(" ",$temp);

	$sql2 = "select * from ".$modulo." where";

	foreach($cadenas as $actual){
		$sql2 = $sql2 ." ".$campo." LIKE '%".$actual."%' AND"; 
	}
	$longitud = strlen($sql2);
	$sql2 = substr($sql2,0,$longitud-4);
	return $sql2;
}


function buscar_cualquiera($modulo, $temp, $campo){
	$cadenas=split(" ",$temp);
	$sql2 = "select * from ".$modulo." where (";
	foreach($cadenas as $actual){
		$sql2 = $sql2 ." ".$campo." LIKE '%".$actual."%' OR"; 
	}
	$longitud = strlen($sql2);
	$sql2 = substr($sql2,0,$longitud-3);
	$sql2=$sql2.")";
	return $sql2;

}

function obtener_num_paginas($consulta,$LIMITE=15){
	$resultado=mysql_query($consulta) or die("no se pudo realizar la consulta ".mysql_error());
	$numero_filas=mysql_num_rows($resultado);
	$num_paginas=ceil($numero_filas/$LIMITE);
	return $num_paginas;
}

function obtener_pagina_actual($pagina, $num_paginas){
	if($pagina<1){
		$pagina=1;
	}else{
		if($pagina>$num_paginas && $num_paginas!=0){
			$pagina=$num_paginas;
		}
	}
	return $pagina;
}

function paginacion($pagina, $consulta){
	$LIMITE=15;
	$inicio=($pagina*$LIMITE)-$LIMITE;
	$consulta2=$consulta." limit ".$inicio.", ".$LIMITE."";
	//echo $consulta2;
	//return $consulta2;
	$resultado=mysql_query($consulta2) or die("no se pudo realizar la consulta");
	return $resultado;
}

//INICIALIZACION DE VARIABLES


function fecha($value) { // fecha de YYYY/MM/DD a DD/MM/YYYY
 if ( ! empty($value) ) return substr($value,8,2) ."/". substr($value,5,2) ."/". substr($value,0,4);
}

function fecha_en($value) { // fecha de YYYY/MM/DD a DD/MM/YYYY
 if ( ! empty($value) ) return substr($value,5,2) ."/". substr($value,8,2) ."/". substr($value,0,4);
}

function fechahora($value) { // fecha de 'YYYY-MM-DD HH:MM:SS' a 'DD-MM-YYYY HH:MM:SS'
 if ( ! empty($value) ) return substr($value,8,2) ."/". substr($value,5,2) ."/". substr($value,0,4)." ".substr($value,11,8);
}

function fecha_sql($value) { // fecha de DD/MM/YYYY a YYYYY/MM/DD
 return substr($value,6,4) ."-". substr($value,3,2) ."-". substr($value,0,2);
}

function fechahora_sql($value) { // fecha de 'DD-MM-YYYY HH:MM:SS' a 'YYYY-MM-DD HH:MM:SS'
 return substr($value,6,4) ."-". substr($value,3,2) ."-". substr($value,0,2)." ".substr($value,11,8);
}

function tiempo($value) { // de 'YYYY-MM-DD HH:MM:SS' a 'HH:MM:SS'
 return substr($value,11,8);
}

function numero($value,$dec=0) {
 if ( ! empty($value) ) return number_format($value, 2, ',', '.');
}

function value($colName, $type, $method = '', $reference = 0) {
	global $$colName;
	global $$reference;
	if ($reference != ""){ $colName = $reference; }

	switch ($method) {
		case '':
			// por los check
			if ( isset($_REQUEST["$colName"]) ) {$val = $_REQUEST["$colName"]; } else { $val = "";}
			$value = $val;
			break;
		case "post":
			if ( isset($_POST["$colName"]) ) {$val = $_POST["$colName"]; } else { $val = "";}
			$value = $val;
			break;
		case "get":
			if ( isset($_GET["$colName"]) ) {$val = $_GET["$colName"]; } else { $val = "";}
			$value = $val;
			break;
		case "session":
			$value = $_SESSION["$colName"];
			break;
		case "var":
			$value = $$colName;
			break;
		case "value":
			$value = $reference;
			break;
	}
	$value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
	switch ($type) {
		case "text":
			$value = ($value != "") ? "'" . $value . "'" : "NULL";
			break;
		case "long":
		case "int":
		  $value = ($value != "") ? intval(str_replace(",", ".", str_replace(".", "", $value))) : "0";
		  break;
		case "double":
		  $value = ($value != "") ? "'" . str_replace(array(".",","), ".", $value ) . "'" : "NULL";
		  break;
		case "date":
		  $value = ($value != "") ? "'" . fecha_sql($value) . "'" : "NULL";
		  break;
		case "datetime":
		  $value = ($value != "") ? "'" . fechahora_sql($value) . "'" : "NULL";
		  break;
		case "now":
		  $value = 'now()';
		  break;
	}
	return $value;
}

class insert {
    var $table;
	var $columns;
	var $sql;

	function table($table)
	{
	 $this->table = $table;
	}
	function addColumn($colName, $type, $method = '', $reference = 0, $default = '') {
     $this->columns[] = array ( "colName" => $colName, "type" => $type, "method" => $method, "reference" => $reference);
	}
	
	function sql(){
	$sql = "INSERT INTO ". $this->table ." (";
	for ($i = 0; $i < sizeof($this->columns); $i = $i + 1) {
	  $sql = $sql . $this->columns[$i]['colName'].",";
	};
	$sql = substr($sql, 0, -1).") VALUES (";

	reset($this->columns);

	for ($i = 0; $i < sizeof($this->columns); $i = $i + 1) {
	  $sql = $sql . value($this->columns[$i]['colName'],$this->columns[$i]['type'],$this->columns[$i]['method'],$this->columns[$i]['reference']).",";
	};

	$sql = substr($sql, 0, -1).")";

	return $sql;
	}
};// class


class update {
    var $table;
	var $columns;
	var $field_id;
	var $id;
	var $sql;

	function table($table){
	 $this->table = $table;
	}
	function addColumn($colName, $type, $method = 0, $reference = 0, $default = '') {
     $this->columns[] = array ( "colName" => $colName, "type" => $type, "method" => $method, "reference" => $reference);
	}
	function field_id($field_id){
	 $this->field_id = $field_id;
	}
	function id($id){
	 $this->id = $id;
	}
	function sql(){
	$sql = "UPDATE ". $this->table ." SET ";
	for ($i = 0; $i < sizeof($this->columns); $i = $i + 1) {
	  $sql = $sql . $this->columns[$i]['colName']."=".value($this->columns[$i]['colName'],$this->columns[$i]['type'],$this->columns[$i]['method'],$this->columns[$i]['reference']).",";
	};
	$sql = substr($sql, 0, -1)." WHERE $this->field_id = '$this->id'";
	return $sql;
	}
};// class

function select($field_value, $field_label, $field_selected, $sql, $control_name, $selecione=0) {
      //global $Conn; 
$Conn=conexion();
      $rs = query($sql,$Conn);
	  $ret="<select class='text-peq' name=".$control_name.">";
	  if ($selecione == 1) {$ret.="<option value=''>Seleccione</option>";};
	  if ($selecione == 2) {$ret.="<option value='0'>Seleccione</option>";};
      if ($selecione == 3) {$ret.="<option value='0'>TODOS</option>";};
      while ($row_rs = fetch_array($rs)) {;
        if ( $row_rs[$field_value] == $field_selected ) {;
         $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'" selected>'.htmlspecialchars($row_rs[$field_label]).'</option>';
        } else {;
          $ret.='<option title="RIF/CI: '.($row_rs['rif']).'" value="'.htmlspecialchars($row_rs[$field_value]).'">'.htmlspecialchars($row_rs[$field_label]).'</option>';
        };
      };
     //cerrar_conexion($Conn);
     $ret.="</select>";
	 print $ret;
};
function select2($field_value, $field_label, $field_selected, $sql, $control_name, $selecione=0,$selecione2) 
{
    //  global $Conn; 
		$Conn=conexion();
      $rs = query($sql,$Conn);
	  $ret="<select class='text-peq' name=".$control_name.">";
	  if ($selecione == 1) {$ret.="<option value=''>Seleccione</option>";};
	  if ($selecione == 2) {$ret.="<option value='0'>Seleccione</option>";};
	  if ($selecione == 3) {$ret.="<option value='0'>$selecione2</option>";};
      while ($row_rs = fetch_array($rs)) {;
        if ( $row_rs[$field_value] == $field_selected ) {;
         $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'" selected>'.htmlspecialchars($row_rs[$field_label]).'</option>';
        } else {;
          $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'">'.htmlspecialchars($row_rs[$field_label]).'</option>';
        };
      };
     //cerrar_conexion($Conn);
     $ret.="</select>";
	 print $ret;
};
function select3($field_value, $field_label, $field_selected, $sql, $control_name, $selecione=0,$selecione2,$selecione3) {
      $Conn=conexion();//global $Conn; 
      $rs = query($sql,$Conn);
	  $ret="<select class='text-peq' name=".$control_name.">";
	  if ($selecione == 1) {$ret.="<option value=''>Seleccione</option>";};
	  if ($selecione == 2) {$ret.="<option value='0'>Seleccione</option>";};
 	  if ($selecione == 3) {$ret.="<option value='$selecione3'>$selecione2</option>";};
      while ($row_rs = fetch_array($rs)) {;
        if ( $row_rs[$field_value] == $field_selected ) {;
         $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'" selected>'.htmlspecialchars($row_rs[$field_label]).'</option>';
        } else {;
          $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'">'.htmlspecialchars($row_rs[$field_label]).'</option>';
        };
      };
     //cerrar_conexion($Conn);
     $ret.="</select>";
	 print $ret;
};

function select4($field_value, $field_label, $field_selected, $sql, $control_name, $selecione=0) {
      global $Conn; 
      $rs = $Conn->query($sql);
	  $ret="<select class='text-peq' name=".$control_name." disabled>";
	  if ($selecione == 1) {$ret.="<option value=''>Seleccione</option>";};
	  if ($selecione == 2) {$ret.="<option value='0'>Seleccione</option>";};
      if ($selecione == 3) {$ret.="<option value='0'>TODOS</option>";};
      while ($row_rs = $rs->fetch_assoc()) {;
        if ( $row_rs[$field_value] == $field_selected ) {;
         $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'" selected>'.htmlspecialchars($row_rs[$field_label]).'</option>';
        } else {;
          $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'">'.htmlspecialchars($row_rs[$field_label]).'</option>';
        };
      };
     $rs->close();
     $ret.="</select>";
	 print $ret;
};
function select5($field_value, $field_label, $field_selected, $sql, $control_name, $selecione=0) {
      global $Conn; 
      $rs = $Conn->query($sql);
	  $ret="<select class='text-peq' name=".$control_name.">";
	  if ($selecione == 1) {$ret.="<option value=''>Seleccione</option>";};
	  if ($selecione == 2) {$ret.="<option value='0'>Seleccione</option>";};
      if ($selecione == 3) {$ret.="<option value='0'>TODOS</option>";};
      while ($row_rs = $rs->fetch_assoc()) {;
        if ( $row_rs[$field_value] == $field_selected ) {;
         $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'" selected>'.htmlspecialchars($row_rs[$field_label]).'</option>';
        } else {;
          $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'">'.htmlspecialchars($row_rs[$field_label]).'</option>';
        };
      };
     $rs->close();
     $ret.="</select>";
	 print $ret;
};
function select6($field_value, $field_label, $field_selected, $sql, $control_name, $selecione=0,$selecione2,$selecione3) {
      global $Conn; 
      $rs = $Conn->query($sql);
	  $ret="<select class='text-peq' name=".$control_name." onClick='validar();'>";
	  if ($selecione == 1) {$ret.="<option value=''>Seleccione</option>";};
	  if ($selecione == 2) {$ret.="<option value='0'>Seleccione</option>";};
 	  //if ($selecione == 3) {$ret.="<option value='0'>TODOS</option>";};
      while ($row_rs = $rs->fetch_assoc()) {;
        if ( $row_rs[$field_value] == $field_selected ) {;
         $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'" selected>'.htmlspecialchars($row_rs[$field_label]).'</option>';
        } else {;
          $ret.='<option value="'.htmlspecialchars($row_rs[$field_value]).'">'.htmlspecialchars($row_rs[$field_label]).'</option>';
        };
      };
     $rs->close();
     $ret.="</select>";
	 print $ret;
};

function select_array($array, $field, $control_name) {
	  $ret="<select class='text-peq' name=".$control_name.">";
	  foreach ($array as $value) {
        if ( $value == $field ) {;
         $ret.='<option value="'.htmlspecialchars($value).'" selected>'.htmlspecialchars($value).'</option>';
        } else {;
          $ret.='<option value="'.htmlspecialchars($value).'">'.htmlspecialchars($value).'</option>';
        };
	  };
	  $ret.="</select>";
	 print $ret;
};

function select_num($c_to, $c_name, $c_value) {
	$ret="<select class='text-peq' name=".$c_name.">";
	for ($i = 1; $i <= $c_to; $i++) {;
       if ($c_value == $i) {
	    $ret.="<option value=\"$i\"selected>$i</option>";
		} else {
		$ret.="<option value=\"$i\">$i</option>";
		}
    };
	$ret.="</select>";
	print $ret;
};

function check($name,$est){
?>
<input type="checkbox" name="<? echo $name ?>" value="1" <? if ($est == "1") echo "checked" ?>>
<?php
}

function check_img($est){
  switch ($est) {
    	   case "": echo '<img src="img_sis/op_off.gif" width="15" height="15">';
    break; case "1": echo '<img src="img_sis/op_on.gif" width="15" height="15">';
  }
}


function boton($cod_modulo,$nom_menu,$archivo){
$ret = 
	'<div class="box">
	<table width="150" height="90" border="0" cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="javascript:window.location=\''.$archivo.'\'">
        <tr>
<td height="45" valign="bottom"><div align="center"><img width="30" height="30" src="imagenes/'.$cod_modulo.'.png" class="icon"/></div></td>
        </tr>
        <tr>
          <td height="45"><div align="center" class="boton-text">'.utf8_encode(nl2br($nom_menu)).'</div></td>
        </tr>
  </table>
	</div>
	';
print $ret;
}

function boton1($cod_modulo,$nom_menu,$archivo){
$ret = 
	'<div class="box">
	<table width="100" height="90" border="0" cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="javascript:window.location=\''.$archivo.'\'">
        <tr>
          <td height="45" valign="bottom"><div align="center"><img src="../imagenes/'.$cod_modulo.'.png" class="icon"/></div></td>
        </tr>
        <tr>
          <td height="45"><div align="center" class="boton-text">'.utf8_encode(nl2br($nom_menu)).'</div></td>
        </tr>
  </table>
	</div>
	';
print $ret;
}
function boton2($cod_modulo,$nom_menu,$archivo){
$ret = 
	'<div class="box">
	<table width="150" height="90" border="0" cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="javascript:'.$archivo.'">
        <tr>
<td height="45" valign="bottom"><div align="center"><img width="30" height="30" src="imagenes/'.$cod_modulo.'.png" class="icon"/></div></td>
        </tr>
        <tr>
          <td height="45"><div align="center" class="boton-text">'.utf8_encode(nl2br($nom_menu)).'</div></td>
        </tr>
  </table>
	</div>
	';
print $ret;
}

function btn_img($tipo,$url){
 switch ($tipo) {;
 case "view":
	echo '';
	break; 
 case "edit":
	echo '<a href="'.$url.'"><img src="images/edit.gif" alt="Editar" width="16" height="16" border="0"></a>';
	break;
 case "del":
	echo '<a href="javascript:;" onClick="confirmar(\'Seguro de borrar\',\''.$url.'\'); return self.rValue"><img src="images/delete.gif" alt="Borrar" width="15" height="15" border="0"></a>';
	break;
 case "save":
	echo '<input type="image" class="no-br" src="img_sis/ico_save.gif" alt="Guardar" width="15" height="15" border="0">';
	break; 
 }
}

function btn($tipo,$url,$accion=0){ // Accion 0=location / 1=Form url = form name / 2 JS ** 3 reset **terminar**

switch ($tipo) {;

 case "add":

	$icon = 'add';

	$name = 'Agregar';

	break; 

 case "edit":

	$icon = 'edit';

	$name = 'Editar';

	break;

 case "print":

	$icon = 'ico_print';

	$name = 'Imprimir';

	break;

 case "cal_iva":

	$icon = 'edit';

	$name = 'Cambiar IVA';

	break;

 case "del":

	$icon = 'delete';

	$name = 'Borrar';

	break;

 case "save":

	$icon = 'delete';

	$name = 'Borrar';

	break; 

 case "ok":

	$icon = 'ok';

	$name = 'Aceptar';

	break; 

 case "cancel":

	$icon = 'cancel';

	$name = 'Cancelar';

	break; 

 case "search":

	$icon = 'search';

	$name = 'Buscar';

	break; 

 case "show_all":

	$icon = 'list';

	$name = 'Mostrar todo';

	break;

 case "maestro":

	$icon = 'back';

	$name = 'Volver a la página maestra';

	break;

 

case "back":

	$icon = 'back';

	$name = 'Regresar';

	break;

case "grabar":

	$icon = 'back';

	$name = 'Grabar';

	break;
case "grabarnormal":

	$icon = 'save';

	$name = 'Grabar';

	break;
case "enviar":

	$icon = 'ok';

	$name = 'Enviar';

	break;
	
case "xls":

	$icon = 'ico_export';

	$name = 'Exportar';

	break;

case "incorporar":

	$icon = 'add';

	$name = 'Incorporacion';

	break;

case "bienes":

	$icon = 'bien';

	$name = 'Bienes';

	break;

case "add1":

	$icon = 'add';

	$name = 'Agregar de Nivel 1';

	break;

 }

switch ($accion) {;

 case 0:

	$js = "window.location='$url'";

	break; 

 case 1:

	

	$js = "window.document.$url.submit();";

	break;

 case 2:

	$js = $url;

	break;

 }

echo '<table style="cursor: pointer;" class="btn_bg" onClick="javascript:'.$js.'" name="buscar" border="0" cellpadding="0" cellspacing="0">

		<tr>

		  <td style="padding: 0px;" align="right"><img src="../imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>

		  <td class="btn_bg"><img src="../imagenes/'.$icon.'.gif" width="16" height="16" /></td>

		  <td class="btn_bg" nowrap style="padding: 0px 4px;">'.$name.'</td>

		  <td style="padding: 0px;" align="left"><img src="../imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>

		</tr>

	  </table>';

}


function tit($tabla){
	global $ConnSys;
	$Conn = conexion_conf();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
	$rs = query("SELECT * FROM modulos WHERE tabla = '$tabla'",$Conn);
	$row_rs = fetch_array($rs);
	if ( num_rows($rs) == 1) {
	  echo '<img src="img_sis/icons/'.$row_rs['cod_modulo'].'.png" width="22" height="22" class="icon" /> '.$row_rs['nom_menu'];
	}
	cerrar_conexion($Conn);
}

function titulo($modulo,$agregar,$regresar,$enlace="nada",$imprimir="nada",$calcular_iva="nada")
{
   if($calcular_iva=="nada")
   {	
	if($imprimir=="nada")
	{
		if($enlace=="nada")
		{
			if($agregar=="")
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\">$modulo</span></td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
			else
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\">$modulo</span></td>
				<td width=\"75\">"; btn('add',$agregar); echo "</td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
		}
		else
		{
			if($agregar=="")
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
			else
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
				<td width=\"75\">"; btn('add',$agregar); echo "</td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
   		}
	}
	else
	{
		if($enlace=="nada")
		{
			if($agregar=="")
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\">$modulo</span></td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('print',$imprimir); echo "</td>
				</tbody>
				</table>";
			}
			else
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\">$modulo</span></td>
				<td width=\"75\">"; btn('add',$agregar); echo "</td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('print',$imprimir); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
		}
		else
		{
			if($agregar=="")
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('print',$imprimir); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
			else
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
				<td width=\"75\">"; btn('add',$agregar); echo "</td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('print',$imprimir); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
		}
	}
   }
   else
   {
	if($imprimir=="nada")
	{
		if($enlace=="nada")
		{
			if($agregar=="")
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\">$modulo</span></td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
			else
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\">$modulo</span></td>
				<td width=\"75\">"; btn('add',$agregar); echo "</td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				</tr>
				</tbody>
				</table>";
			}
		}
		else
		{
			if($agregar=="")
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('cal_iva',$calcular_iva); echo "</td></tr>
				</tr>
				</tbody>
				</table>";
			}
			else
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
				<td width=\"75\">"; btn('add',$agregar); echo "</td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('cal_iva',$calcular_iva); echo "</td></tr>
				</tr>
				</tbody>
				</table>";
			}
   		}
	}
	else
	{
		if($enlace=="nada")
		{
			if($agregar=="")
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\">$modulo</span></td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('print',$imprimir); echo "</td>
				<td width=\"75\">"; btn('cal_iva',$calcular_iva); echo "</td></tr>
				</tr>
				</tbody>
				</table>";
			}
			else
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\">$modulo</span></td>
				<td width=\"75\">"; btn('add',$agregar); echo "</td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('print',$imprimir); echo "</td>
				<td width=\"75\">"; btn('cal_iva',$calcular_iva); echo "</td></tr>
				</tr>
				</tbody>
				</table>";
			}
		}
		else
		{
			if($agregar=="")
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('print',$imprimir); echo "</td>
				<td width=\"75\">"; btn('cal_iva',$calcular_iva); echo "</td></tr>
				</tr>
				</tbody>
				</table>";
			}
			else
			{
				echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
				<tbody>
				<tr>
				<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
				<td width=\"75\">"; btn('add',$agregar); echo "</td>
				<td width=\"75\">"; btn('back',$regresar); echo "</td>
				<td width=\"75\">"; btn('print',$imprimir); echo "</td>
				<td width=\"75\">"; btn('cal_iva',$calcular_iva); echo "</td></tr>
				</tr>
				</tbody>
				</table>";
			}
		}
	}
   }	
}


function titulo2($modulo,$agregar,$regresar,$xls,$enlace)
{
	echo "<table class=\"tb-tit\" cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
		<tbody>
		<tr>
		<td width=\"900\"><span style=\"float:left\"><img src=\"../imagenes/$enlace.png\" width=\"22\" height=\"22\" class=\"icon\" />$modulo</span></td>
		<td width=\"75\">"; btn('xls',$xls); echo "</td>
		<td width=\"75\">"; btn('add',$agregar); echo "</td>
		<td width=\"75\">"; btn('back',$regresar); echo "</td>
		</tr>
		</tbody>
		</table>";
}



function detalle($div,$name,$file,$title,$form,$id) {

echo '
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="ewTableTitle">
  <tr>
    <td>'.$title.'</td>
    <td align="right"><button type="button" onclick="'.$name.'Add();"><img src="images/add.gif" class="icon" /> Agregar</button></td>
  </tr>
</table>
<div id="'.$div.'list"></div>

<script type="text/javascript">
function '.$name.'List(){
new Ajax.Updater(\''.$div.'list\', \''.$file.'?id_ma='.$id.'\');
}

function '.$name.'Add(){
new Ajax.Updater(\''.$div.'list\', \''.$file.'?rsac=add&id_ma='.$id.'\');
}

function '.$name.'Added(){
var params = Form.serialize(\''.$form.'\');
new Ajax.Request(\''.$file.'?rsac=added\', { parameters: params, onComplete:'.$name.'List });
}

function '.$name.'Edit(id,id_ma){
new Ajax.Updater(\''.$div.'list\', \''.$file.'?id_ma=<?= $id ?>\', { parameters: \'id=\'+id } );
}

function '.$name.'Edited(){
var params = Form.serialize(\''.$form.'\');
new Ajax.Request(\''.$file.'?rsac=edited\', { parameters: params, onComplete:\''.$div.'list\'});
}

function '.$name.'Delete(id){
new Ajax.Request(\''.$file.'?rsac=deleted\', { parameters: \'id=\'+id , onComplete:\''.$div.'list\'});
}
'.$name.'List();
</script>';
}
function conexion_formular(){
$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){
	$host = DB_HOST;
  	$usuario = DB_USUARIO;
  	$clave = DB_CLAVE; 
	$conexion= mysql_connect($host,$usuario,$clave);
	$base= mysql_select_db("formulacionremapca",$conexion);
	return $conexion;
}else{
	echo "No se ha implementado en postgres";
}
}
function conexion_instructivo(){
$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){
	$host = DB_HOST;
  	$usuario = DB_USUARIO;
  	$clave = DB_CLAVE; 
	$conexion= mysql_connect($host,$usuario,$clave);
	$base= mysql_select_db("selectrainstructivo",$conexion);
	return $conexion;
}else{
	echo "No se ha implementado en postgres";
}
}
function field_name($resultado,$campo)
{
	$config=parse_ini_file("selectra.ini");
	if($config[bd]=="mysql")
	{
		$fila=mysql_field_name($resultado,$campo);
		return $fila;
	}
	else
	{
		echo "No se ha implementado en postgres";
	}
}

function conexion(){
$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){
	$host = DB_HOST;
  	$usuario = DB_USUARIO;
  	$clave = DB_CLAVE; 
	$conexion= mysql_connect($host,$usuario,$clave);
	$base= mysql_select_db(DB_SELECTRA_FAC,$conexion);
	return $conexion;
}else{
	echo "No se ha implementado en postgres";
}
}

function conexion_contab(){
$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){
	$host = DB_HOST;
  	$usuario = DB_USUARIO;
  	$clave = DB_CLAVE; 
	$conexion= mysql_connect($host,$usuario,$clave);
	$base= mysql_select_db(DB_SELECTRA_CONT,$conexion);
	return $conexion;
}else{
	echo "No se ha implementado en postgres";
}
}

function conexion_sol(){
$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){
	$host="localhost";
	$usuario="root";
	$clave="";
	$conexion= mysql_connect($host,$usuario,$clave);
	$base= mysql_select_db("solicitudes",$conexion);
	return $conexion;
}else{
	echo "No se ha implementado en postgres";
}
}


function conexion_conf(){
$config=parse_ini_file("selectra.ini");

if($config[bd]=="mysql"){
	$host = DB_HOST;
  	$usuario = DB_USUARIO;
  	$clave = DB_CLAVE; 
	$conexion= mysql_connect($host,$usuario,$clave);
	$base= mysql_select_db(DB_SELECTRA_FAC,$conexion);
	return $conexion;
}else{
	echo "No se ha implementado en postgres";
}
}
function cerrar_conexion($conexion){
$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){
	mysql_close($conexion);
}else{
	echo "No se ha implementado en postgres";
}
}
function query($consulta, $conexion){
$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){

$resultado=mysql_query($consulta,$conexion) or die("No se puede realizar la consulta en la linea ".$consulta." ".mysql_error());
	return $resultado;
}else{
	echo "No se ha implementado en postgres";
}
}


function fetch_array($resultado){

$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){
	$fila=mysql_fetch_array($resultado);
	return $fila;
}else{
	echo "No se ha implementado en postgres";
}

}

function num_rows($resultado){
$config=parse_ini_file("selectra.ini");
if($config[bd]=="mysql"){
	$numero=mysql_num_rows($resultado);
	return $numero;
}else{
	echo "No se ha implementado en postgres";
}
}

function pie_pagina($url,$pagina,$campos,$num_paginas){

echo '<table cellpadding="0" cellspacing="0" border="0" class="tb-head" width="100%">
  <tbody>
    <tr>
      <td><span class="phpmaker">P&aacute;gina&nbsp;</span></td>
      <td><a href="'.$url.'.php?pagina=1&'.$campos.'"><img src="../imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" border="0"></a></td>
      <td><a href="'.$url.'.php?pagina='.($pagina-1).'&'.$campos.'"><img src="../imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" border="0"></a></td>
      <td><input type="text" name="numero_pagina" onblur="javascript:paginacion(\''.$url.'\',this.value,\''.$campos.'\')" value="'.$pagina.'" size="4"></td>
     <td><a href="'.$url.'.php?pagina='.($pagina+1).'&'.$campos.'"><img src="../imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" border="0"></a></td>
      <td><a href="'.$url.'.php?pagina='.$num_paginas.'&'.$campos.'"><img src="../imagenes/last.gif" alt="Ultima" title="Ultima" width="16" height="16" border="0"></a></td>
      <td colspan="14" width="100%" align="center">P&aacute;gina '.$pagina.' de '.$num_paginas.'</td>
    </tr>
  </tbody>
</table>';

}

function icono($url,$titulo,$enlace){
echo "<td><a href=\"$url\"><IMG title=\"$titulo\" src=\"../imagenes/$enlace\" width=\"16\" height=\"16\" align=\"left\" border=\"0\"></a></td>
";
}

function iconoNuevo($url,$titulo,$enlace){
echo "<td ><a href=\"$url\" target=\"_blank\"><IMG  title=\"$titulo\" src=\"../imagenes/$enlace\" width=\"16\" height=\"16\" align=\"left\" border=\"0\"></a></td>
";
}

function encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq){

$encabezado= '<table  width="700" border="0" align="center">
  <tbody>
    <tr>
      <td rowspan="4" align="left" valign="top" width="250" height="80"><img src="'.$var_imagen_izq.'" width="150" height="106"></td>
      <td width="400"><p align="center"><strong>'. $var_encabezado1.'</strong></p></td>
     
    </tr>
    <tr>
      <td width="400"><p align="left"><strong>'.$var_encabezado2.'</strong></p></td>
    </tr>
    <tr>
      <td width="400"><p align="center"><strong>'.$var_encabezado3.'</strong></p></td>
    </tr>
    <tr>
      <td width="400"><p align="center"><strong>'.$var_encabezado4.'</strong></p></td>
    </tr>
  </tbody>
</table>';
return $encabezado;
}
function pie()
{
$pie= '<br><br><br><table height="100" aling="center" width="700" border="1" >
  <tbody align="center">
	<tr>
      <td align="center">Autorizado Por:<br><br><br><br><br>Direcci&oacute;n General<br>Firma</td>
      <td align="center">Administraci&oacute;n<br><br><br><br><br><br>Firma</td>
      <td align="center">Unidad Solicitante<br><br><br><br><br><br>Firma</td>
    </tr>

  </tbody>
</table>
<table height="100" align="center" width="700" border="0" >
  <tbody align="center">
<tr>
      
      
    </tr>

  </tbody>
</table>';
return $pie;
//<td align="left">Elaborado Por:&nbsp;'.$_SESSION['nombre'].'</td>
}

function pie_irem()
{
$pie= '<table height="50" aling="center" width="100%" border="0" >
  <tbody align="center">
	<tr>
      <td align="center">Administradora:<br>Firma</td>
      
      <td align="center">Presidenta:<br>Firma</td>
    </tr>

  </tbody>
</table>
<table height="100" align="center" width="700" border="0" >
  <tbody align="center">
<tr>
      
      
    </tr>

  </tbody>
</table>';
return $pie;
//<td align="left">Elaborado Por:&nbsp;'.$_SESSION['nombre'].'</td>
}

function pie_inzuvi()
{
$pie= '<div  style="position : fixed; top : 800px; vertical-align : bottom;">
 <table height="100" width="700" border="1" >
  <tbody>
	<tr>
		<td align="center" width="125">Unidad Solicitante<br><br><br><br><br><br>Firma y Sello</td>
      <td align="center" width="125">Administraci&oacute;n<br><br><br><br><br><br>Firma y Sello</td>
		<td align="center" width="125">Compras y Suministros<br><br><br><br><br><br>Firma y Sello</td>
		<td align="center" width="125">Autorizado Por:<br><br><br><br><br><br>Direcci&oacute;n General</td>
    </tr>
  </tbody>
</table>
<table height="100" width="700" border="0" >
  <tbody>
    <tr>
      <td align="left">Elaborado Por:&nbsp;'.$_SESSION['nombre'].'</td>
    </tr>

  </tbody>
</table>
</div>';
return $pie;
}
function pie_orden()
{
$pie= '<div  style="position : fixed; top : 850px; vertical-align : bottom;">
      <table height="180" width="800" align="center"  >
        <tbody>
          <tr valign="center">
            <td align="center" valign="top" style="text-align: center;" class="texto10"><small><strong>Presupuesto </strong><br>
                  <br><br>
                  <br>
          ___________________________<br>
         </small></td>
            <td class="texto10" align="center" valign="top" style="text-align: center;"><small><strong>Direcci&oacute;n de Administracion</strong><br>
                  <br><br>
                  <br>
          ____________________________<br>
         </small></td>
            <td align="center" class="texto10" valign="top"><div align="center"><small><strong>Direcci&oacute;n General</strong><br><br>
            </div></td>
          </tr>

		<tr valign="center">
            <td align="center" valign="top" class="texto10" style="text-align: center;"><small><strong>Elaborado Por:</strong><br>
                  <br><br>
                  <br>
          ___________________________<br>
         </small></td>
            <td align="center" class="texto10" valign="top" style="text-align: center;"><small><strong>Revisi&oacute;n y Control</strong><br>
                  <br><br>
                  <br>
          ____________________________<br>
         </small></td>
            <td align="center" class="texto10" valign="top"><div align="center"><small><strong>Beneficiario:</strong><br><br>
            NOMBRE:__________________ <br><br>
&nbsp; &nbsp; C.I.:__________________<br><br>
&nbsp; &nbsp;Firma:__________________</small></div></td>
          </tr>
        </tbody>
      </table>
</div>';       
return $pie;
}
function pie_orden_inzuvi()
{
$pie='<TABLE align="center" width="775" cellpadding="0" cellspacing="0" border="1" >
	<TR bgcolor="#CCCCCC">
		<TD align="center" colspan=5 class="texto8"><strong>FIRMAS AUTORIZADAS</strong></TD>
	</TR>
	<TR bgcolor="#CCCCCC">
		<TD align="center" width="155" class="texto8">Revisado Por</TD>
		<TD align="center" width="155" class="texto8">Gerencia de Adm&oacute;n.</TD>
		<TD align="center" width="155" class="texto8">Presupuesto</TD>
		<TD align="center" width="155" class="texto8">Vice-Pres. Gesti&oacute;n Int.</TD>
		<TD align="center" width="155" class="texto8"class="texto9">Presidencia</TD>
	</TR>
	<TR>
		<TD height="100"></TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
	</TR>
	<TR bgcolor="#CCCCCC">
		<TD align="center" class="texto8">Joel Márquez G.</TD>
		<TD align="center" class="texto8">Lcda. Eleida Urdaneta</TD>
		<TD align="center" class="texto8">Lcda. Thais Flores</TD>
		<TD align="center" class="texto8">Vice-Pres. Gesti&oacute;n Int.</TD>
		<TD align="center" class="texto8">Econ. Ivonne de Ocando</TD>
	</TR>
</TABLE>
<br>
<TABLE align="center" width="775" cellpadding="0" cellspacing="0" border="1" >
	<TR>
		<TD  bgcolor="#CCCCCC" align="center" width="385" class="texto8"></TD>
		<TD align="center" width="390" class="texto8">Beneficiario o Persona Autorizada</TD>
	</TR>
	<TR>
		<TD align="center" height="40" width="385" class="texto8"></TD>
		<TD align="center" width="390" class="texto8"></TD>
	</TR>
	<TR>
		<TD bgcolor="#CCCCCC" align="center" width="385" class="texto8">Contralor</TD>
		<TD align="center" width="390" class="texto8">Nombre Completo</TD>
	</TR>
	<TR>
		<TD align="center" height="40" width="385" class="texto8"></TD>
		<TD align="center" width="390" class="texto8"></TD>
	</TR>
	<TR>
		<TD bgcolor="#CCCCCC"align="center" width="385" class="texto8">Revisor</TD>
		<TD align="center" width="390" class="texto8">Recibí Comforme</TD>
	</TR>
	<tr>
      <td colspan="2" class="texto8">Elaborado Por:&nbsp;'.$_SESSION['nombre'].'</td>
    </tr>
</TABLE>';
return $pie;
}

function pie_orden_inzuvi33()
{
$pie='<TABLE align="center" width="775" cellpadding="0" cellspacing="0" border="1" >
	<TR bgcolor="#CCCCCC">
		<TD align="center" colspan=5 class="texto8"><strong>FIRMAS AUTORIZADAS</strong></TD>
	</TR>
	<TR bgcolor="#CCCCCC">
		<TD align="center" width="155" class="texto8">Revisado Por</TD>
		<TD align="center" width="155" class="texto8">Gerencia de Adm&oacute;n.</TD>
		<TD align="center" width="155" class="texto8">Presupuesto</TD>
		<TD align="center" width="155" class="texto8">Vice-Pres. Gesti&oacute;n Int.</TD>
		<TD align="center" width="155" class="texto8"class="texto9">Presidencia</TD>
	</TR>
	<TR>
		<TD height="100"></TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
	</TR>
	<TR bgcolor="#CCCCCC">
		<TD align="center" class="texto8">Joel Márquez G.</TD>
		<TD align="center" class="texto8">Lcda. Eleida Urdaneta</TD>
		<TD align="center" class="texto8">Lcda. Thais Flores</TD>
		<TD align="center" class="texto8">Vice-Pres. Gesti&oacute;n Int.</TD>
		<TD align="center" class="texto8">Econ. Ivonne de Ocando</TD>
	</TR>
</TABLE>';
return $pie;
}


function pie_orden_inzuvi2()
{
$pie='
<TABLE align="center" width="780" cellpadding="0" cellspacing="0" border="1" style="position : fixed; top : 950px;">
	<TR bgcolor="#CCCCCC">
		<TD align="center" width="195" class="texto9">Elaborado Por</TD>
		<TD align="center" width="195" class="texto9">Gerencia de Finanzas.</TD>
		<TD align="center" width="195" class="texto9">Presupuesto</TD>
		<TD align="center" width="195" class="texto9"class="texto9">Presidencia</TD>
	</TR>
	<TR>
		<TD height="100"></TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
	</TR>
	<TR bgcolor="#CCCCCC">
		<TD align="center" class="texto9">Firma y Sello</TD>
		<TD align="center" class="texto9">Lcda. Marylis Rivas</TD>
		<TD align="center" class="texto9">Lcda. Thais Flores</TD>
		<TD align="center" class="texto9">Econ. Ivonne de Ocando</TD>
	</TR>
	<tr>
      <td colspan="4" class="texto8">Elaborado Por:&nbsp;'.$_SESSION['nombre'].'</td>
    </tr>
</TABLE>';
return $pie;
}
function pie_orden_inzuvi_odp_obras()
{
$pie='
<TABLE align="center" width="775" cellpadding="0" cellspacing="0" border="1" style="position : fixed; top : 950px;">
	<TR bgcolor="#CCCCCC">
		<TD align="center" width="155" class="texto9">Secretaria de Administración Gobernacion del Estado Zulia</TD>
		<TD align="center" width="155" class="texto9">Gerencia de Finanzas.</TD>
		<TD align="center" width="155" class="texto9">Presupuesto</TD>
		<TD align="center" width="155" class="texto9"class="texto9">Presidencia</TD>
	</TR>
	<TR>
		<TD height="100"></TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
	</TR>
	<TR bgcolor="#CCCCCC">
		<TD align="center" class="texto9">Econ. Zulay Medina</TD>
		<TD align="center" class="texto9">Lcda. Marylis Rivas</TD>
		<TD align="center" class="texto9">Lcda. Thais Flores</TD>
		<TD align="center" class="texto9">Econ. Ivonne de Ocando</TD>
	</TR>
	<tr>
      <td colspan="5" class="texto8">Elaborado Por:&nbsp;'.$_SESSION['nombre'].'</td>
    </tr>
</TABLE>';
return $pie;
}

function pie_odp()
{
$pie= '<div  style="position : fixed; top : 850px; vertical-align : bottom;">
      <table height="180" width="700" align="center"  style="border-bottom-style : outset; border-left-style : outset; border-right-style : outset; border-top-style : outset;">
        <tbody>
          <tr valign="center">
            <td align="center" valign="top" style="text-align: center;"><small><strong>Finanzas </strong><br>
                  <br><br>
                  <br>
          ___________________________<br>
         </small></td>
            <td align="center" valign="top" style="text-align: center;"><small><strong>Direcci&oacute;n de Administraci&oacute;n</strong><br>
                  <br><br>
                  <br>
          ____________________________<br>
         </small></td>
            <td align="center" valign="top"><div align="center"><small><strong>Direcci&oacute;n General</strong><br><br>
            </div></td>
          </tr>

		<tr valign="center">
            <td align="center" valign="top" style="text-align: center;"><small><strong>Elaborado Por:</strong><br>
                  <br><br>
                  <br>
          ___________________________<br>
         </small></td>
            <td align="center" valign="top" style="text-align: center;"><small><strong>Revisi&oacute;n y Control</strong><br>
                  <br><br>
                  <br>
          ____________________________<br>
         </small></td>
            <td align="center" valign="top"><div align="center"><small><strong>Beneficiario:</strong><br><br>
            NOMBRE:__________________ <br><br>
&nbsp; &nbsp; C.I.:__________________<br><br>
&nbsp; &nbsp;Firma:__________________</small></div></td>
          </tr>
        </tbody>
      </table>
</div>';       
return $pie;
}
function datos_de_orden($var_nomemp,$var_direccion,$var_fecha,$var_nom_und,$var_monto_orden,$var_codigo,$var_dias_credito)
{
?>
	<tr><td>SeÃ±or(es): <?echo $var_nomemp?></td></tr>
	<tr><td>Direccion: <?echo $var_direccion?></td></tr> 
	
	<tr><td>Fecha de Emision: <?php echo fecha($var_fecha); ?></td></tr>
        <tr><td>Unidad Solicitante: <?php echo $var_nom_und; ?></td></tr>
	<tr><td>Monto Bs: <?php echo $var_monto_orden; ?></td></tr>
	<tr><td>Nro Control: <?php echo $var_codigo; ?></td></tr>
	<tr><td>Dias de Credito: <?php echo $var_dias_credito;?></td></tr>
	
<?php
}
function materiales_detalles($descripcion,$cantidad,$var_precio,$var_iva,$var_total,$var_total_gen)
{?>
	
	<td><? echo $descripcion; ?>
        <div align="center"></div></td><td><? echo $cantidad;  ?>
        <td><?  ?>
	<div align="center"></div></td>
	<td><? echo $var_precio; ?>
	<div align="center"></div></td>
	<td><? echo $var_iva; ?>
	<div align="center"></div></td>
	<td><? echo $var_total; ?>
	<div align="center"></div></td>
		 
<?php
}

function partidas_presupuestarias($var_sector,$var_programa,$var_actividad,$var_partida,$var_descripcion, $var_monto3)
{?>
	<table width="749" border="1" align="center" cellpadding="2" cellspacing="2" class="">
        <tr class="">
          <td width="97"><div align="center">Programatica</div></td>
	  <td width="72"><div align="center">Cuenta</div></td>
	  <td width="210"><div align="center">Descripcion de la Cuenta</div></td>
          <td width="47"><div align="center">Monto</div></td>
	 </tr>

	<td><? echo $var_sector.".".$var_programa.".".$var_actividad; ?>
        <div align="center"></div></td>
	<td><? echo $var_partida; ?>
        <td><? echo $var_descripcion; ?>
        <div align="center"></div></td>
        <td><? echo $var_monto3; ?>
        <div align="center"></div></td>
        </table>
	
<?php
}

function materiales_detalles2($var_cod_prod,$var_descripcion,$var_cantidad,$var_cantidad_des)
{?>
	
	<td><? echo $var_cod_prod; ?>
        <div align="center"></div></td>
	<td><? echo $var_descripcion;?>
        <div align="center"></div>
	<td><? echo $var_cantidad ?>
	<div align="center"></div></td>
	<td><? echo $var_cantidad_des; ?>
	<div align="center"></div></td>
		 
<?php
}
function phpmkr_fetch_row($rs)
{
	return mysql_fetch_row($rs);
}
function phpmkr_free_result($rs)
{
	mysql_free_result($rs);
}
function phpmkr_data_seek($rs, $cnt)
{
	mysql_data_seek($rs, $cnt);
}
function ewBuildSql($sSelect, $sWhere, $sGroupBy, $sHaving, $sOrderBy, $sFilter, $sSort) {
	$sDbWhere = $sWhere;
	if ($sDbWhere <> "") {
		$sDbWhere = "(" . $sDbWhere . ")";
	}
	if ($sFilter <> "") {
		if ($sDbWhere <> "") $sDbWhere .= " AND ";
		$sDbWhere .= "(" . $sFilter . ")";
	}
	$sDbOrderBy = $sOrderBy;
	if ($sSort <> "") {
		$sDbOrderBy = $sSort;
	}
	$sSql = $sSelect;
	if ($sDbWhere <> "") {
		$sSql .= " WHERE " . $sDbWhere;
	}
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	If ($sDbOrderBy <> "") {
		$sSql .= " ORDER BY " . $sDbOrderBy;
	}
	return $sSql;
}
function AdjustSql($str) {
	$sWrk = trim($str);
	$sWrk = addslashes($sWrk);
	return $sWrk;
}
function cut_string($string, $charlimit)
{
	
	if(substr($string,$charlimit-1,1) != ' ')
	{
		$string = substr($string,'0',$charlimit);
		$array = explode(' ',$string);
		array_pop($array);
		$new_string = implode(' ',$array);
	
		return $new_string.' ...';
	}
	else
	{
		return substr($string,'0',$charlimit-1).' ...';
	}
}


?>
