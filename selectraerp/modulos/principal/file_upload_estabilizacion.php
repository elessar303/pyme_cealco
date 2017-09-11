<?php
ini_set("memory_limit","1024M");
ini_set("upload_max_filesize","2000M");
set_time_limit(0);

session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
include("../../../generalp.config.inc.php");
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");

$comunes = new ConexionComun();
$comun = new Comunes();
$correlativos = new Correlativos();
$login = new Login();

function completar($cad,$long){
        $temp=$cad;
        for($i=1;$i<=$long-strlen($cad);$i++){
            $temp=" ".$temp;
        }
        return $temp;
        
}
function completarD($cad,$long){
        $temp=$cad;
        for($i=1;$i<=$long-strlen($cad);$i++){
            $temp=$temp." ";
        }
        return $temp; 
}
?>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-timepicker-addon.js"></script>
<?php
$pyme=DB_SELECTRA_FAC;
$pos=POS;
//PATHS
$sql="SELECT codigo_siga, servidor FROM $pyme.parametros_generales";
$array_sucursal=$comunes->ObtenerFilasBySqlSelect($sql);
foreach ($array_sucursal as $key => $value) {
$sucursal=$value['codigo_siga']; 
$servidor=$value['servidor']; 
}

$ruta_master=$_SESSION['ROOT_PROYECTO']."/selectraerp/uploads";

$path_local=$ruta_master."/estabilizacion//";

if ($_FILES['archivo_productos']["error"] > 0)
      {
      echo" <script type='text/javascript'>
                alert('Error Al Subir Archivo');
                    history.go(-1);       
                   </script>";
        exit();
      }
if(!empty($_FILES['archivo_productos']))
{
    $string=".csv";
    if(strnatcasecmp($string,substr($_FILES['archivo_productos']['name'],-4))!=0){

    echo"   <script type='text/javascript'>
                alert('Error El Formato De Archivo No Es Valido, Unicamente Extensiones CSV');
                    history.go(-1);       
                   </script>";exit();
    }

   $nombre=$_FILES['archivo_productos']['name'];
      if(move_uploaded_file($_FILES['archivo_productos']['tmp_name'],$path_local.$_FILES['archivo_productos']['name'])){
        }else{
                    echo" <script type='text/javascript'>
                     alert('Error Al Cargar Archivo');
                     history.go(-1);       
                    </script>";
                    exit();
            
        }
        chmod($path_local.$_FILES['archivo_productos']['name'], 0777);

//*********************************JUNIOR AYALA**************************************************       
$directorio=dir("$path_local");
$login = new Login();
$cod_usuario=$login->getIdUsuario();
            $archiv2=substr($nombre,0,1);
            if ($archiv2!='.') {
            //Datos del Archivo a actualizar
            $sql_truncate="TRUNCATE TABLE productos_centrales;";
            $comunes->Execute2($sql_truncate);

                $filas=file($path_local.$nombre);
                $i=0;
                $rs_ins=0;
                //Recorro el archivo linea por linea
                while($filas[$i]!=NULL) {
                    $row = $filas[$i];
                    $values = explode(";",$row);
                    //Valido que el nombre del producto no venga en blanco
                    if(!empty($values[1])) {
                    $sql_detalle="INSERT INTO productos_centrales(codigo_barras) VALUES ('".$values[0]."')";
                    //echo $sql_detalle; exit();
                    $comunes->Execute2($sql_detalle);

                    $validar_item="SELECT id_item  FROM item WHERE codigo_barras='".$values[0]."'";
                    $array_item=$comunes->ObtenerFilasBySqlSelect($validar_item);
                    $filas_item=$comunes->getFilas($array_item);
                    if($filas_item>0)
                        {   
                            if($values[10]=='Unidad'){
                                $values[10]=1;
                            }
                            if($values[9]=='Seleccione'){
                                $values[9]=1;
                            }
                            $actualizar_item_pyme="UPDATE item SET descripcion1='".$values[1]."', unidad_empaque='".$values[2]."', seriales=".$values[3].",cod_departamento=".$values[4].", cod_grupo=".$values[5].", cantidad_bulto=".$values[6].", kilos_bulto=".$values[7].", estatus='A', id_marca=".$values[8].", unidadxpeso=".$values[9].", unidad_venta='".$values[10]."', pesoxunidad=".$values[11].", producto_vencimiento='".$values[12]."', iva=".$values[13]."  WHERE codigo_barras='".$values[0]."'";

                            $iva=($values[13])/100;
                            $imp = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM $pos.taxes WHERE RATE='$iva' ");

                            //Unidad de Venta en el POS (Productos pesados) si es 2 es Pesado
                            if ($values[10]==2) {
                                $pesado=1;
                            }elseif ($values[10]==1 || $values[10]==0) {
                                $pesado=0;
                            }

                            $actualizar_item_pos="UPDATE $pos.products SET NAME='".$values[1]."', TAXCAT='".$imp[0]["CATEGORY"]."', ISSCALE='".$pesado."' WHERE CODE='".$values[0]."'";

                            //echo $actualizar_item_pos; exit();
                            $comunes->Execute2($actualizar_item_pyme);
                            $comunes->Execute2($actualizar_item_pos);

                            $validar_marca="SELECT id FROM marca WHERE id='".$values[8]."'";
                            $array_marca=$comunes->ObtenerFilasBySqlSelect($validar_marca);
                            $filas_marca=$comunes->getFilas($array_marca);

                            if($filas_marca>0){
                                $actualizar_marca="UPDATE marca SET marca='".$values[14]."' WHERE id=".$values[8]."";
                                $comunes->Execute2($actualizar_marca);
                            }elseif($filas_marca==0 && $values[8]!=0){
                                $insert_marca="INSERT INTO marca(id, marca) VALUES (".$values[8].",'".$values[14]."')";
                                $comunes->Execute2($insert_marca);
                            }

                            $validar_grupo="SELECT cod_grupo FROM grupo WHERE cod_grupo=".$values[5]."";
                            $array_grupo=$comunes->ObtenerFilasBySqlSelect($validar_grupo);
                            $filas_grupo=$comunes->getFilas($array_grupo);



                            if ($filas_grupo>0) {
                                $actualizar_grupo="UPDATE grupo SET descripcion='".trim($values[15])."',id_rubro=".$values[16].",restringido=".$values[17].",cantidad_rest=".$values[18].",dias_rest=".$values[19].", grupopos='".trim($values[20])."' WHERE cod_grupo=".$values[5]."";
                                $comunes->Execute2($actualizar_grupo);
                            }else if ($filas_grupo==0) {
                                $insert_grupo="INSERT INTO grupo (`cod_grupo`, `descripcion`, `id_rubro`, `restringido`, `cantidad_rest`, `dias_rest`, `grupopos`) VALUES (".$values[5].",'".trim($values[15])."',".$values[16].",".$values[17].",".$values[18].",".$values[19].",'".trim($values[20])."')";
                                $comunes->Execute2($insert_grupo);
                            }

                            $validar_grupo_pos="SELECT ID FROM $pos.categories WHERE ID='".trim($values[20])."'";
                            $array_grupo_pos=$comunes->ObtenerFilasBySqlSelect($validar_grupo_pos);
                            $filas_grupo_pos=$comunes->getFilas($array_grupo_pos);

                            if ($filas_grupo_pos>0) {
                                $actualizar_grupo_pos="UPDATE $pos.categories SET NAME='".$values[15]."',PROTECTED=".$values[17].",QUANTITYMAX=".$values[18].",TIMEFORTRY=".$values[19]." WHERE ID='".trim($values[20])."'";
                                
                                $comunes->Execute2($actualizar_grupo_pos);
                            }else if ($filas_grupo_pos==0) {
                                $insert_grupo_pos="INSERT INTO $pos.categories (`ID`, `NAME`, `PROTECTED`,`QUANTITYMAX`, `TIMEFORTRY`) VALUES ('".trim($values[20])."','".$values[15]."',".$values[17].",".$values[18].",".$values[19].")";
                                $comunes->Execute2($insert_grupo_pos);
                            }


                        }
                    if($filas_item==0)
                        {       
                            if($values[10]=='Unidad'){
                                $values[10]=1;
                            }
                            if($values[9]=='Seleccione'){
                                $values[9]=1;
                            }
                            $horaActual= date("Y-m-d H:i:s");
                            $id_pos_nuevo=$values[1].$horaActual; 
                            $idpos=$comun->codigo_pos($id_pos_nuevo);
                            $nro_producto = $correlativos->getUltimoCorrelativo("cod_producto", 1, "si", "P");
                            $insertar_item="INSERT INTO item (cod_item, codigo_barras , descripcion1 ,  unidad_empaque , seriales , cod_departamento ,  cod_grupo , cantidad_bulto ,  kilos_bulto ,  id_marca ,  unidadxpeso ,  unidad_venta , pesoxunidad ,  producto_vencimiento, iva, estatus, cod_item_forma, fecha_creacion, itempos) VALUES ('".$nro_producto."','".$values[0]."', '".$values[1]."', '".$values[2]."', ".$values[3].", ".$values[4].", ".$values[5].", '".$values[6]."', ".$values[7].",".$values[8].", ".$values[9].", '".$values[10]."',".$values[11].", '".$values[12]."', ".$values[13].",'A', 1,CURRENT_TIMESTAMP,'".$idpos."')";
                            
                            $comunes->Execute2($insertar_item);
                            $nro_producto = $correlativos->getUltimoCorrelativo("cod_producto", 1, "no", "");
                            $comunes->Execute2("UPDATE correlativos SET contador = '".$nro_producto."' WHERE campo = 'cod_producto'");

                            $validar_marca="SELECT id FROM marca WHERE id='".$values[8]."'";
                            $array_marca=$comunes->ObtenerFilasBySqlSelect($validar_marca);
                            $filas_marca=$comunes->getFilas($array_marca);

                            if($filas_marca>0){
                                $actualizar_marca="UPDATE marca SET marca='".$values[14]."' WHERE id=".$values[8]."";
                                $comunes->Execute2($actualizar_marca);
                            }elseif($filas_marca==0 && $values[8]!=0){
                                $insert_marca="INSERT INTO marca(id, marca) VALUES (".$values[8].",'".$values[14]."')";
                                $comunes->Execute2($insert_marca);
                            }

                             $validar_grupo="SELECT cod_grupo FROM grupo WHERE cod_grupo=".$values[5]."";
                            $array_grupo=$comunes->ObtenerFilasBySqlSelect($validar_grupo);
                            $filas_grupo=$comunes->getFilas($array_grupo);



                            if ($filas_grupo>0) {
                                $actualizar_grupo="UPDATE grupo SET descripcion='".trim($values[15])."',id_rubro=".$values[16].",restringido=".$values[17].",cantidad_rest=".$values[18].",dias_rest=".$values[19].", grupopos='".trim($values[20])."' WHERE cod_grupo=".$values[5]."";
                                $comunes->Execute2($actualizar_grupo);
                            }else if ($filas_grupo==0) {
                                $insert_grupo="INSERT INTO grupo (`cod_grupo`, `descripcion`, `id_rubro`, `restringido`, `cantidad_rest`, `dias_rest`, `grupopos`) VALUES (".$values[5].",'".trim($values[15])."',".$values[16].",".$values[17].",".$values[18].",".$values[19].",'".trim($values[20])."')";
                                $comunes->Execute2($insert_grupo);
                            }

                            $validar_grupo_pos="SELECT ID FROM $pos.categories WHERE ID='".trim($values[20])."'";
                            $array_grupo_pos=$comunes->ObtenerFilasBySqlSelect($validar_grupo_pos);
                            $filas_grupo_pos=$comunes->getFilas($array_grupo_pos);

                            if ($filas_grupo_pos>0) {
                                $actualizar_grupo_pos="UPDATE $pos.categories SET NAME='".$values[15]."',PROTECTED=".$values[17].",QUANTITYMAX=".$values[18].",TIMEFORTRY=".$values[19]." WHERE ID='".$values[20]."'";
                                $comunes->Execute2($actualizar_grupo_pos);
                            }else if ($filas_grupo_pos==0) {
                                $insert_grupo_pos="INSERT INTO $pos.categories (`ID`, `NAME`, `PROTECTED`,`QUANTITYMAX`, `TIMEFORTRY`) VALUES ('".trim($values[20])."','".$values[15]."',".$values[17].",".$values[18].",".$values[19].")";
                                $comunes->Execute2($insert_grupo_pos);
                            }
                        }
                    }
                    
                $i++;
                }
            }
            $sql_arreglar_productos="UPDATE item SET unidadxpeso='5' where unidadxpeso='' or unidadxpeso='Seleccione' or unidadxpeso=0;
            UPDATE item SET unidad_venta='1' where unidad_venta='' or unidad_venta='Unidad';
            UPDATE item SET id_marca=1 WHERE id_marca=0 or id_marca='';
            UPDATE item SET cantidad_bulto=1 WHERE cantidad_bulto=0;
            UPDATE item SET kilos_bulto=1 WHERE kilos_bulto=0";
            $comunes->Execute2($sql_arreglar_productos);
            
            unlink($path_local.$nombre);
            echo" <script type='text/javascript'>
                     alert('Exito: Sincronizacion de Productos de Sede Central Correcta');
                     history.go(-1);       
                    </script>";
                    exit();
}
?>
