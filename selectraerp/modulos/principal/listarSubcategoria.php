<?php
include("../../libs/php/clases/ConexionComun.php");
include("../../libs/php/clases/comunes.php");
include("../../../generalp.config.inc.php");
require_once("../../config.ini.php");
$comunes = new Comunes();

$id_rubro = $_POST["idCarga"];
echo $sql="select * from sub_grupo where cod_grupo = ".$id_rubro;
$campos = $comunes->ObtenerFilasBySqlSelect($sql);
foreach ($campos as $filas){
?>
<option value= "<?php echo $filas['id_sub_grupo']; ?>"><?php echo $filas['descripcion']; ?> </option>
<?php }
?>