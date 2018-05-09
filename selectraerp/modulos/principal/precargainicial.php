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
    echo
        " 
            <script type='text/javascript'>
                alert('Recuerde que es imperativo ordernar por cliente, Rubro e Item de la lista de carga inicial.');
            </script>
        ";
    

    //*********************************JUNIOR AYALA**************************************************       
    $directorio=dir("$path_local");
    $login = new Login();
    $cod_usuario=$login->getIdUsuario();
    $sucursal1= $comunes->ObtenerFilasBySqlSelect("SELECT estado from parametros_generales");
    $estado_punto = $sucursal1[0]['estado'];
    //echo $estado_punto; exit();
    $nombreusuario=$login->getNombreApellidoUSuario();
    $comunes->BeginTrans();
    while ($archivo = $directorio->read())
    {
        $archiv2=substr($nombre,0,1);
        if ($archiv2!='.') 
        {
            //Datos del Archivo a actualizar
            $filas=file($path_local.$nombre);
            $l=1;
            $rs_ins=0;
            $oldid_transaccion="";
            $oldid_transaccion2="";
            //ordeno primero
            /*//Recorro el archivo linea por linea
            while($filas[$i]!=NULL)
            {
                $row = $filas[$i];
                $values="";
                $values = explode(";",$row);
                //validar datos
                for($ii=0; $ii<23; $ii++)
                {
                       
                }
            }*/
            //Recorro el archivo linea por linea
            $control=0;
            while($filas[$l]!=NULL)
            {
                $row = $filas[$l];
                $control++;
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
                    $values[$ii]=str_replace('"', '', $values[$ii]);
                    $values[$ii]=trim($values[$ii]);
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
                        if($ii==9 || $ii==11  || $ii==12 || $ii==15 || $ii==16 || $ii==17 || $ii==18 || $ii==19)
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
                            else
                            {
                                $values[$ii]=$cliente[0]['id_cliente'];
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
                            else
                            {
                                $values[$ii]=$item[0]['id_item'];
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
                            else
                            {
                                $values[$ii]=$almacen[0]['cod_almacen'];
                            }
                        }
                        //validando almacen
                        if($ii==21)
                        {
                            $sql="select id from ubicacion as a inner join almacen as b on a.id_almacen=b.cod_almacen where a.descripcion='".$values[$ii]."' and a.id_almacen='".$values[$ii-1]."' and a.ocupado=0";
                            $almacen1=$comunes->ObtenerFilasBySqlSelect($sql);
                            if($almacen1==null)
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
                            else
                            {
                                $values[$ii]=$almacen1[0]['id'];
                            }
                        }
                    }
                }
                
                //se guarda el numero de documento y el rif del proveedor, esto me indica si es el mismo 
                if($control==1)
                {
                    $oldrif=$values[3]; $oldnrodocumento=$values[2]; $olditem=$values[8];
                    
                }
                if( ($oldrif!=$values[3])   || ($control==1) )
                {
                    
                    $oldrif=$values[3];
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
                    $oldid_transaccion=$id_transaccion;
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
                    $oldid_transaccion2=$id_transaccion2;
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
                        NULL, '{$id_transaccion2}', '{$values[20]}',
                        '', '{$values[8]}', '{$values[15]}', '{$values[16]}', '{$values[17]}', '{$values[18]}', '{$values[19]}','{$values[21]}','{$values[10]}',
                        '{$values[1]}','{$values[11]}','{$values[15]}','{$values[22]}', ".$precioconiva.", ".$idticket[0]['contador'].", '{$values[12]}', '{$values[13]}', '{$values[14]}', '');
                    ";
                    $comunes->ExecuteTrans($kardex_almacen_detalle_instruccion);
                    //actualizo correlativo
                    $sql="update correlativos set contador=(contador+1) where campo='id_ticket'";
                    $comunes->ExecuteTrans($sql);
                    
                    //bloqueando las ubicaciones
                    $sql="select ocupado from ubicacion where id=".$values[21];
                    $ocupado=$comunes->ObtenerFilasBySqlSelect($sql);
                    
                    if($ocupado[0]['ocupado']==0)
                    {
                        $sql=
                        "
                            update ubicacion set ocupado=1 where id=".$values[21];
                            
                        $comunes->ExecuteTrans($sql);
                        
                    }
                    else
                    {
                        //ubicacion ocupada
                        echo "ubicacion ocupada"; exit();
                    }
                    $sql=
                    "
                        SELECT * FROM item_existencia_almacen WHERE
                        id_item  = '{$values[8]}' 
                        AND id_ubicacion = '{$values[21]}'
                        AND lote='{$values[11]}' 
                        AND id_marca='{$values[13]}' 
                        AND id_proveedor='{$values[3]}';
                    ";
                    $campos = $comunes->ObtenerFilasBySqlSelect($sql);
                    if (count($campos) > 0) 
                    {
                        $cantidadExistente = $campos[0]["cantidad"];
                        $pesoExistente = $campos[0]["peso"];
                        $comunes->ExecuteTrans(
                        "
                            UPDATE item_existencia_almacen 
                            SET cantidad = '" . ($cantidadExistente + $values[15]) . "', peso = '" . ($pesoExistente + $values[16]) . "'
                            WHERE id_item  = '{$values[8]}' 
                            AND id_ubicacion = '{$values[21]}' 
                            AND lote='{$values[11]}' 
                            AND id_marca='{$values[13]}' 
                            AND id_proveedor='{$values[3]}';
                        ");
                    } 
                    else 
                    {
                        $instruccion = 
                        "
                            INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`, `peso`, `id_ubicacion`, `lote`, `id_proveedor`, `id_marca`)
                            VALUES 
                            ('{$values[20]}',
                            '{$values[8]}', '{$values[15]}' , '{$values[16]}',  '{$values[21]}',
                            '{$values[11]}', '{$values[3]}', '{$values[13]}');
                        ";
                        $comunes->ExecuteTrans($instruccion);
                    }
                    
                    
                }// fin del if
                else
                {
                    //ya existe una entrada maestra. solo debe verse que detalles actualizar
                    //se verifica primero el item si es distinto hay que agregar un nuevo detalle de calidad.
                    if($olditem!=$values[8])
                    {
                        $olditem=$values[8];
                        $sql = 
                        "
                            INSERT INTO calidad_almacen_detalle (
                            `id_transaccion` ,`id_almacen_entrada`,
                            `id_almacen_salida`, `id_item`, `cantidad`,`id_ubi_entrada`, `vencimiento`,`lote`, `observacion`, `estatus`, `tipo_uso`, `costo_declarado`, `id_marca`, `id_presentacion` )
                            VALUES (
                            '{$oldid_transaccion}', '{$values[20]}',
                            '', '{$values[8]}', '{$values[9]}','{$values[21]}','{$values[10]}','{$values[11]}','','', '', '{$values[12]}', '{$values[13]}', '{$values[14]}');";
        
                        $comunes->ExecuteTrans($sql);
                        //comienza la inserccion en el kardex
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
                            NULL, '{$oldid_transaccion2}', '{$values[20]}',
                            '', '{$values[8]}', '{$values[15]}', '{$values[16]}', '{$values[17]}', '{$values[18]}', '{$values[19]}','{$values[21]}','{$values[10]}',
                            '{$values[1]}','{$values[11]}','{$values[15]}','{$values[22]}', ".$precioconiva.", ".$idticket[0]['contador'].", '{$values[12]}', '{$values[13]}', '{$values[14]}', '');
                        ";
                        $comunes->ExecuteTrans($kardex_almacen_detalle_instruccion);
                        //actualizo correlativo
                        $sql="update correlativos set contador=(contador+1) where campo='id_ticket'";
                        $comunes->ExecuteTrans($sql);
                        //bloqueando las ubicaciones
                        $sql="select ocupado from ubicacion where id=".$values[21];
                        $ocupado=$comunes->ObtenerFilasBySqlSelect($sql);
                        if($ocupado[0]['ocupado']==0)
                        {
                            $sql=
                            "
                                update ubicacion set ocupado=1 where id=".$values[21];
                                
                            $comunes->ExecuteTrans($sql);
                        }
                        else
                        {
                            //ubicacion ocupada
                            echo "ubicacion ocupada"; exit();
                        }
                        $sql=
                        "
                            SELECT * FROM item_existencia_almacen WHERE
                            id_item  = '{$values[8]}' 
                            AND id_ubicacion = '{$values[21]}'
                            AND lote='{$values[11]}' 
                            AND id_marca='{$values[13]}' 
                            AND id_proveedor='{$values[3]}';
                        ";
                        $campos = $comunes->ObtenerFilasBySqlSelect($sql);
                        if (count($campos) > 0) 
                        {
                            $cantidadExistente = $campos[0]["cantidad"];
                            $pesoExistente = $campos[0]["peso"];
                            $comunes->ExecuteTrans(
                            "
                                UPDATE item_existencia_almacen 
                                SET cantidad = '" . ($cantidadExistente + $values[15]) . "', peso = '" . ($pesoExistente + $values[16]) . "'
                                WHERE id_item  = '{$values[8]}' 
                                AND id_ubicacion = '{$values[21]}' 
                                AND lote='{$values[11]}' 
                                AND id_marca='{$values[13]}' 
                                AND id_proveedor='{$values[3]}';
                            ");
                        } 
                        else 
                        {
                            $instruccion = 
                            "
                                INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`, `peso`, `id_ubicacion`, `lote`, `id_proveedor`, `id_marca`)
                                VALUES 
                                ('{$values[20]}',
                                '{$values[8]}', '{$values[15]}' , '{$values[16]}',  '{$values[21]}',
                                '{$values[11]}', '{$values[3]}', '{$values[13]}');
                            ";
                            $comunes->ExecuteTrans($instruccion);
                        }
                        
                    }
                    else
                    {
                        //no se hace nada con calidad. la entrad nueva solo es de paleta.
                        //comienza la inserccion en el kardex
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
                            NULL, '{$oldid_transaccion2}', '{$values[20]}',
                            '', '{$values[8]}', '{$values[15]}', '{$values[16]}', '{$values[17]}', '{$values[18]}', '{$values[19]}','{$values[21]}','{$values[10]}',
                            '{$values[1]}','{$values[11]}','{$values[15]}','{$values[22]}', ".$precioconiva.", ".$idticket[0]['contador'].", '{$values[12]}', '{$values[13]}', '{$values[14]}', '');
                        ";
                        $comunes->ExecuteTrans($kardex_almacen_detalle_instruccion);
                        //actualizo correlativo
                        $sql="update correlativos set contador=(contador+1) where campo='id_ticket'";
                        $comunes->ExecuteTrans($sql);
                        //bloqueando las ubicaciones
                        $sql="select ocupado from ubicacion where id=".$values[21];
                        $ocupado=$comunes->ObtenerFilasBySqlSelect($sql);
                        if($ocupado[0]['ocupado']==0)
                        {
                            $sql=
                            "
                                update ubicacion set ocupado=1 where id=".$values[21];
                            $comunes->ExecuteTrans($sql);
                        }
                        else
                        {
                            //ubicacion ocupada
                            echo "ubicacion ocupada"; exit();
                        }
                        $sql=
                        "
                            SELECT * FROM item_existencia_almacen WHERE
                            id_item  = '{$values[8]}' 
                            AND id_ubicacion = '{$values[21]}'
                            AND lote='{$values[11]}' 
                            AND id_marca='{$values[13]}' 
                            AND id_proveedor='{$values[3]}';
                        ";
                        $campos = $comunes->ObtenerFilasBySqlSelect($sql);
                        if (count($campos) > 0) 
                        {
                            $cantidadExistente = $campos[0]["cantidad"];
                            $pesoExistente = $campos[0]["peso"];
                            $comunes->ExecuteTrans(
                            "
                                UPDATE item_existencia_almacen 
                                SET cantidad = '" . ($cantidadExistente + $values[15]) . "', peso = '" . ($pesoExistente + $values[16]) . "'
                                WHERE id_item  = '{$values[8]}' 
                                AND id_ubicacion = '{$values[21]}' 
                                AND lote='{$values[11]}' 
                                AND id_marca='{$values[13]}' 
                                AND id_proveedor='{$values[3]}';
                            ");
                        } 
                        else 
                        {
                            $instruccion = 
                            "
                                INSERT INTO item_existencia_almacen (`cod_almacen`, `id_item`, `cantidad`, `peso`, `id_ubicacion`, `lote`, `id_proveedor`, `id_marca`)
                                VALUES 
                                ('{$values[20]}',
                                '{$values[8]}', '{$values[15]}' , '{$values[16]}',  '{$values[21]}',
                                '{$values[11]}', '{$values[3]}', '{$values[13]}');
                            ";
                            $comunes->ExecuteTrans($instruccion);
                        }
                    }//fin del else interno
                    
                }//fin del else
                //ahora se comienza a cobrar
                    //tipo cliente
                    $sql="select cod_tipo_cliente from clientes where id_cliente={$values[3] }";
                    $clienteseguro=0;
                    $tipocliente=$comunes->ObtenerFilasBySqlSelect($sql);
                    if($tipocliente[0]['cod_tipo_cliente']==1)
                    {
                        //privada
                        $precio='precio1';
                        $clienteseguro=1;
                    }
                    elseif ($tipocliente[0]['cod_tipo_cliente']==2) 
                    {
                        //publica
                        $precio='precio2';
                    }
                    else
                    {
                        //minpal
                        $precio='precio3';
                    }
                    
                    //busco el id del movimiento cargo
                    $sql="select id_tipo_movimiento_almacen from tipo_movimiento_almacen where descripcion= 'Cargo'";
                    $id_movimiento=$comunes->ObtenerFilasBySqlSelect($sql);
                    //primero se buscar el movimiento "cargo" y se agrega el costo de los servicios de dicho movimiento
                    $iva[]="";
                    $base[]="";
                    $total[]="";
                    $idservicios[]="";
                    $codservicio[]="";
                    $nombreservicio[]="";
                    $contador=0;
                    $cobrar="";
                    //guardando los servicios
                    $cajas=[];//json_decode($_POST['cajas']);
                    foreach($cajas as $key => $valueser)
                    {
                        $cobrar.="'".$valueser->value."', ";
                    }
                    $cobrar=substr($cobrar, 0, -2);
                    foreach ($id_movimiento as $key => $idmovimiento)
                    {
                        $sql="select id_movimiento_almacen, id_servicio from movimiento_almacen_servicio where id_movimiento_almacen = ".$idmovimiento['id_tipo_movimiento_almacen']." "; //and id_servicio in (".$cobrar.")
                        $buscarservicios=$comunes->ObtenerFilasBySqlSelect($sql);
                        foreach($buscarservicios as $key2 => $servicios)
                        {
                            
                            $sql="select id_item, cod_item, precio1, precio2, precio3, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
                            //echo $sql; exit();
                            $contarservicio=$comunes->ObtenerFilasBySqlSelect($sql);
                            $iva[$contador] = $contarservicio[0]['iva'];
                            $base[$contador] = $contarservicio[0][$precio];
                            $total[$contador] = $contarservicio[0][$precio]+(($contarservicio[0][$precio]*$contarservicio[0]['iva']) / 100);
                            $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                            $idservicios[$contador]= $contarservicio[0]['id_item'];
                            $codservicio[$contador]= $contarservicio[0]['cod_item'];
                            $contador++;
                        }
                        //echo $sql; exit();
                    }
                    
                    //fin del cobro de movimiento, ahora se cobra la ubicacion
                    $sql="select id_servicio from ubicacion_servicio where id_ubicacion=".$values[21];
                    $buscarserviciosubicacion=$comunes->ObtenerFilasBySqlSelect($sql);
                    foreach($buscarserviciosubicacion as $key => $servicios)
                    {
                        $sql="select id_item, cod_item, precio1, precio2, precio3, iva, descripcion1 from item where id_item=".$servicios['id_servicio'];
                        $contarservicio=$comunes->ObtenerFilasBySqlSelect($sql);
                        $iva[$contador] = $contarservicio[0]['iva'];
                        $base[$contador] = $contarservicio[0][$precio];
                        $total[$contador] = $contarservicio[0][$precio]+(($contarservicio[0][$precio]*$contarservicio[0]['iva']) / 100);
                        $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                        $idservicios[$contador]= $contarservicio[0]['id_item'];
                        $codservicio[$contador]= $contarservicio[0]['cod_item'];
                        $contador++;
                    }
                    //ahora a realizar el cobro del seguro******
                    $sql="select id_item, cod_item, iva, descripcion1 from item where precio1=-1";
                    $contarservicio=$comunes->ObtenerFilasBySqlSelect($sql);
                    if($clienteseguro==0)
                    {
                        if($contarservicio!=null)
                        {
                            $iva[$contador] = $contarservicio[0]['iva'];
                            $base[$contador] = (($values[16]*$values[12]) * 0.005); //$contarservicio[0]['precio1'];
                            $total[$contador] =( (($values[16]*$vlaues[12]) * 0.005) + (((($values[16]*$values[12]) * 0.005)*$contarservicio[0]['iva']) / 100) ) ;
                            $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                            $idservicios[$contador]= $contarservicio[0]['id_item'];
                            $codservicio[$contador]= $contarservicio[0]['cod_item'];
                            $contador++;
                        }
                    }
                    else
                    {
                        if($contarservicio!=null)
                        {
                            $iva[$contador] = $contarservicio[0]['iva'];
                            $base[$contador] = (($values[16]*$values[12]) * 0.0075); //$contarservicio[0]['precio1'];
                            $total[$contador] =( (($values[16]*$values[12]) * 0.0075) + (((($values[16]*$values[12]) * 0.0075)*$contarservicio[0]['iva']) / 100) ) ;
                            $nombreservicio[$contador]= $contarservicio[0]['descripcion1'];
                            $idservicios[$contador]= $contarservicio[0]['id_item'];
                            $codservicio[$contador]= $contarservicio[0]['cod_item'];
                            $contador++;
                        }
                    }
                    //se comienza hacer el pedido por la entrada.
                    //obtener correlativo:
                    //obtenemos el correlativo de la factura
                    $correlativos = new Correlativos();
                    $nro_pedido = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "si");
                    $formateo_nro_factura = $nro_pedido;
                    $subtotal=0;
                    $ivatotal=0;
                    $itemstotal=count($total);
                    $totaltotal=0;
                    //se guarda los totales del resultado de los arreglos
                    for($i=0; $i<count($total); $i++)
                    {
                        $subtotal+=$base[$i];
                        $ivatotal+=(($base[$i]*$iva[$i]) / 100);
                        $totaltotal+=$total[$i];
                        
                    }
                    
                   // debemos ver si hay pedido pendiente, para eso verificamos la fecha de pago
                    $sql="select * from despacho_new where fecha_pago='0000-00-00' and id_cliente='{$values[3]}' limit 1";
                    $cargosoriginal=$comunes->ObtenerFilasBySqlSelect($sql);
                    if($cargosoriginal==null)
                    {
                        //es su primer cargo por lo que hay que crear el despacho new padre.
                        $nro_factura = $correlativos->getUltimoCorrelativo("cod_factura", 1, "si");
                        $formateo_nro_factura = $nro_factura;
                        #obtenemos el money actual
                        $money=$comunes->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");
                        $sql = "INSERT INTO `despacho_new` (
                            `id_cliente`,`cod_factura`,`cod_vendedor`,`fechaFactura`,
                            `subtotal`,`descuentosItemFactura`,`montoItemsFactura`,
                            `ivaTotalFactura`,`TotalTotalFactura`,`cantidad_items`,
                            `totalizar_sub_total`,`totalizar_descuento_parcial`,`totalizar_total_operacion`,
                            `totalizar_pdescuento_global`,`totalizar_descuento_global`,
                            `totalizar_base_imponible`,`totalizar_monto_iva`,
                            `totalizar_total_general`,`totalizar_total_retencion`,`fecha_creacion`,
                            `usuario_creacion`,`cod_estatus`,`formapago`, `impresora_serial`, `money`, `facturacion`
                            )
                        VALUES(
                            {$values[3]}, '{$nro_factura}', '{$login->getUsuario()}', now(),
                            ". $subtotal . ", 0 ," . $subtotal . ","
                                . $ivatotal . "," . $totaltotal . "," . $itemstotal . ","
                                . $subtotal . ", 0," . $totaltotal . ", 0 ,  
                                0," . $subtotal . ","
                                . $ivatotal . ", " . $totaltotal . ",0 ,CURRENT_TIMESTAMP,'" . $login->getUsuario() . "',
                                '" . $cod_estatus = 1 . "', 'contado', '".impresora_serial."' , '".$money[0]['money']."',''
                            );";
                            $comunes->ExecuteTrans($sql);
                        $id_facturaTrans = $comunes->getInsertID();
                                $kardex_almacen_instruccion = "
                                    INSERT INTO kardex_almacen (
                                    `id_transaccion` ,
                                    `tipo_movimiento_almacen` ,
                                    `autorizado_por` ,
                                    `observacion` ,
                                    `fecha` ,
                                    `usuario_creacion`,
                                    `fecha_creacion`,
                                    `estado`,
                                    `fecha_ejecucion`,
                                    id_cliente, 
                                    nro_factura
                                    )
                                    VALUES (
                                    NULL ,
                                    '8',
                                    '" . $login->getUsuario() . "',
                                    'Salida por Ventas',
                                    now(),
                                    '" . $login->getUsuario() . "',
                                    CURRENT_TIMESTAMP,
                                    'Pendiente',
                                    now(),
                                    {$values[3]},
                                    '{$nro_factura}'
                                    );";
                    
                        $comunes->ExecuteTrans($kardex_almacen_instruccion);
                        $id_transaccion = $comunes->getInsertID();
                        for($i=0; $i<count($total); $i++)
                        {
                            if($total[$i]!=null)
                            {
                                $descripcion =  $nombreservicio[$i];
                               
                                $detalle_item_instruccion = "
                                INSERT INTO despacho_new_detalle (
                                `id_factura`, `id_item`,
                                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                                `fecha_creacion`, `_item_almacen`
                                )
                                VALUES (
                                '{$id_facturaTrans}', '{$idservicios[$i]}',
                                '{$descripcion}', '1', '{$base[$i]}',
                                0, 0, '{$iva[$i]}',
                                '{$base[$i]}', '{$total[$i]}', '{$usuario}',
                                CURRENT_TIMESTAMP, '1'
                                );";
                                $comunes->ExecuteTrans($detalle_item_instruccion);
                                
                                $kardex_almacen_detalle_instruccion = "
                                INSERT INTO kardex_almacen_detalle (
                                `id_transaccion_detalle` ,
                                `id_transaccion` ,
                                `id_almacen_entrada` ,
                                `id_almacen_salida` ,
                                `id_item` ,
                                `precio` ,
                                `cantidad`
                                )
                                VALUES (
                                NULL ,
                                '" . $id_transaccion . "',
                                '{$values[20]}',
                                '',
                                '" . $idservicios[$i] . "',
                                '" . $base[$i] . "',
                                1
                                );";
                                $comunes->ExecuteTrans($kardex_almacen_detalle_instruccion);
                            }
                        }
                        $nro_facturaOLD = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "no");
                        $nro_pedido = $correlativos->getUltimoCorrelativo("cod_pedido", 1, "no");
                        $comunes->ExecuteTrans("update correlativos set contador = '" . $nro_pedido . "' where campo = 'cod_pedido'");
                        $comunes->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_factura}' WHERE campo = 'cod_factura';");
                        //Fin pedido
                    }
                    else
                    { // cuando existe pedido pendiente
                        $kardexoriginal=$comunes->ObtenerFilasBySqlSelect("select id_transaccion from kardex_almacen where nro_factura='".$cargosoriginal[0]['cod_factura']."'");
                        if($kardexoriginal==null)
                        { 
                            echo "Error Interno, el Kardex no se ha podido localizar contacte al administrador"; exit();
                        }
                        $sql="UPDATE
                                `despacho_new`
                            SET
                                `subtotal` =  (subtotal + ". $subtotal . "),
                                `descuentosItemFactura` = 0,
                                `montoItemsFactura` = (montoItemsFactura + ". $subtotal . "),
                                `ivaTotalFactura` = (ivaTotalFactura + ".$ivatotal."),
                                `TotalTotalFactura` =(TotalTotalFactura + ".$totaltotal."),
                                `cantidad_items` = (cantidad_items + ".$itemstotal."),
                                `totalizar_sub_total` = (totalizar_sub_total + ". $subtotal . "),
                                `totalizar_descuento_parcial` = totalizar_descuento_parcial,
                                `totalizar_total_operacion` = (totalizar_total_operacion + ".$totaltotal.") ,
                                `totalizar_pdescuento_global` = totalizar_pdescuento_global ,
                                `totalizar_descuento_global` = totalizar_descuento_global,
                                `totalizar_base_imponible` = (totalizar_base_imponible + ".$subtotal."),
                                `totalizar_monto_iva` = (totalizar_monto_iva + ".$ivatotal."),
                                `totalizar_total_general` = (totalizar_total_general + ".$totaltotal."),
                                `totalizar_total_retencion` = totalizar_total_retencion
                            WHERE
                                id_factura='".$cargosoriginal[0]['id_factura'] ."'";
                        $comunes->ExecuteTrans($sql);
        
                        for($i=0; $i<count($total); $i++)
                        {
                            if($total[$i]!=null)
                            {
                                $descripcion =  $nombreservicio[$i];
                               
                                $detalle_item_instruccion = "
                                INSERT INTO despacho_new_detalle (
                                `id_factura`, `id_item`,
                                `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva` ,
                                `_item_descuento`, `_item_montodescuento`, `_item_piva`,
                                `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion` ,
                                `fecha_creacion`, `_item_almacen`
                                )
                                VALUES (
                                '".$cargosoriginal[0]['id_factura'] ."', '{$idservicios[$i]}',
                                '{$descripcion}', '1', '{$base[$i]}',
                                0, 0, '{$iva[$i]}',
                                '{$base[$i]}', '{$total[$i]}', '{$usuario}',
                                CURRENT_TIMESTAMP, '1'
                                );";
                                $comunes->ExecuteTrans($detalle_item_instruccion);
                                
                                $kardex_almacen_detalle_instruccion = "
                                INSERT INTO kardex_almacen_detalle (
                                `id_transaccion_detalle` ,
                                `id_transaccion` ,
                                `id_almacen_entrada` ,
                                `id_almacen_salida` ,
                                `id_item` ,
                                `precio` ,
                                `cantidad`
                                )
                                VALUES (
                                NULL ,
                                '" . $kardexoriginal[0]['id_transaccion'] . "',
                                '{$values[20]}',
                                '',
                                '" . $idservicios[$i] . "',
                                '" . $base[$i] . "',
                                1
                                );";
                                $comunes->ExecuteTrans($kardex_almacen_detalle_instruccion);
                            }
                        }
                    }
            $l++;
            }
        }
        unlink($path_local.$nombre);
        $comunes->CommitTrans(1);
    }
    echo
    "   <script type='text/javascript'>
            alert('Importancion Exitosa');
            history.go(-1);
        </script>
    ";
    exit();
}

?>
