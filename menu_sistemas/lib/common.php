<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/pyme/generalp.config.inc.php');
if($_SESSION['ROOT_PROYECTO']=="")
{
	require_once('../generalp.config.inc.php');
}
else 
{
	require_once($_SESSION['ROOT_PROYECTO'].'/generalp.config.inc.php');
}

function envia_array($array) {
    $tmp = serialize($array);
    $tmp = urlencode($tmp);
    return $tmp;
}

function recibe_array($url_array) {
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
    return $tmp;
}

class bd {

    private $servidor = DB_HOST;
    private $usuario = DB_USUARIO;
    private $clave = DB_CLAVE;
    private $base;

    public function bd($var) {
        $this->base = $var;
    }

    public function create_database($bdnueva) {
        $conexion = new mysqli($this->servidor, $this->usuario, $this->clave);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            return;
        }
        $sentencia = "create database " . $bdnueva;
        $resultado = $conexion->query($sentencia);
    }

    private function connect() {
        $conexion = new mysqli($this->servidor, $this->usuario, $this->clave, $this->base);
        if (mysqli_connect_errno()) {
            printf("Fallo de conexion: %s\n", mysqli_connect_error());
        }
        return $conexion;
    }

    public function query($sentencia) {
        $conexion = $this->connect();
        $resultado = $conexion->query($sentencia);

        if (!$resultado) {
            printf("Error: %s\n", $conexion->error);
        }
        $conexion->close();
        return $resultado;
    }

    public function multi_query($sentencia) {
        $conexion = $this->connect();
        $resultado = $conexion->multi_query($sentencia);

        if (!$resultado) {
            printf("Error: %s\n", $conexion->error);
        }
        $conexion->close();
        return $resultado;
    }

}

class procesos {

    public function final_chequera($banco, $chequera, $cheque) {
        $conexion = new bd(DB_SELECTRA_DEFAULT);
        $consulta = "select max(cheque) as maximo from cheques where banco='" . $banco . "' and chequera='" . $chequera . "'";
        $resultado = $conexion->query($consulta);
        $fila = $resultado->fetch_assoc();
        if ($fila['maximo'] == $cheque) {
            $consulta = "update chequera set situacion='C' where banco='" . $banco . "' and numero='" . $chequera . "'";
            $resultado = $conexion->query($consulta);
            return 1;
        } else {
            return 0;
        }
    }
}



function btn($tipo, $url, $accion=0) { // Accion 0=location / 1=Form url = form name / 2 JS ** 3 reset **terminar**
    switch ($tipo) {
;

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

    switch ($accion) {
;

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

    echo '<table style="cursor: pointer;" class="btn_bg" onClick="javascript:' . $js . '" name="buscar" border="0" cellpadding="0" cellspacing="0">

		<tr>

		  <td style="padding: 0px;" align="right"><img src="../imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>

		  <td class="btn_bg"><img src="../imagenes/' . $icon . '.gif" width="16" height="16" /></td>

		  <td class="btn_bg" nowrap style="padding: 0px 4px;">' . $name . '</td>

		  <td style="padding: 0px;" align="left"><img src="../imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>

		</tr>

	  </table>';
}

function btn2($nombre, $url, $icono) {

    echo '<table style="cursor: pointer;" class="btn_bg" onClick="javascript:' . $url . '" name="buscar" border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td style="padding: 0px;" align="right"><img src="imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;"/></td>
		  <td class="btn_bg"><img src="imagenes/' . $icono . '" width="16" height="16"/></td>
		  <td class="btn_bg" nowrap style="padding: 0px 4px;">' . $nombre . '</td>
		  <td style="padding: 0px;" align="left"><img src="imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;"/></td>
		</tr>
	  </table>';

    return $encabezado;
}


            function fecha($value) { // fecha de YYYY/MM/DD a DD/MM/YYYY
                if (!empty($value))
                    return substr($value, 8, 2) . "/" . substr($value, 5, 2) . "/" . substr($value, 0, 4);
            }

            function fecha_en($value) { // fecha de YYYY/MM/DD a DD/MM/YYYY
                if (!empty($value))
                    return substr($value, 5, 2) . "/" . substr($value, 8, 2) . "/" . substr($value, 0, 4);
            }

            function fechahora($value) { // fecha de 'YYYY-MM-DD HH:MM:SS' a 'DD-MM-YYYY HH:MM:SS'
                if (!empty($value))
                    return substr($value, 8, 2) . "/" . substr($value, 5, 2) . "/" . substr($value, 0, 4) . " " . substr($value, 11, 8);
            }

            function fecha_sql($value) { // fecha de DD/MM/YYYY a YYYYY/MM/DD
                return substr($value, 6, 4) . "-" . substr($value, 3, 2) . "-" . substr($value, 0, 2);
            }

            function fechahora_sql($value) { // fecha de 'DD-MM-YYYY HH:MM:SS' a 'YYYY-MM-DD HH:MM:SS'
                return substr($value, 6, 4) . "-" . substr($value, 3, 2) . "-" . substr($value, 0, 2) . " " . substr($value, 11, 8);
            }

            function tiempo($value) { // de 'YYYY-MM-DD HH:MM:SS' a 'HH:MM:SS'
                return substr($value, 11, 8);
            }

            function numero($value, $dec=0) {
                if (!empty($value))
                    return number_format($value, 2, ',', '.');
            }

function mesaletras($mes){

$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
return $meses[intval($mes)];

}

function getDiaFinMes($mes,$anio){
    return cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
}

function getDiaSemanaFecha($fechaSQL){
    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
    $diaSemana = $dias[date('N', strtotime($fechaSQL))-1];
    return $diaSemana;
}

function getColumnasDiasMontoPuntoVenta($mes,$anio){
    $nDias = getDiaFinMes($mes,$anio);

    $columnas = "";    

    for($i=1;$i <= $nDias;$i++){
        $dia = str_pad($i, 2, "0", STR_PAD_LEFT);
        $columnas .= "sum((case when vp.fecha='".$anio."-".$mes."-".$dia."' then vp.unidad else 0 end)) as unidades_$anio$mes".$dia.",";
       // $columnas .= "sum((case when vp.fecha='".$anio."-".$mes."-".$dia."' then (vp.total_kilos*0.001) else 0 end)) as toneladas_$anio$mes".$dia.",";
        $columnas .= "sum((case when vp.fecha='".$anio."-".$mes."-".$dia."' then (0.00*0.001) else 0 end)) as toneladas_$anio$mes".$dia.",";
        $columnas .= "sum((case when vp.fecha='".$anio."-".$mes."-".$dia."' then (vp.unidad*(vp.precio+vp.iva)) else 0 end)) as total_$anio$mes".$dia.",";
        
    }

    return $columnas;
}

?>
