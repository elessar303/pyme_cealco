<?php
ini_set("memory_limit","1024M");
ini_set("upload_max_filesize","2000M");

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
$correlativos = new Correlativos();
$comun = new Comunes();
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
<script language="JavaScript">

function actualizar_precio(prod, precio, trans)
{
    if(prod.value!=''){
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=actualizar_precio_producto&prod="+prod+"&precio="+precio+"&trans="+trans,
            success: function(data){
                if(data == "NO EXISTE EL PRODUCTO");
                $("#"+nom).html(data);
            }
        });
    }
}
</script>
<?php
$pos=POS;
$pyme=DB_SELECTRA_FAC;
//PATHS
$sql="SELECT codigo_siga, servidor FROM $pyme.parametros_generales";
$array_sucursal=$comunes->ObtenerFilasBySqlSelect($sql);
foreach ($array_sucursal as $key => $value) {
$sucursal=$value['codigo_siga']; 
$servidor=$value['servidor']; 
}

$ruta_master=$_SESSION['ROOT_PROYECTO']."/selectraerp/uploads";

$path_local=$ruta_master."/temporal_subida_producto//";

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
    $string=".txt";
    if(strnatcasecmp($string,substr($_FILES['archivo_productos']['name'],-4))!=0)
    {
        echo"   <script type='text/javascript'>
                alert('Error El Formato De Archivo No Es Valido, Unicamente Extensiones TXT');
                    history.go(-1);       
                   </script>";exit();
    }

    $nombre=$_FILES['archivo_productos']['name'];
    if(move_uploaded_file($_FILES['archivo_productos']['tmp_name'],$path_local.$_FILES['archivo_productos']['name']))
    {
    }
    else
    {
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
    $sucursal1= $comunes->ObtenerFilasBySqlSelect("SELECT estado from parametros_generales");
    $estado_punto = $sucursal1[0]['estado'];
    //echo $estado_punto; exit();

    while ($archivo = $directorio->read())
    {
        $archiv2=substr($nombre,0,1);
        if ($archiv2!='.') 
        {
            //$comunes->BeginTrans();
            //Datos del Archivo a actualizar
            $sql_verificar_archivo="SELECT * FROM sincronizacion_productos WHERE nombre_archivo='".$nombre."'";
            $array_verificar_archivo=$comunes->ObtenerFilasBySqlSelect($sql_verificar_archivo);
            $filas_verificar_archivo=$comunes->getFilas($array_verificar_archivo);

            $sub = array("actualizacion", ".txt");
            $version=str_replace($sub, "", $nombre);

            if($filas_verificar_archivo==0)
            {

                $sql_verificar_version="SELECT * FROM sincronizacion_productos ORDER BY id DESC";
                $array_verificar_version=$comunes->ObtenerFilasBySqlSelect($sql_verificar_version);
                $filas_verificar_version=$comunes->getFilas($array_verificar_version);


                if($filas_verificar_version==0 and $version!=1)
                {
                    unlink($path_local.$nombre);
                    echo" <script type='text/javascript'>
                         alert('Error: Debe Subir el Archivo actualizacion1.txt');
                         history.go(-1);       
                        </script>";
                        exit();
                }
                elseif($filas_verificar_version>0)
                {
                    $version_old=$array_verificar_version[0]['version'];
                    $version_new=$version_old+1;
                    if($version_new!=$version)
                    {
                        unlink($path_local.$nombre);
                        echo" <script type='text/javascript'>
                         alert('Error: Debe Subir el Archivo actualizacion".$version_new.".txt');
                         history.go(-1);       
                        </script>";
                        exit();
                    }
                }

                $sql="INSERT INTO `sincronizacion_productos`(`nombre_archivo`, `fecha`, `cod_usuario`, `tipo`, `version`) 
                VALUES ('$nombre',CURRENT_TIMESTAMP,$cod_usuario,'I',$version)";
                $comunes->ExecuteTrans($sql);
                $id_trans=$comunes->getInsertID();
                $filas=file($path_local.$nombre);
                $i=0;
                $rs_ins=0;
                
                //Recorro el archivo linea por linea
                while($filas[$i]!=NULL)
                {
                    $row = $filas[$i];
                    $values = explode(";",$row);
                    $n3=intval($estado_punto);
                    $n4=intval($values[9]);
                    //Valido que el nombre del producto no venga en blanco
                    if(!empty($values[1]) && ($n4==0 || $n4==$estado_punto))
                    {

                        $n3=intval($estado_punto);
                        $n4=intval($values[9]);

                        $validar_item_detalle="SELECT codigo_barra  FROM sincronizacion_productos_detalle WHERE codigo_barra='".$values[0]."' and id_sincro=$id_trans";
                        $array_item_detalle=$comunes->ObtenerFilasBySqlSelect($validar_item_detalle);
                        $filas_item_detalle=$comunes->getFilas($array_item_detalle);
                        if($filas_item_detalle>0)
                        {  
                            $sql_detalle1="UPDATE sincronizacion_productos_detalle SET precio=$values[8] WHERE codigo_barra='".$values[0]."' and id_sincro=$id_trans";
                            $comunes->ExecuteTrans($sql_detalle1);

                        }else{
                            $sql_detalle="INSERT INTO `sincronizacion_productos_detalle`(`id_sincro`, `codigo_barra`, `precio`, `estado`, `estatus`) VALUES ($id_trans, '".$values[0]."', $values[8],$values[9], 1)";
                            $comunes->ExecuteTrans($sql_detalle);
                        }

                            
                             


                        $validar_item="SELECT id_item, coniva1  FROM item WHERE codigo_barras='".$values[0]."'";
                        
                        $array_item='';
                        $array_item=$comunes->ObtenerFilasBySqlSelect($validar_item);
                        $filas_item=$comunes->getFilas($array_item);

                        $n1=floatval($array_item[0]['coniva1']);
                        $n2=floatval($values[8]);
                        if($values[6]==''){
                                $values[6]='No';
                            }
                            if($values[7]==''){
                                $values[7]=1;
                            }

                            if($values[7]=='Unidad'){
                                $values[7]=1;
                            }

                        if($filas_item>0)
                        {
                            $values[1]=trim($values[1]);

                            $actualizar_item_pyme="UPDATE item SET descripcion1='".$values[1]."', seriales=".$values[2].", cod_departamento=".$values[3].",cod_grupo=".$values[4].", iva=".$values[5].", producto_vencimiento='".$values[6]."', unidad_venta=".$values[7].", estatus='A', cantidad_bulto=".$values[10]."  WHERE codigo_barras='".$values[0]."'";

                            //Unidad de Venta en el POS (Productos pesados) si es 2 es Pesado
                            if ($values[7]==2) {
                                $pesado=1;
                            }elseif ($values[7]==1) {
                                $pesado=0;
                            }

                            $actualizar_item_pos="UPDATE $pos.products SET NAME='".$values[1]."', ISSCALE='".$pesado."' WHERE CODE='".$values[0]."'";

                            $comunes->ExecuteTrans($actualizar_item_pyme);
                            $comunes->ExecuteTrans($actualizar_item_pos);
                       
                           
                            //echo $n1."-".$n2."<br>"; 

                            if($n1==$n2 && $filas_item_detalle==0)
                            {
                                $actualizar_estatus_prod="UPDATE sincronizacion_productos_detalle SET estatus=0, usuario_ejecucion='".$login->getUsuario()."', fecha_ejecucion=CURRENT_TIMESTAMP WHERE codigo_barra='".$values[0]."' AND id_sincro=$id_trans";
                                $comunes->ExecuteTrans($actualizar_estatus_prod);
                            }

                            if($n3!=$n4 && $n4>0 && $filas_item_detalle==0)
                            {
                                $actualizar_estatus_prod="UPDATE sincronizacion_productos_detalle SET estatus=0, usuario_ejecucion='".$login->getUsuario()."', fecha_ejecucion=CURRENT_TIMESTAMP WHERE codigo_barra='".$values[0]."' AND id_sincro=$id_trans";
                                $comunes->ExecuteTrans($actualizar_estatus_prod);
                                if($filas_item_detalle>0){
                                    echo $actualizar_estatus_prod."<br>";
                                    echo $n3."-".$n4;
                                }
                         
                            }

                            if($values[9]==0)
                            {
                                $tipo_precio="NACIONAL";
                            }
                            else
                            {
                                $tipo_precio="ESTADAL";
                            }

                            /*if(($n1!=$n2) && ($values[9]==0 || $values[9]==$estado_punto) )
                            {
                                echo  "<script type='text/javascript'>
                                varr=confirm('¿Desea Actualizar El Precio del Producto: ".$values[0]."? \\n (".$values[1].") \\n Precio Actual: ".$n1." - Precio Nuevo: ".$values[8]."\\n Tipo de Precio: ".$tipo_precio."')
                                if(varr){
                                actualizar_precio(".$values[0].", ".$values[8].", ".$id_trans.");
                                }
                                </script>";                            
                            }*/
                        }

                        if($filas_item==0)
                        {   $horaActual= date("Y-m-d H:i:s");
                            $id_pos_nuevo=$values[1].$horaActual; 
                            $idpos=$comun->codigo_pos($id_pos_nuevo); 
                            if($values[6]==''){
                                $values[6]='No';
                            }
                            if($values[7]==''){
                                $values[7]=1;
                            }
                            $values[0]=trim($values[0]);
                            $nro_producto = $correlativos->getUltimoCorrelativo("cod_producto", 1, "si", "P");
                            $insertar_item="INSERT INTO item (cod_item, codigo_barras, descripcion1, seriales, cod_departamento, cod_grupo, iva, producto_vencimiento, unidad_venta, precio1, coniva1, precio2, coniva2, precio3, coniva3,estatus, cod_item_forma, fecha_creacion, itempos) VALUES ('".$nro_producto."','".$values[0]."', '".$values[1]."', ".$values[2].", ".$values[3].", ".$values[4].", ".$values[5].", '".$values[6]."', ".$values[7].",".$values[8].", ".$values[8].", ".$values[8].", ".$values[8].", ".$values[8].", ".$values[8].",'A', 1,CURRENT_TIMESTAMP, '".$idpos."')";
                            $comunes->ExecuteTrans($insertar_item);
                            $nro_producto = $correlativos->getUltimoCorrelativo("cod_producto", 1, "no", "");
                            $comunes->ExecuteTrans("UPDATE correlativos SET contador = '".$nro_producto."' WHERE campo = 'cod_producto'");
                            $actualizar_estatus_prod="UPDATE sincronizacion_productos_detalle SET estatus=0, usuario_ejecucion='".$login->getUsuario()."', fecha_ejecucion=CURRENT_TIMESTAMP WHERE codigo_barra='".$values[0]."' AND id_sincro=$id_trans";
                            $comunes->ExecuteTrans($actualizar_estatus_prod);
                        }
                    }
                    $i++;
                }
            }
            else
            { //If verificar archivo repetido
                unlink($path_local.$nombre);
                echo" <script type='text/javascript'>
                     alert('Error: Este archivo ya fue Sincronizado');
                     history.go(-1);       
                    </script>";
                    exit();
            }
        }
        unlink($path_local.$nombre);
        $comunes->CommitTrans(1);
        $sql_arreglar_productos="UPDATE item SET unidadxpeso='5' where unidadxpeso='' or unidadxpeso='Seleccione' or unidadxpeso=0;
        UPDATE item SET unidad_venta='1' where unidad_venta='' or unidad_venta='Unidad';
        UPDATE item SET id_marca=1 WHERE id_marca=0 or id_marca='';
        UPDATE item SET cantidad_bulto=1 WHERE cantidad_bulto=0;
        UPDATE item SET kilos_bulto=1 WHERE kilos_bulto=0";
        $comunes->Execute2($sql_arreglar_productos);

        echo" <script type='text/javascript'>
                    alert('Exito: Sincronizacion de Productos Correcta');
                    history.go(-1);       
                    </script>";
                   exit();
    }
}

?>
