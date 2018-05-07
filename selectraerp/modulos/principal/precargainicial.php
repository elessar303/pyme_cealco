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

function completar($cad,$long)
{
    $temp=$cad;
    for($i=1;$i<=$long-strlen($cad);$i++)
    {
        $temp=" ".$temp;
    }
    return $temp;

}
function completarD($cad,$long)
{
    $temp=$cad;
    for($i=1;$i<=$long-strlen($cad);$i++)
    {
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
    if(prod.value!='')
    {
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
foreach ($array_sucursal as $key => $value) 
{
    $sucursal=$value['codigo_siga']; 
    $servidor=$value['servidor']; 
}

$ruta_master=$_SESSION['ROOT_PROYECTO']."/selectraerp/uploads";

$path_local=$ruta_master."/";

if ($_FILES['archivo_productos']["error"] > 0)
{
    echo
    "   <script type='text/javascript'>
            alert('Error Al Subir Archivo');
            history.go(-1);       
        </script>
    ";
    exit();
}
if(!empty($_FILES['archivo_productos']))
{
    $string=".csv";
    if(strnatcasecmp($string,substr($_FILES['archivo_productos']['name'],-4))!=0)
    {
        echo
        "   
            <script type='text/javascript'>
                alert('Error El Formato De Archivo No Es Valido, Unicamente Extensiones CSV');
                history.go(-1);       
            </script>
        ";
        exit();
    }

    $nombre=$_FILES['archivo_productos']['name'];
    if(!move_uploaded_file($_FILES['archivo_productos']['tmp_name'],$path_local.$_FILES['archivo_productos']['name']))
    {
        echo
        " 
            <script type='text/javascript'>
                alert('Error Al Cargar Archivo');
                history.go(-1);       
            </script>
        ";
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
    $nombreusuario=$login->getNombreApellidoUSuario());
    $comunes->BeginTrans();
    while ($archivo = $directorio->read())
    {
        $archiv2=substr($nombre,0,1);
        if ($archiv2!='.') 
        {
            //Datos del Archivo a actualizar
            $filas=file($path_local.$nombre);
            $i=1;
            $rs_ins=0;
            //Recorro el archivo linea por linea
            while($filas[$i]!=NULL)
            {
                $row = $filas[$i];
                //GUIA
                /*
                value0 = observacion, value1=fecha value2=nro documento
                value3=rif, value4=cedula conductor, value5=placa, value6=sunagro
                value7=ticketentrada, value8=codigo barras, value9=cantidad total producto 
                value10=fecha_vencimiento, value11=lote, value12=costodeclarado, value13=idmarca
                value14=id-presentacion, value15=cantidadxpaleta, value16=pesoxpaleta, value17=pesobrutoxpaleta, value18=pesoestibaxpaleta, value19=pesoempaquexpaleta,
                value20=almacen, value21=ubicacion, value22=observacionxpaleta
                */
                $values="";
                $values = explode(";",$row);
                //validar datos
                for($ii=0; $ii<23; $ii++)
                {
                    if(!empty($values[$ii]))
                    {
                        if($ii==1 || $ii==10 )
                        {
                            //validando fechas
                            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$values[$ii])) 
                            {
                                echo
                                " 
                                    <script type='text/javascript'>
                                        alert('Error, formato de fecha incorrecto, el formato es YYYY-MM-DD linea ".$i." valor: ".$values[$ii]."');
                                        history.go(-1);       
                                    </script>
                                ";
                                exit();
                            }
                        }
                        //validando enteros
                        if($ii==9 || $ii==11  || $ii==12 || $ii==15 || $ii==16 || $ii==17 || $ii==18 || $ii==19 || $ii==11)
                        {
                            if(!is_numeric($values[$ii]))
                            {
                                echo
                                " 
                                    <script type='text/javascript'>
                                        alert('Error, formato de numero incorrecto el formato es xxx.xx,  linea ".$i." valor: ".$values[$ii]."');
                                        history.go(-1);       
                                    </script>
                                ";
                                exit();
                            }
                        }
                        //validando cliente y agregando id
                        if($ii==3)
                        {
                            $sql="select id_cliente from clientes where rif='".$values[$ii]."'";
                            $cliente=$comunes->ObtenerFilasBySqlSelect($sql);
                            if($cliente==null)
                            {
                                echo
                                " 
                                    <script type='text/javascript'>
                                        alert('Error, cliente no encontrado,  linea ".$i." valor: ".$values[$ii]."');
                                        history.go(-1);
                                    </script>
                                ";
                                exit();
                            }
                        }
                        //validando codigo de barras
                        if($ii==8)
                        {
                            $sql="select id_item from item where codigo_barras='".$values[$ii]."'";
                            $item=$comunes->ObtenerFilasBySqlSelect($sql);

                            if($item==null)
                            {
                                echo
                                " 
                                    <script type='text/javascript'>
                                        alert('Error, producto no encontrado,  linea ".$i." valor: ".$values[$ii]."');
                                        history.go(-1);
                                    </script>
                                ";
                                exit();
                            }
                        }

                        //validando almacen
                        if($ii==20)
                        {
                            $sql="select cod_almacen from almacen where descripcion='".$values[$ii]."'";
                            $almacen=$comunes->ObtenerFilasBySqlSelect($sql);

                            if($almacen==null)
                            {
                                echo
                                " 
                                    <script type='text/javascript'>
                                        alert('Error, Almacen no encontrado,  linea ".$i." valor: ".$values[$ii]."');
                                        history.go(-1);
                                    </script>
                                ";
                                exit();
                            }
                        }
                        //validando almacen
                        if($ii==21)
                        {
                            $sql="select id from ubicacion as a inner join almacen as b on a.id_almacen=b.cod_almacen where a.descripcion='".$values[$ii]."' and b.descripcion='".$values[$ii-1] ."' and a.ocupado=0";
                            $almacen=$comunes->ObtenerFilasBySqlSelect($sql);
                            if($almacen==null)
                            {
                                echo
                                " 
                                    <script type='text/javascript'>
                                        alert('Error, Ubicacion no encontrada u ocupada,  linea ".$i." valor: ".$values[$ii]."');
                                        history.go(-1);
                                    </script>
                                ";
                                exit();
                            }
                        }
                    }
                }
                // se inserta primero en el maestro de calidad
                $sql = 
                "
                    INSERT INTO calidad_almacen (
                    `id_transaccion` , `tipo_movimiento_almacen`, `autorizado_por`,
                    `observacion`, `fecha`, `usuario_creacion`,
                    `fecha_creacion`, `estado`, `fecha_ejecucion`, `id_documento`, `empresa_transporte`, `id_conductor`, `placa`, `guia_sunagro`, `orden_despacho`, `almacen_procedencia`, `id_proveedor`,`id_ticket_entrada`, `prescintos` )
                    VALUES (
                    NULL , '3', '{$nombreusuario}',
                    '{$values[0]}', '{$values[1]}', '{$login->getUsuario()}', 
                    CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP, '{$values[2]}', '', '{$values[4]}', '{$values[5]}', '{$values[6]}', '', '', '{$values[3]}','{$values[7]}', '');
                ";
        
                $comunes->ExecuteTrans($sql);
                $id_transaccion = $comunes->getInsertID();

                $sql = 
                "
                    INSERT INTO calidad_almacen_detalle (
                    `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_entrada`, `vencimiento`,`lote`, `observacion`, `estatus`, `tipo_uso`, `costo_declarado`, `id_marca`, `id_presentacion` )
                    VALUES (
                    '{$id_transaccion}', '{$values[20]}',
                    '', '{$values[8]}', '{$values[9]}','{$values[21]}','{$values[10]}','{$values[11]}','','', '', '{$values[12]}', '{$values[13]}', '{$values[14]}');";

                $comunes->ExecuteTrans($sql);
                //comienza la inserccion en el kardex
                $kardex= 
                "
                    INSERT INTO kardex_almacen (
                    `id_transaccion_calidad` , `tipo_movimiento_almacen`, `autorizado_por`,
                    `observacion`, `fecha`, `usuario_creacion`,
                    `fecha_creacion`, `estado`, `fecha_ejecucion`, `id_documento`, `empresa_transporte`, `id_conductor`, `placa`, 
                    `guia_sunagro`, `orden_despacho`, `almacen_procedencia`, `id_proveedor`,  `id_seguridad`, `id_aprobado`,
                    `id_receptor`, `nro_contenedor`, `ticket_entrada`, `id_cliente`, `prescintos`)
                    VALUES (
                    {$id_transaccion} , '3', '{$login->getUsuario()}',
                    '{$values[0]}', '{$values[1]}', '{$login->getUsuario()}', 
                    CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP, '{$values[2]}', '',
                    '{$values[4]}', '{$values[5]}', '{$values[6]}',
                    '',
                    '', '{$values[3]}','',
                    '',
                    '', '', '{$values[7]} ', '{$values[3]}', '');
                ";
                $comunes->ExecuteTrans($kardex);
                $id_transaccion2 = $comunes->getInsertID();
                //Se consulta el precio actual para dejar el historico en kardex (Junior)
                $sql="SELECT precio1, iva FROM item WHERE id_item  = '{$values[8]}'";
                $precio_actual=$comunes->ObtenerFilasBySqlSelect($sql);
                $precioconiva=$precio_actual[0]['precio1']+($precio_actual[0]['precio1']*$precio_actual[0]['iva']/100);
                //correlativo de ticket
                $sql="Select contador from correlativos where campo='id_ticket'";
                $idticket=$comunes->ObtenerFilasBySqlSelect($sql);
                //sql kardex_detalle
                $kardex_almacen_detalle_instruccion = 
                "
                    INSERT INTO kardex_almacen_detalle (
                    `id_transaccion_detalle` , `id_transaccion` ,`id_almacen_entrada`,
                    `id_almacen_salida`, `id_item`, `cantidad`, `peso`, `peso_bruto`, `peso_estiva`, `peso_empaque`, `id_ubi_entrada`, `vencimiento`,`elaboracion`,`lote`, `c_esperada`,`observacion`, `precio`, `etiqueta`, `costo_declarado`, `id_marca`, `id_presentacion`, `observacion_limite`)
                    VALUES (
                    NULL, '{$id_transaccion2}', '{$value[20]}',
                    '', '{$value[8]}', '{$value[15]}', '{$value[16]}', '{$value[17]}', '{$value[18]}', '{$value[19]}','{$value[21]}','{$value[10]}',
                    '{$value[1]}','{$value[11]}','{$value[15]}','{$value[22]}', ".$precioconiva.", ".$idticket[0]['contador'].", '{$value[12]}', '{$value[13]}', '{$value[14]}', '');
                ";
                $comunes->ExecuteTrans($kardex_almacen_detalle_instruccion);
                //actualizo correlativo
                $sql="update correlativos set contador=(contador+1) where campo='id_ticket'";
                $comunes->ExecuteTrans($sql);
                //bloqueando las ubicaciones
                $sql="select ocupado from ubicacion where id=".$value[21];
                $ocupado=$comunes->ObtenerFilasBySqlSelect($sql);
                if($ocupado[0]['ocupado']==0)
                {
                    $sql=
                    "
                        update ubicacion set ocupado=1 where id=".$value[21];
                    $communes->ExecuteTrans($sql);
                }
                else
                {
                    //ubicacion ocupada
                    echo "ubicacion ocupada"; exit();
                }
                $sql=
                "
                    SELECT * FROM item_existencia_almacen WHERE
                    id_item  = '{$datospadre[0]['id_item']}' 
                    AND id_ubicacion = '{$_POST['ubicacion_detalle']}'
                    AND lote='{$datospadre[0]['lote']}' 
                    AND id_marca='{$datospadre[0]['id_marca']}' 
                    AND id_proveedor='{$datospadre[0]['id_proveedor']}';
                ";
                $campos = $comunes->ObtenerFilasBySqlSelect($sql);
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
