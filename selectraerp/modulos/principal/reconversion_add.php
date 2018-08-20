<?php
include("../../libs/php/clases/almacen.php");
require_once("../../../general.config.inc.php");
require_once("../../../generalp.config.inc.php");
$almacen = new Almacen();
error_reporting(-1);

$pyme=DB_SELECTRA_FAC;
$pos=POS;

if(isset($_POST["aceptar"])){

	//Leemos las variables enviadas por el formulario
	$tabla=trim($_POST['tabla']);
	$columna=trim($_POST['columna']);

	//Consultamos si ya se le aplico la formula a esta columna
	$sql="SELECT * from $pyme.reconversion_monetaria WHERE columna='".$columna."' AND tabla='".$pos.".".$tabla."'";
	$array_rs1=$almacen->ObtenerFilasBySqlSelect($sql);

	$sql="SELECT * from $pyme.reconversion_monetaria WHERE columna='".$columna."' AND tabla='".$pyme.".".$tabla."'";
	$array_rs2=$almacen->ObtenerFilasBySqlSelect($sql);

	//Preguntamos si ya se realizo la reconversion, de ser negativo se corre el proceso y se inserta un regitro en la tabla reconversion para que no se vuelva a correr el proceso sobre la misma columna
	if(count($array_rs1)>0 || count($array_rs2)>0){

		if(count($array_rs1)>0){
			$bd_ver=$pos;
		}else{
			$bd_ver=$pyme;
		}
		echo "";
		echo '<script language="javascript" type="text/JavaScript">';
		echo 'alert("Ya se realizo la reconversion a la Columna: '.$columna.' de la Tabla: '.$tabla.' en la Base de Datos: '.$bd_ver.'"); history.back(1); ';
		echo '</script>';
		exit();
	}else{

		//Seleccionamos el campo a actualizar y lo agrupamos para tener la data para el update
		$sql="SELECT $columna FROM $pyme.$tabla GROUP BY $columna";
		//echo $sql."<br>";
		$array_pr1 = $almacen->ObtenerFilasBySqlSelect($sql);

		$sql2="SELECT $columna FROM $pos.$tabla GROUP BY $columna";
		//echo $sql."<br>";
		$array_pr2 = $almacen->ObtenerFilasBySqlSelect($sql2);

		if(!$array_pr1 && !$array_pr2){
			echo '<script language="javascript" type="text/JavaScript">';
			echo 'alert("Error en la consulta: '.$sql.' y '.$sql2.'"); history.back(1); ';
			echo '</script>';
			exit();
		}

		if(!$array_pr1 && $array_pr2){
			$array_pr=$array_pr2;
			$bd=$pos;
		}else if(!$array_pr2 && $array_pr1){
			$array_pr=$array_pr1;
			$bd=$pyme;
		}else if($array_pr1 && $array_pr2){
			echo '<script language="javascript" type="text/JavaScript">';
			echo 'alert("La tabla: '.$tabla.' se encuentra tanto en la Base de Datos PYME como POS, elimine la tabla a donde corresponda y corra el script nuevamente"); history.back(1); ';
			echo '</script>';
			exit();
		}


		//Si existe la tabla y la columna se hace el proceso
		if(count($array_pr)>0){

			foreach ($array_pr as $value) {
				$pr=$value[$columna];
				$vr = $pr / 100000;

				$pos= strpos($vr,'.');	
				if ($pos > -1){	
					$particion=explode('.',$vr);
					$parte_entera=$particion['0'];
					$parte_decimal=".".substr($particion['1'],0,2);
				} else {
					$parte_entera=$vr;
					$parte_decimal="";
				}	

				$numero_final=$parte_entera.$parte_decimal;
				$pos= strpos($numero_final,'E');	
				if ($numero_final < 0.01 || $pos > -1){
				   $numero_final = 0.01;
				}

				echo "Numero antes de la reconversion: ".$pr." - Despues de la reconversion: ".$numero_final."<br>";
				$sql="UPDATE $bd.$tabla SET $columna=$numero_final WHERE $columna=$pr";
				$update=$almacen->Execute2($sql);
			    //echo $sql."<br><br>";
			}
		}else{
			echo "";
			echo '<script language="javascript" type="text/JavaScript">';
			echo 'alert("La columna: '.$columna.' no se encuentra en la tabla: '.$tabla.'"); history.back(1); ';
			echo '</script>';
			exit();
		}
		//Despues de terminar los updates catulizamos la tabla reconversion para llevar el registro de que ya se hizo
		$sql="INSERT INTO reconversion_monetaria (tabla, columna) VALUES ('".$bd.".".$tabla."', '".$columna."')";
		$insert=$almacen->Execute2($sql);
		echo "";
		echo '<script language="javascript" type="text/JavaScript">';
		echo 'alert("Reconversion a columna lista");history.back(1); ';
		echo '</script>';
		exit();
			//echo "realizar reconversion";
	}

}         


// Cargando localidad en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM localidad" );
foreach ($campos_comunes as $key => $item) {
	$arraySelectOption[] = $item["id"];
	$arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_localidad", $arraySelectOption);
$smarty->assign("option_output_localidad", $arraySelectoutPut);

?>
