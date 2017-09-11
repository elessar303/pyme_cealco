<?
			require_once '../lib/common.php';
			//echo "Pase por aqui"; exit(0);
			$cantidad=@$_GET['cantidad'];
			$id_det=@$_GET['id_det'];
			$id=@$_GET['id'];
			$cod_centro=@$_GET['centro'];
			$unidad=@$_GET['unidad'];
			$codigo=@$_GET['codigo'];
			$conexion=conexion();
			$consulta="update requisiciones_det set cantidad='".$cantidad."' where cod_requisicion_det='".$id_det."' and cod_requisicion='".$id."' and cod_centro='".$cod_centro."' and unidad='".$unidad."' and cod_material='".$codigo."'";
			
			$resultado=mysql_query($consulta) or die(mysql_error());
			//echo $resultado;
			//exit(0);
			

?>