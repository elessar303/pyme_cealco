<?php

session_start();
ini_set("display_errors", 1);

require_once("../../../libs/php/adodb5/adodb.inc.php");
require_once("../../../libs/php/configuracion/config.php");
require_once("../../../libs/php/clases/ConexionComun.php");
require_once("../../../libs/php/clases/comunes.php");
require_once("../../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../../libs/php/clases/correlativos.php");
require_once "../../../libs/php/clases/numerosALetras.class.php";
include("../../../../menu_sistemas/lib/common.php");
include("../../../../generalp.config.inc.php");
include("../../../../general.config.inc.php");
$bdCentral= "selectrapyme_central";

if (isset($_GET["opt"]) == true || isset($_POST["opt"]) == true) {
    $conn = new ConexionComun();
    $login = new Login();
    $opt = (isset($_GET["opt"])) ? $_GET["opt"] : $_POST["opt"];

    switch ($opt) {

        case "getProductoCotizacion" :
            $datos=$conn->ObtenerFilasBySqlSelect("SELECT cmd.id_detalle_producto,cmd.id_estudio,cmd.id_producto,
                i.codigo_barras,i.descripcion1,cmd.costo_sin_iva,cmd._ivaproduct,cmd._precio_sugerido,
                cmd._margen_ganancia,cmd._pvp,cmd._estatus_producto,cme.estatus_name,cme.id_estatus
                FROM $bdCentral.cotizacion_mercado_detalle cmd
                INNER JOIN $selectrapyme.item i ON i.id_item = cmd.id_producto
                INNER JOIN $bdCentral.cotizacion_mercado_estatus cme ON cme.id_estatus = cmd._estatus_producto
                WHERE cmd.id_detalle_producto = ".$_POST['iddetalle']);
            //print_r($datos); exit();
            echo json_encode($datos);
        break;

        case "updateEstatusProducto" :

            $resultado=$conn->Execute2("update $bdCentral.cotizacion_mercado_detalle set _estatus_producto='".$_POST['estatusproducto']."' where id_detalle_producto=".$_POST['iddetalle']);
            
            if($resultado)
            {
                echo "1";
            }else
            {
                echo "0";
            }
            break;

        case "ValidarCodigoCotizacion":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT cm.* FROM $bdCentral.cotizacion_mercado cm WHERE nro_cotizacion = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;

        //Cambio el estatus de la cotizacion ////////////////////////////////////
        case "cambiar_estatus_cotizacion":
            
            $cambiando=$conn->Execute2("update $bdCentral.cotizacion_mercado set retirado=1, fecha_recep_mercadeo=now() where id_estudio=".$_POST['id_estudio']);
 
            if($cambiando){
                echo 1;
            }else{
                echo 2;
            }
            break;

        case "cambiar_estatus_cotizacion2":
            
            $cambiando=$conn->Execute2("update $bdCentral.cotizacion_mercado set cerrado=1 where id_estudio=".$_POST['id_estudio']);

            if($cambiando){
                echo 1;
            }else{
                echo 2;
            }
            break;

        case "generarCodigoKardex" :
            
            $mes=date('m');
            $dia=date('d');
            $arraynumero=str_split($_POST['siga']);
            //generar codigo siga con suma;
            $puntosuma=($arraynumero[3]+$arraynumero[4]+$arraynumero[5])+$_POST['siga'];
            $i=0;
            $login = new Login();
            while($i!=1)
            {
                $primer=rand(10, 99);
                $hexa=dechex($primer+$puntosuma);
                $codigo=$mes.$primer.$dia.$puntosuma.$hexa;
                $consultar=$conn->ObtenerFilasBySqlSelect("select codigo from codigo_autorizacion where codigo='".$codigo."'");
                if($consultar==null)
                {
                    $insertar=$conn->Execute2('insert into codigo_autorizacion (codigo, punto, fecha, usuario) values ("'.$codigo.'","'.$_POST['siga'].'", now(), "'.$login->getNombreApellidoUSuario().'" )');
                    $i=1;
                }
            }
            echo $codigo; exit();
        
        break;

         case "det_pda_compras" :
            $id_transaction =$_POST['id_transaccion'];
            $proveedor=$conn->ObtenerFilasBySqlSelect("Select b.id_instalacion_origen  from pda_maestro a, pda_detalle as b where a.id=b.id_pda_maestro and a.id=".$_POST['id_transaccion']);
            if($proveedor[0]['id_instalacion_origen']==0)
            {
                $consultar = $conn->ObtenerFilasBySqlSelect("SELECT a.id, b.id as id_detalle, a.transporte, a.orden_compra, date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_inicio, date_format(b.fecha_fin, '%d/%m/%Y') as fecha_fin, d.direccion, e.descripcion1, b.cantida_x_kilo, b.observaciones, format(((e.cantidad_bulto*b.cantida_x_kilo)/1000), 2) as total  FROM pda_maestro as a, pda_detalle as b, proveedores as d, item as e
                    where
                    a.id=b.id_pda_maestro
                    and
                    b.id_producto=e.id_item
                    and
                    a.id_proveedor=d.id_proveedor
                    and a.id=".$id_transaction."

                    union

                    SELECT a.id, b.id as id_detalle, a.transporte, a.orden_compra,  date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_inicio, date_format(b.fecha_fin, '%d/%m/%Y') as fecha_fin, d.direccion_punto as direccion, e.descripcion1, b.cantida_x_kilo, b.observaciones, format(((e.cantidad_bulto*b.cantida_x_kilo)/1000), 2) as total  FROM pda_maestro as a, pda_detalle as b, ".DB_SELECTRA_PYMEPP.".puntos_venta as d, item as e
                    where
                    a.id=b.id_pda_maestro
                    and
                    b.id_producto=e.id_item
                    and
                    b.id_instalacion_origen=d.codigo_siga_punto
                    and a.id=".$id_transaction
                    );
            }
            else
            {
             $consultar = $conn->ObtenerFilasBySqlSelect("SELECT a.id, b.id as id_detalle, a.orden_compra, a.transporte,  date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_inicio, date_format(b.fecha_fin, '%d/%m/%Y') as fecha_fin, f.direccion, e.descripcion1, b.cantida_x_kilo, b.observaciones, format(((e.cantidad_bulto*b.cantida_x_kilo)/1000), 2) as total  FROM pda_maestro as a, pda_detalle as b, proveedores as d, item as e, instalacion_proveedores as f, ".DB_SELECTRA_PYMEPP.".estados as g
                    where
                    a.id=b.id_pda_maestro
                    and
                    b.id_producto=e.id_item
                    and
                    b.id_instalacion_origen=f.codigo_sica
                    and
                    f.estado=g.id
                    and
                    f.codigo_sica=b.id_instalacion_origen
                    and
                    a.id_proveedor=d.id_proveedor
                    and a.id=".$id_transaction."

                    union

                    SELECT a.id, b.id as id_detalle, a.orden_compra, a.transporte,  date_format(b.fecha_inicio, '%d/%m/%Y') as fecha_inicio, date_format(b.fecha_fin, '%d/%m/%Y') as fecha_fin, d.direccion_punto as direccion,   e.descripcion1, b.cantida_x_kilo, b.observaciones, format(((e.cantidad_bulto*b.cantida_x_kilo)/1000), 2) as total  FROM pda_maestro as a, pda_detalle as b, ".DB_SELECTRA_PYMEPP.".puntos_venta as d, item as e,  ".DB_SELECTRA_PYMEPP.".estados as g
                    where
                    a.id=b.id_pda_maestro
                    and
                    b.id_producto=e.id_item
                    and
                    b.id_instalacion_origen=d.codigo_siga_punto
                    and
                    d.codigo_estado_punto=g.codigo_estado
                    and a.id=".$id_transaction

                    ); 

            }
            //print_r($consultar);exit();
             if($consultar[0]['cantida_x_kilo']!=null)
             {
            
             $tabla= 
             '<tr class="detalle_items">
                <td colspan="8">
                    <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                        <table>
                            <thead>
                                <th style="width:150px; font-weight: bold;">Tipo Transporte</th>
                                <th style="width:150px; font-weight: bold;">Fecha Desde</th>
                                <th style="width:150px; font-weight: bold;">Fecha Hasta</th>
                                 <th style="width:150px; font-weight: bold;">Producto</th>
                                <th style="width:150px; font-weight: bold;">Origen</th>
                                <th style="width:150px; font-weight: bold;">Cantidad</th>
                                <th style="width:150px; font-weight: bold;">Observaciones</th>
                                <th style="width:110px; font-weight: bold; text-align: center;">Acciones</th>
                            </thead>
                        <tbody>';

                foreach ($consultar as $key => $item) 
                {
                    $total+= $item["cantidad"];
                    $tabla.= '
                            <tr>
                                <td style="width:150px; padding-left:10px;">' . $item["transporte"] . '</td>
                                <td style="width:150px; padding-left:10px;">' . $item["fecha_inicio"] . '</td>
                                <td style="width:150px; padding-left:10px;">' . $item["fecha_fin"] . '</td>
                                <td style="width:150px; padding-left:10px;">' . $item["descripcion1"] . '</td>
                                <td style="width:300px;">' . $item["direccion"] .'</td>
                                
                                <td style="width:110px; text-align: right; padding-right:10px;">' . $item["cantida_x_kilo"] . '</td>
                                <td style="text-align: right; padding-right:10px;">' . $item['observaciones'] . '</td>
                                <td style="cursor:pointer; width:30px; text-align:center;">
                                ';
                                if($item['id_transporte_camion']==null || $item['id_transporte_camion']=="")
                                {
                                 $tabla.=
                                 '<img class="editar" id='.$item['id_detalle'].' width="17" height="17" onclick="editarPDA(this)" title="Editar" src="../../../includes/imagenes/edit.gif" />
                                 ';
                                }
                                else
                                {
                                   $tabla.=
                                    '<img class="editar" id='.$item['id_distribucion'].' width="35" height="35" title="Distribución Despachada" src="../../../includes/imagenes/truckok.png" style="color:red"/>
                                    ';
                                }
                    $tabla.='
                                </td>
                            </tr>';
                }
              
                        
                    echo $tabla;
                }
                else
                {
                    echo   
                    '<tr class="detalle_items">
                        <td colspan="8">
                            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                            <table>
                                <thead>
                                    <th style="width:550px; font-weight: bold; text-align: center;" colspan="5">Sin Carga</th>
                                    <tr>
                                    <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">No se han hechos distribucciones de este producto</td>
                                    </tr>';
                }


        break;

        case "getPDACompras" :
            $datos=$conn->ObtenerFilasBySqlSelect("Select a.*, b.id_proveedor, c.descripcion as descripcion_proveedor, d.codigo_barras, d.descripcion1 as producto, a.cantida_x_kilo as cantidad from pda_detalle as a, pda_maestro as b, proveedores as c, item as d where a.id and b.id=a.id_pda_maestro and c.id_proveedor=b.id_proveedor and d.id_item=a.id_producto and a.id=".$_POST['iddetalle']);
            //print_r($datos); exit();
            echo json_encode($datos);
        break;

        case "updatePDACompras" :

            $_POST['fecha_inicio']= date("Y-m-d", strtotime(str_replace('/', '-', $_POST["fecha_inicio"])));
            $_POST['fecha_fin'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST["fecha_fin"])));
            $resultado=$conn->Execute2("update pda_detalle set id_instalacion_origen='".$_POST['destino']."', fecha_inicio='".$_POST['fecha_inicio']."', fecha_fin='".$_POST['fecha_fin']."', cantida_x_kilo=".$_POST['cantidad'].", observaciones='".$_POST['observacion']."' where id=".$_POST['iddetalle']);
            if($resultado)
            {
                echo "1";
            }else
            {
                echo "0";
            }


        break;

    	case "eliminarTipoVehiculo":
        
            if($conn->Execute2("delete  FROM tipo_vehiculo  WHERE id = '" . $_POST["id"] . "'"))
            {
                echo 1;
            }
            else
            {
                echo -1;
            }
            
        break;

        case "ValidarDescripcionTipoVehiculo":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id FROM tipo_vehiculo WHERE descripcion= '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            
        break;

        case "eliminarModeloTransporte":
        
            if($conn->Execute2("delete  FROM transporte_modelo  WHERE id = '" . $_POST["id"] . "'"))
            {
                echo 1;
            }
            else
            {
                echo -1;
            }
            
        break;

        case "ValidarDescripcionModelo":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id FROM transporte_modelo  WHERE descripcion_modelo = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        break;

        case "eliminarMarcaTransporte":
        
            if($conn->Execute2("delete  FROM transporte_marca  WHERE id = '" . $_POST["id"] . "'"))
            {
                echo 1;
            }
            else
            {
                echo -1;
            }
            
        break;

        case "ValidarDescripcionMarca":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id FROM transporte_marca  WHERE descripcion = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        break;

        case "getPdaCamionEstado":
        
            $estado=$_GET["camion_estado"];
            $sql="SELECT id, concat(placa,' -- ',serial_carroceria) as nombre  from transporte_camion where flota_asignada='".$estado."'";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            if (count($campos) == 0) 
            {
                echo "[{id:'-1'}]";
            } 
            else 
            {
                echo json_encode($campos);
            }
        
        break;

        case "getTransporteModelo":
        
            $marca=$_GET["marca"];
            $sql="SELECT id, descripcion_modelo  from transporte_modelo where id_marca='".$marca."'";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            if (count($campos) == 0) 
            {
                echo "[{id:'-1'}]";
            } 
            else 
            {
                echo json_encode($campos);
            }
        
        break;

        case "ValidarAliasGps":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT alias_gps FROM transporte_camion  WHERE alias_gps = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        
        break;
        case "ValidarSerialGps":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT serial_gps FROM transporte_camion  WHERE serial_gps = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        
        break;
        
        case "ValidarUnidadTransporte":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT unidad FROM transporte_camion  WHERE unidad = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        
        break;
        case "ValidarPlacaTransporte":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT placa FROM transporte_camion  WHERE placa = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        
        break;
        case "ValidarSerialMotor":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT serial_motor FROM transporte_camion  WHERE serial_motor = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        
        break;
        case "ValidarSerialCarroceria":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT serial_carroceria FROM transporte_camion  WHERE serial_carroceria = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        
        break;
        case "ValidarCedulaConductor":
        
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT cedula FROM transporte_conductores  WHERE cedula = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
        
        break;

        case "ValidarOrdenCompra":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT orden_compra FROM pda_maestro  WHERE orden_compra = '" . $_GET["v1"] . "'");

            if(count($campos)>0)
            {

                $campos = $conn->ObtenerFilasBySqlSelect("SELECT orden_compra FROM pda_maestro  WHERE orden_compra like '%" . $_GET["v1"] . "-%' order by orden_compra  desc limit 1");
                if(count($campos)>0)
                {
                   
                    $ultimo=explode('-', $campos[0]['orden_compra']);
                    echo $_GET["v1"]."-".($ultimo[1]+1);
                }
                else
                {
                    echo $_GET["v1"]."-1";
                }
                
            }
            else
            {
                echo $_GET["v1"];
            }
            
        
        break;

        case "obtenerIdConductor" :
            $id=isset($_POST['id']) ? $_POST['id'] : '';
            $consulta=$conn->ObtenerFilasBySqlSelect("select id_transporte_chofer from distribucion_pda where id=".$id);
            if($consulta!=null)
            {
                echo $consulta[0]['id_transporte_chofer'];
            }
            else
            {
                
                echo -1;
            }

        break;

        case "obtenerIdCamion" :
            $id=isset($_POST['id']) ? $_POST['id'] : '';
            $consulta=$conn->ObtenerFilasBySqlSelect("select id_transporte_camion, date_format(fecha_ejecucion_transporte, '%d-%m-%Y') as fecha_ejecucion_transporte from distribucion_pda where id=".$id);
            if($consulta!=null)
            {
                echo json_encode($consulta);
            }
            else
            {
                echo "";
            }

        break;

        case "eliminarCamion" :
        $id=isset($_POST['id']) ? $_POST['id'] : '';
        if($conn->Execute2("delete from transporte_camion where id=".$id))
        {
            echo 1;
        }
        else
        {
            echo 2;
        }

        break;

        case "eliminarConductor" :
        $id=isset($_POST['id']) ? $_POST['id'] : '';
        if($conn->Execute2("delete from transporte_conductores where id=".$id))
        {
            echo 1;
        }
        else
        {
            echo 2;
        }

        break;
    	
    	case "pdaCamion" :

        $camion=isset($_POST['camion']) ? $_POST['camion'] : '';
        $fecha=isset($_POST['fecha']) ? $_POST['fecha'] : '';
        $date = new DateTime($fecha);
        $id_distribucion=isset($_POST['id_distribucion']) ? $_POST['id_distribucion'] : '';

        if($id_distribucion!="")
        {
            $editar_distribuccion=$conn->Execute2("update distribucion_pda set id_transporte_camion=".$camion.", fecha_ejecucion_transporte='".$date->format('Y-m-d')."' where id=".$id_distribucion);
            if($editar_distribuccion)
            {
                echo 1;    
            }
            else
            {
                echo 0;
            }
            
        }
        else
        {
            echo 0;
        }

        break;

        case "pdaConductor" :

        /*$cedula=isset($_POST['cedula']) ? $_POST['cedula'] : '';
        $nombres=isset($_POST['nombres']) ? $_POST['nombres'] : '';
        $apellidos=isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
        $telefono=isset($_POST['telefono']) ? $_POST['telefono'] : '';*/
        $conductor=isset($_POST['conductor']) ? $_POST['conductor'] : '';
        $id_distribucion=isset($_POST['id_distribucion']) ? $_POST['id_distribucion'] : '';

        /*$detalle_distribucion=$conn->Execute2("INSERT INTO transporte_conductores( cedula, nombres, apellidos, telefono, flota_asignado) VALUES 
            (
            '".$cedula."',
            '".$nombres."',
            '".$apellidos."',
            '".$telefono."',
            '".$flota_asignada."'
            )");
        */

        if($id_distribucion!="")
        {
            //$obtener_id=$conn->ObtenerFilasBySqlSelect("select max(id) as id from transporte_conductores");
            $editar_distribuccion=$conn->Execute2("update distribucion_pda set id_transporte_chofer=".$conductor." where id=".$id_distribucion);
            if($editar_distribuccion)
            {
                echo 1;    
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }

        break;

        case "det_pda_transporte" :
      
        $consultar=$conn->ObtenerFilasBySqlSelect("select b.id_transporte_camion as camion, b.id_transporte_chofer as conductor, d.transporte, b.id as id_distribucion, a.cantida_x_kilo as cantidad_origen, b.cantidad, b.fecha_inicio_logistica as fecha_inicio, b.fecha_fin_logistica as fecha_fin, nombre_punto, b.observaciones_logistica from pda_detalle as a, distribucion_pda as b, ".DB_SELECTRA_PYMEPP.".puntos_venta as c, pda_maestro as d where a.id_pda_maestro=d.id and a.id=b.id_pda_detalle and b.destino_logistica=c.codigo_siga_punto and a.id=".$_POST['id_transaccion']);
        if($consultar[0]['cantidad']!=null)
        {
        
         $tabla= 
         '<tr class="detalle_items">
            <td colspan="8">
                <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                    <table>
                        <thead>
                            <th style="width:150px; font-weight: bold;">Tipo Transporte</th>
                            <th style="width:150px; font-weight: bold;">Fecha Desde</th>
                            <th style="width:150px; font-weight: bold;">Fecha Hasta</th>
                            <th style="width:150px; font-weight: bold;">Destino</th>
                            <th style="width:150px; font-weight: bold;">Observaciones</th>
                            <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                            <th style="width:110px; font-weight: bold; text-align: center;">Acciones</th>
                        </thead>
                    <tbody>';
            $bandera=0;
            foreach ($consultar as $key => $item) 
            {
            $total+= $item["cantidad"];
            $tabla.= '
                    <tr>
                        <td style="width:150px; padding-left:10px;">' . $item["transporte"] . '</td>
                        <td style="width:150px; padding-left:10px;">' . $item["fecha_inicio"] . '</td>
                        <td style="width:150px; padding-left:10px;">' . $item["fecha_fin"] . '</td>
                        <td style="width:300px;">' . $item["nombre_punto"] .'</td>
                        <td style="text-align: right; padding-right:10px;">' . $item['observaciones_logistica'] . '</td>
                        <td style="width:110px; text-align: right; padding-right:10px;">' .$item["cantidad"]. '</td>
                        <td style="cursor:pointer; width:30px; text-align:center;">';

                            if($item['camion']==NULL)
                            {
                                $tabla.='<img class="editar" id='.$item['id_distribucion'].' width="20" height="20" name="camion" onclick="activar(this)" title="Agregar Camión"src="../../libs/imagenes/camion.png" />';
                            }
                            else
                            {   
                                $bandera++;
                                $tabla.='<img class="editar" id='.$item['id_distribucion'].' width="20" height="20" name="camion" title="Camión Agregado" onclick="activar(this,1)" src="../../libs/imagenes/camion.png" />';
                            }
                            
                            if($item['conductor']==NULL)
                            {
                                $tabla.='<img class="editar" id='.$item['id_distribucion'].' width="20" height="20" name="conductor" onclick="activar(this)" title="Agregar Conductor"src="../../libs/imagenes/ico_user.gif" />';
                            }
                            else
                            {
                                $bandera++;
                                $tabla.='<img class="editar" id='.$item['id_distribucion'].' width="20" height="20" name="conductor" title="Conductor Agregado" onclick="activar(this,1)" src="../../libs/imagenes/ico_user.gif" />';
                            }

                            if($bandera==2)
                            {
                                $tabla.='<img class="editar" id='.$item['id_distribucion'].' width="20" height="20"  title="Imprimir Detalle" onclick="javascript:window.open(\'../../reportes/pda_transporte_detalle.php?id_transaccion='.$item['id_distribucion'].'\')" src="../../../includes/imagenes/ico_print.gif" />';   
                            }

                            $tabla.='
                        </td>
                    </tr>';
            $bandera=0;
            }
            $tabla.='
                <tr>
                    <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">TOTAL CANTIDAD</td>
                    <td style="width:110px; text-align: right; padding-right:10px;" >'.$total. '</td>
                </tr>
                <tr>
                    <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">TOTAL PEDIDO</td>
                    <td style="width:110px; text-align: right; padding-right:10px;">'.$item["cantidad_origen"]. '</td>
                </tr>
                <tr>
                    <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">RESTANTE</td>
                    <td style="width:110px; text-align: right; padding-right:10px;">'.($item["cantidad_origen"]-$total). '</td>
                </tr>';
                    
                echo $tabla;
            }
            else
            {
            echo   
            '<tr class="detalle_items">
                <td colspan="8">
                    <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                    <table>
                        <thead>
                                <th style="width:550px; font-weight: bold; text-align: center;" colspan="5">Sin Carga</th>
                            <tr>
                                <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">No se han hechos distribucciones de este producto</td>
                            </tr>';
            }
        
                
        break;

        case "reporte_productoSobreLimite3": //Creado por Humberto
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $unidades = $_GET["unidades"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $codigo_barras = $_GET["codigo_barras"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has){

                            echo " Los Años Deben Ser Iguales"; exit();
                    }else{

                    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_pyme";
                    }


                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*Original y funcional*/
                    $sql = "SELECT v.rif,v.nombre as nombre,v.codigo_barras,v._item_descripcion, v._item_cantidad as UNITS,v._item_totalconiva as PRICE,v.fecha_creacion,v.codigo_siga as codigo_siga,
                    pv.nombre_punto as nombre_punto, et.nombre_estado as nombre_estado,mc.marca as marca,um.nombre_unidad as nombre_unidad, i.pesoxunidad as pesoxunidad
                    FROM $BD_central.$ventas_mes_anno v
                    LEFT JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                    LEFT JOIN $BD_central.estados et ON pv.codigo_estado_punto = et.codigo_estado
                    LEFT JOIN $BD_selectrapyme.item i ON i.codigo_barras = v.codigo_barras
                    LEFT JOIN $BD_selectrapyme.unidad_medida um ON i.unidadxpeso = um.id
                    LEFT JOIN $BD_selectrapyme.marca mc ON mc.id = i.id_marca
                    WHERE v._item_cantidad>0
                    AND v.fecha_creacion BETWEEN '$desde 00:00:00' and '$hasta 23:59:59'
                    ";

                  
                    /*$sql= "SELECT v.taxid,v.name_persona,v.CODE as CODE,v.nombre_producto,v.units as UNITS,v.price as PRICE,v.datenew_ticketlines,v.codigo_siga,pv.nombre_punto,et.nombre_estado
                        FROM $BD_central.$ventas_mes_anno v
                        INNER JOIN $BD_central.grupo gp ON gp.grupopos = v.category
                        INNER JOIN $BD_central.puntos_venta pv
                        INNER JOIN $BD_central.estados et

                        WHERE pv.codigo_estado_punto = et.codigo_estado
                        AND pv.codigo_siga_punto = v.codigo_siga
                        AND v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    //ORIGINAL como ejemplo para el nuevo ajax
                   /*$sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";*/


                    if($punto!="0"){
                        $sql.=" and codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and codigo_estado_punto='$estado'";
                    }
                    /*if($unidades!=""){
                        $sql.=" and units>='$unidades'";
                    }*/
                    /*if($unidades!="0"){

                        $sql.=" and v.category='$categoria'";
                    }*/
                    if ($codigo_barras!="") {
                        $sql.=" and i.codigo_barras = '$codigo_barras'";
                    }


                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    $sql.=" ORDER BY v.fecha_creacion,nombre_punto,descripcion1";
                    //echo $sql; 
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="11" align="center"><b><font color="white">REPORTE DE UNIDADES VENDIDAS POR CLIENTE DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <!--<td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>-->
                            <td width="50px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="80px" align="center"><b><font color="white">CEDULA o RIF</font></b></td>
                            <td width="150px" align="center"><b><font color="white">NOMBRE Y APELLIDO</font></b></td>
                            <td width="120px" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="300px" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="50px" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="80px" align="center"><b><font color="white">PRECIO</font></b></td>
                            <td width="100px" align="center"><b><font color="white">FECHA Y HORA</font></b></td>
                            <td width="50px" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="150px" align="center"><b><font color="white">PUNTO</font></b></td>
                            <td width="100px" align="center"><b><font color="white">ESTADO</font></b></td>
                        </tr>
                        </table>
                    <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        $i=1;
                        //print_r($reporte);exit();
                        foreach ($reporte as $lista ) {

                            $total_iva=$lista["UNITS"]*$lista["PRICE"];
                            $totalUni+=$lista["UNITS"];
                            $totalPrice+=$total_iva;
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/

                     ?>
                        <tr>
                            <td width="50px"align="center"><?php echo $i; ?></td>
                            <td width="80px"><?php echo $lista["rif"] ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre"]); ?></td>
                            <td width="120px" align="center"><?php echo $lista["codigo_barras"] ?></td>
                            <td width="300px" align="center">
                                <?php
                                //echo utf8_encode($lista["nombre_producto"]);
                                echo utf8_encode($lista["_item_descripcion"]);
                                echo " - ".$lista["marca"]." ";
                                echo $lista["pesoxunidad"].$lista["nombre_unidad"];
                                ?>
                            </td>
                            <td width="50px" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="80px" align="center"><?php echo number_format($lista["PRICE"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo $lista["fecha_creacion"]; ?></td>
                            <td width="50px" align="center"><?php echo $lista["codigo_siga"]; ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre_punto"]); ?></td>
                            <td width="100px" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                    ?>
                     <tr>
                        <td colspan="11"><hr color="black" size="2" /></td>
                    </tr>
                     <tr>
                            <td></td>
                            <td></td>
                            <td align="center"></td>
                            <td align="center"><b>TOTAL UNIDADES</b></td>
                            <td align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                    </tr>
                    <tr>
                            <td></td>
                            <td></td>
                            <td align="center"></td>
                            <td align="center"><b>TOTAL VENTAS</b></td>
                            <td align="center"><b><?php echo number_format($totalPrice, 2, ',', '.');  ?></b></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                    </tr>
                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }



            break;

            case "observacion_migracion": //Creado por Humberto
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $unidades = $_GET["unidades"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);


                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*Original y funcional*/
                    $sql = "SELECT * 
                    FROM $BD_central.observaciones_migracion v
                    LEFT JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                    LEFT JOIN $BD_central.estados et ON pv.codigo_estado_punto = et.codigo_estado
                    WHERE  observacion LIKE  '%No se encuentra registrado %'
                    AND v.fecha BETWEEN '$desde' and '$hasta'
                    ";

                  
                    /*$sql= "SELECT v.taxid,v.name_persona,v.CODE as CODE,v.nombre_producto,v.units as UNITS,v.price as PRICE,v.datenew_ticketlines,v.codigo_siga,pv.nombre_punto,et.nombre_estado
                        FROM $BD_central.$ventas_mes_anno v
                        INNER JOIN $BD_central.grupo gp ON gp.grupopos = v.category
                        INNER JOIN $BD_central.puntos_venta pv
                        INNER JOIN $BD_central.estados et

                        WHERE pv.codigo_estado_punto = et.codigo_estado
                        AND pv.codigo_siga_punto = v.codigo_siga
                        AND v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    //ORIGINAL como ejemplo para el nuevo ajax
                   /*$sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";*/


                    if($punto!="0"){
                        $sql.=" and codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and codigo_estado_punto='$estado'";
                    }
                    /*if($unidades!=""){
                        $sql.=" and units>='$unidades'";
                    }*/
                    /*if($unidades!="0"){

                        $sql.=" and v.category='$categoria'";
                    }*/


                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    $sql.=" GROUP BY observacion, factura ORDER BY observacion, nombre_estado";
                    //echo $sql; 
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="13" align="center"><b><font color="white">REPORTE DE UNIDADES VENDIDAS POR CLIENTE DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <!--<td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>-->
                            <td width="50px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="400px" align="center"><b><font color="white">OBSERVACION</font></b></td>
                            <td width="150px" align="center"><b><font color="white">FACTURA</font></b></td>
                            <td width="120px" align="center"><b><font color="white">FECHA</font></b></td>
                            <td width="50px" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="150px" align="center"><b><font color="white">PUNTO</font></b></td>
                            <td width="100px" align="center"><b><font color="white">ESTADO</font></b></td>
                        </tr>
                        </table>
                    <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        $i=1;
                        //print_r($reporte);exit();
                        foreach ($reporte as $lista ) {

                            $total_iva=$lista["PRICE"];
                            $totalUni+=$lista["UNITS"];
                            $totalPrice+=$total_iva;
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/
                            if ($lista["monto_credito"]>0) {

                                $tipo="Credito";
                            }else{
                                $tipo="Contado";
                            }

                     ?>
                        <tr>
                            <td width="50px"align="center"><?php echo $i; ?></td>
                            <td width="400px" align="center"><?php echo utf8_encode($lista["observacion"]); ?></td>
                            <td width="150px" align="center"><?php echo $lista["factura"] ?></td>
                            <td width="120px" align="center"><?php echo $lista["fecha"]; ?></td>
                            <td width="50px" align="center"><?php echo $lista["codigo_siga"]; ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre_punto"]); ?></td>
                            <td width="100px" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                    ?>
                     <tr>
                        <td colspan="11"><hr color="black" size="2" /></td>
                    </tr>
                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }



            break;

              case "reporte_productoSobreLimite4": //Creado por Humberto
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $unidades = $_GET["unidades"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has){

                            echo " Los Años Deben Ser Iguales"; exit();
                    }else{

                    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_pyme";
                    }


                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*Original y funcional*/
                    $sql = "SELECT v.rif,v.nombre as nombre,v.id_factura as id_factura ,v.cod_factura_fiscal as cod_factura_fiscal, sum(v._item_cantidad) as UNITS,v.TotalTotalFactura as PRICE,v.fecha_creacion,v.codigo_siga as codigo_siga,
                    pv.nombre_punto as nombre_punto, et.nombre_estado as nombre_estado,mc.marca as marca,um.nombre_unidad as nombre_unidad, i.pesoxunidad as pesoxunidad, v.nombreyapellido as usuario, v.monto_credito 
                    FROM $BD_central.$ventas_mes_anno v
                    LEFT JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                    LEFT JOIN $BD_central.estados et ON pv.codigo_estado_punto = et.codigo_estado
                    LEFT JOIN $BD_selectrapyme.item i ON i.codigo_barras = v.codigo_barras
                    LEFT JOIN $BD_selectrapyme.unidad_medida um ON i.unidadxpeso = um.id
                    LEFT JOIN $BD_selectrapyme.marca mc ON mc.id = i.id_marca
                    WHERE v._item_cantidad>0
                    AND v.fecha_creacion BETWEEN '$desde 00:00:00' and '$hasta 23:59:59'
                    ";

                  
                    /*$sql= "SELECT v.taxid,v.name_persona,v.CODE as CODE,v.nombre_producto,v.units as UNITS,v.price as PRICE,v.datenew_ticketlines,v.codigo_siga,pv.nombre_punto,et.nombre_estado
                        FROM $BD_central.$ventas_mes_anno v
                        INNER JOIN $BD_central.grupo gp ON gp.grupopos = v.category
                        INNER JOIN $BD_central.puntos_venta pv
                        INNER JOIN $BD_central.estados et

                        WHERE pv.codigo_estado_punto = et.codigo_estado
                        AND pv.codigo_siga_punto = v.codigo_siga
                        AND v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    //ORIGINAL como ejemplo para el nuevo ajax
                   /*$sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";*/


                    if($punto!="0"){
                        $sql.=" and codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and codigo_estado_punto='$estado'";
                    }
                    /*if($unidades!=""){
                        $sql.=" and units>='$unidades'";
                    }*/
                    /*if($unidades!="0"){

                        $sql.=" and v.category='$categoria'";
                    }*/


                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    $sql.=" GROUP BY v.id_factura, v.rif ORDER BY v.fecha_creacion,nombre_punto,descripcion1";
                    //echo $sql; 
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="13" align="center"><b><font color="white">REPORTE DE UNIDADES VENDIDAS POR CLIENTE DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <!--<td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>-->
                            <td width="50px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="100px" align="center"><b><font color="white">CEDULA o RIF</font></b></td>
                            <td width="150px" align="center"><b><font color="white">NOMBRE Y APELLIDO</font></b></td>
                            <td width="120px" align="center"><b><font color="white">NRO FACTURA ESTABLECIMIENTO</font></b></td>
                            <td width="120px" align="center"><b><font color="white">NRO FACTURA FISCAL</font></b></td>
                            <td width="80px" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="100px" align="center"><b><font color="white">TOTAL FACTURA</font></b></td>
                            <td width="100px" align="center"><b><font color="white">FECHA Y HORA</font></b></td>
                            <td width="100px" align="center"><b><font color="white">USUARIO CREACION</font></b></td>
                            <td width="50px" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="150px" align="center"><b><font color="white">PUNTO</font></b></td>
                            <td width="100px" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="50px" align="center"><b><font color="white">TIPO</font></b></td>
                        </tr>
                        </table>
                    <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        $i=1;
                        //print_r($reporte);exit();
                        foreach ($reporte as $lista ) {

                            $total_iva=$lista["PRICE"];
                            $totalUni+=$lista["UNITS"];
                            $totalPrice+=$total_iva;
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/
                            if ($lista["monto_credito"]>0) {

                                $tipo="Credito";
                            }else{
                                $tipo="Contado";
                            }

                     ?>
                        <tr>
                            <td width="50px"align="center"><?php echo $i; ?></td>
                            <td width="100px"><?php echo $lista["rif"] ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre"]); ?></td>
                            <td width="120px" align="center"><?php echo $lista["id_factura"] ?></td>
                            <td width="120px" align="center"><?php echo $lista["cod_factura_fiscal"] ?></td>
                            <td width="80px" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo number_format($lista["PRICE"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo $lista["fecha_creacion"]; ?></td>
                            <td width="100px" align="center"><?php echo $lista["usuario"]; ?></td>
                            <td width="50px" align="center"><?php echo $lista["codigo_siga"]; ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre_punto"]); ?></td>
                            <td width="100px" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>
                            <td width="50px" align="center"><?php echo $tipo; ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                    ?>
                     <tr>
                        <td colspan="11"><hr color="black" size="2" /></td>
                    </tr>
                     <tr>
                            <td></td>
                            <td></td>
                            <td align="center"></td>
                            <td align="center"><b>TOTAL UNIDADES</b></td>
                            <td align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                    </tr>
                    <tr>
                            <td></td>
                            <td></td>
                            <td align="center"></td>
                            <td align="center"><b>TOTAL VENTAS</b></td>
                            <td align="center"><b><?php echo number_format($totalPrice, 2, ',', '.');  ?></b></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                    </tr>
                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }



            break;

        case "getInstalaciones" :
        $proveedor=$_GET["idProveedor"];
            $sql="SELECT a.codigo_sica, UPPER(concat(b.nombre,' --- ',a.direccion)) as instalacion from instalacion_proveedores as a, estados as b where a.estado=b.id and a.id_proveedor=".$proveedor;
            
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            
                echo json_encode($campos);
            

            break;

            case "eliminarDistribucion":
            $id=$_GET['id_distribucion'];
            $sql="delete from distribucion_pda where id=".$id;
            if($conn->Execute2($sql))
            {
                echo 1;
            }
            else
            {
                echo 2;
            }


        break;
            
        case "instalacionproveedores" :
        if($_POST['cantidad']==0)
        {
        $campos_comunes = $conn->ObtenerFilasBySqlSelect("select * from estados");
        $tabla="
        <table width='100%' align='left' style='margin-left: -265px;' border='0'>
            <tr>
                    <td colspan='3' style='width: 43%; vertical-align: top;' class='tb-head'>
                    Estado**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td>
                    <select name='estado[]' id='estado[]' class='form-text'>
                        ";
                        foreach ($campos_comunes as $key => $item) 
                        {
                        $tabla.="<option value=".$item["id"].">".utf8_encode($item["nombre"])."</option>";
                        }
                        $tabla.="
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 15%; vertical-align: top;' class='tb-head'>
                        Direccion**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td>
                        <input type='text' size='60' name='direccion[]'  class='form-text' id=direccion[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 15%; vertical-align: top;' class='tb-head'>
                        Codigo SICA**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td>
                        <input type='text' size='60' name='codigo_sica[]'  class='form-text' id=cod_sica[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 15%; vertical-align: top;' class='tb-head'>
                        Responsable&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td>
                        <input type='text' size='60' name='responsable[]'  class='form-text' id=responsable[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 15%; vertical-align: top;' class='tb-head'>
                        Correo Responsable**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td>
                        <input type='text' size='60' name='correo_responsable[]'  class='form-text' id=correo_responsable[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 15%; vertical-align: top;' class='tb-head'>
                        Telefono Responsable**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td>
                        <input type='text' size='60' name='telefono_responsable[]'  class='form-text' id=telefono_responsable[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='4'>&nbsp;
                    </td>
                </tr>
            <input type='hidden' name='cantidad_ob' id='cantidad_ob' value='".($_POST['cantidad']+1)."' />
        </table>
        ";
        echo $tabla; exit();
        }
        else
        {
        $i=0;
        $campos_comunes = $conn->ObtenerFilasBySqlSelect("select * from estados");
            
        $tabla=
            "               
            <table width='80%' align='left' style='margin-left: -180px;'  border='0'>
            ";
        while($i<$_POST['cantidad']){
            $tabla.="
                <tr>
                    <td colspan='3' style='width: 10%; vertical-align: top;' class='tb-head'>
                    Estado**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='width: 15%;'>
                        <select name='estado[]' id='estado[]' class='form-text'>
                        ";
                        foreach ($campos_comunes as $key => $item) 
                        {
                        $tabla.="<option value=".$item["id"].">".utf8_encode($item["nombre"])."</option>";
                        }
                        $tabla.="
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 10%; vertical-align: top;' class='tb-head'>
                        Direccion**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='width: 10%;'>
                        <input type='text' size='60' name='direccion[]'  class='form-text' id=direccion[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 10%; vertical-align: top;' class='tb-head'>
                        Codigo SICA**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='width: 10%;'>
                        <input type='text' size='60' name='codigo_sica[]'  class='form-text' id=cod_sica[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 10%; vertical-align: top;' class='tb-head'>
                        Responsable&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='width: 10%;'>
                        <input type='text' size='60' name='responsable[]'  class='form-text' id=responsable[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 10%; vertical-align: top;' class='tb-head'>
                        Correo Responsable**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='width: 10%;'>
                        <input type='text' size='60' name='correo_responsable[]'  class='form-text' id=correo_responsable[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='width: 10%; vertical-align: top;' class='tb-head'>
                        Telefono Responsable**&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style='width: 10%';>
                        <input type='text' size='60' name='telefono_responsable[]'  class='form-text' id=telefono_responsable[]/>
                    </td>
                </tr>
                <tr>
                    <td colspan='4'>&nbsp;
                    </td>
                </tr>
                

            ";
            $i++;
            }
            $tabla.=
                "
                    <input type='hidden' name='cantidad_ob' id='cantidad_ob' value='".($_POST['cantidad']+1)."' />
                </table>
                ";
                
            echo $tabla; exit();
            
        }


        break;

        case "det_pda" :
      
        $consultar=$conn->ObtenerFilasBySqlSelect("select d.transporte, b.id as id_distribucion, a.cantida_x_kilo as cantidad_origen, b.cantidad, b.fecha_inicio_logistica as fecha_inicio, b.fecha_fin_logistica as fecha_fin, nombre_punto, b.observaciones_logistica, b.id_transporte_camion from pda_detalle as a, distribucion_pda as b, ".DB_SELECTRA_PYMEPP.".puntos_venta as c, pda_maestro as d where a.id_pda_maestro=d.id and a.id=b.id_pda_detalle and b.destino_logistica=c.codigo_siga_punto and a.id=".$_POST['id_transaccion']);
         if($consultar[0]['cantidad']!=null)
         {
        
         $tabla= 
         '<tr class="detalle_items">
            <td colspan="8">
                <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                    <table>
                        <thead>
                            <th style="width:150px; font-weight: bold;">Tipo Transporte</th>
                            <th style="width:150px; font-weight: bold;">Fecha Desde</th>
                            <th style="width:150px; font-weight: bold;">Fecha Hasta</th>
                            <th style="width:150px; font-weight: bold;">Destino</th>
                            <th style="width:150px; font-weight: bold;">Observaciones</th>
                            <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                            <th style="width:110px; font-weight: bold; text-align: center;">Acciones</th>
                        </thead>
                    <tbody>';

            foreach ($consultar as $key => $item) 
            {
                $total+= $item["cantidad"];
                $tabla.= '
                        <tr>
                            <td style="width:150px; padding-left:10px;">' . $item["transporte"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["fecha_inicio"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["fecha_fin"] . '</td>
                            <td style="width:300px;">' . $item["nombre_punto"] .'</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['observaciones_logistica'] . '</td>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["cantidad"] . '</td>
                            <td style="cursor:pointer; width:30px; text-align:center;">
                            ';
                            if($item['id_transporte_camion']==null || $item['id_transporte_camion']=="")
                            {
                             $tabla.=
                             '<img class="editar" id='.$item['id_distribucion'].' width="25" height="25" onclick="eliminarDistribucion(this)" title="Eliminar"src="../../../includes/imagenes/delete.gif" />
                             ';
                            }
                            else
                            {
                               $tabla.=
                                '<img class="editar" id='.$item['id_distribucion'].' width="35" height="35" title="Distribución Despachada" src="../../../includes/imagenes/truckok.png" style="color:red"/>
                                ';
                            }
                $tabla.='
                            </td>
                        </tr>';
            }
            $tabla.='
                <tr>
                    <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">TOTAL CANTIDAD</td>
                    <td style="width:110px; text-align: right; padding-right:10px;" >'.$total. '</td>
                </tr>
                <tr>
                    <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">TOTAL PEDIDO</td>
                    <td style="width:110px; text-align: right; padding-right:10px;">'.$item["cantidad_origen"]. '</td>
                </tr>
                <tr>
                    <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">RESTANTE</td>
                    <td style="width:110px; text-align: right; padding-right:10px;">'.($item["cantidad_origen"]-$total). '</td>
                </tr>';
                    
                echo $tabla;
            }
            else
            {
                echo   
                '<tr class="detalle_items">
                    <td colspan="8">
                        <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                        <table>
                            <thead>
                                <th style="width:550px; font-weight: bold; text-align: center;" colspan="5">Sin Carga</th>
                                <tr>
                                <td style="width:550px; text-align: right; padding-right:10px;" colspan="5">No se han hechos distribucciones de este producto</td>
                                </tr>';
            }
        
                
        break;
        
        case "distribucion_limite" :

        $iddetalle=isset($_POST['iddetalle']) ? $_POST['iddetalle'] : '';
        $cantidad=isset($_POST['cantidad']) ? $_POST['cantidad'] : '';
        if($cantidad<=0)
        {
            echo "-1";
            exit();
            
        }

        $verificar=$conn->ObtenerFilasBySqlSelect("SELECT sum(b.cantidad) as total, a.cantida_x_kilo as existe FROM pda_detalle as a, distribucion_pda as b  WHERE a.id=b.id_pda_detalle and a.id=".$iddetalle);
        if($verificar[0]['total']==null)
        {
            //verificamos cantidad nada mas

            $verificar1=$conn->ObtenerFilasBySqlSelect("SELECT cantida_x_kilo as pedido from pda_detalle where id=".$iddetalle);

            
        if($cantidad>$verificar1[0]['pedido'])
            echo 0;
        else
            echo 1;

        }
        else
        {
        
        if(($verificar[0]['total']+$cantidad)>$verificar[0]['existe'])
            echo 0;
        else
            echo 1;
        }


        break;

        case "distribucion" : 
        //almacen las variables
        $iddetalle=isset($_POST['iddetalle']) ? $_POST['iddetalle'] : '';
        $destino=isset($_POST['destino']) ? $_POST['destino'] : '';
        $fecha_inicio=isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
        $fecha_fin=isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
        $fecha_inicio=date_create($fecha_inicio);
        $fecha_fin=date_create($fecha_fin);
        $fecha_inicio=date_format($fecha_inicio, "Y-m-d");
        $fecha_fin=date_format($fecha_fin, "Y-m-d");
        $cantidad=isset($_POST['cantidad']) ? $_POST['cantidad'] : '';
        $observacion=isset($_POST['observacion']) ? $_POST['observacion'] : '';
       
       // se guarda la distribucion
        $detalle_distribucion=$conn->Execute2("insert into distribucion_pda (id_pda_detalle, destino_logistica, fecha_inicio_logistica, fecha_fin_logistica, cantidad, observaciones_logistica, usuario_logistica)
            values (".$iddetalle.",'".$destino."','".$fecha_inicio."','".$fecha_fin."',".$cantidad.",'".$observacion."','".$login->getNombreApellidoUSuario()."')");

        if($detalle_distribucion==1)
            echo 1;
        else
            echo 0;

        break;
        
        case "eliminar_asientoCXP":
            $instruccion = "SELECT * FROM cxp_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);

            $instruccion = "delete from tabla_impuestos WHERE numero_control_factura = '" . $campos[0]["numero"] . "' and tipo_documento='c' and totalizar_monto_retencion='" . $campos[0]["monto"] . "'";
            $conn->Execute2($instruccion);

//,fecha_anulacion='".$_GET["fecha"]."',observacion_anulado='".$_GET["motivoAnulacion"]."'
            $instruccion = "update cxp_edocuenta_detalle set marca='',estado = '0',fecha_anulacion='" . $_GET["fecha"] . "',observacion_anulado='" . $_GET["motivoAnulacion"] . "' WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);

            $instruccion = "update cxp_edocuenta set marca = '' WHERE cod_edocuenta = " . $campos[0]["cod_edocuenta"];
            $conn->Execute2($instruccion);

            $instruccion = "delete from cxp_factura_pago WHERE cxp_edocuenta_detalle_fk = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);

            $instruccion = "delete from cxp_edocuenta_formapago WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $conn->Execute2($instruccion);
//echo $instruccion;
            break;
        case "eliminar_asientoCXC":

            $instruccion = "SELECT * FROM cxc_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);

            $instruccion = "delete from cxc_edocuenta_detalle WHERE cod_edocuenta_detalle = '" . $_GET["cod"] . "'";
            echo $conn->Execute2($instruccion);

            $instruccion = "delete from tabla_impuestos WHERE numero_control_factura = '" . $campos[0]["numero"] . "' and tipo_documento='f' and totalizar_monto_retencion='" . $campos[0]["monto"] . "'";
            echo $conn->Execute2($instruccion);

            $instruccion = "update cxc_edocuenta set marca = '' WHERE cod_edocuenta = " . $campos[0]["cod_edocuenta"];
            echo $instruccion;
            $conn->Execute2($instruccion);

            break;
        case "impuestos":
            $instruccion = "SELECT * FROM lista_impuestos as li
            left join formulacion_impuestos as fi on li.cod_formula=fi.cod_formula
            WHERE cod_impuesto= '" . $_GET["cod_impuesto"] . "'";
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);
            $PORCENTAJE = $campos[0]["alicuota"];
            $PAGOMAYORA = $campos[0]["pago_mayor_a"];
            $MONTOSUSTRACCION = $campos[0]["monto_sustraccion"];
            $MONTOBASE = $_GET["monto_base"];
            $formula = $campos[0]["formula"];
            $resultado = eval($formula);

            $calculo = $_GET["monto_islr"] * $porcentaje;
            echo "[{'total_retencion':'" . $MONTO . "','porcentaje':'" . $campos[0]["alicuota"] . "','formula':'" . $campos[0]["formula"] . "','resultado':'" . $MONTO . "','codigo_impuesto':'" . $campos[0]["cod_impuesto"] . "','cod_tipo_impuesto':'" . $campos[0]["cod_tipo_impuesto"] . "'}]";
            break;
        case "impuesto_iva":
            $instruccion = "SELECT * FROM impuesto_iva WHERE cod_impuesto_iva = " . $_GET["cod_impuesto_iva"];
            $campos = $conn->ObtenerFilasBySqlSelect($instruccion);
            $calculo = $_GET["montoiva"] * ($campos[0]["porcentaje"] / 100);
            echo "[{'total_iva':'" . ($calculo) . "','porcentaje':'" . $campos[0]["porcentaje"] . "'}]";
            break;
        case "ValidarCodigoitem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE cod_item = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;

        case "ObtenerIVA":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE id_item = '".$_GET["rubro"]."' ");
            // echo $campos; exit();
            if (count($campos)!=0) {
                echo "[{'rc':'0','ivaproduct':'".$campos[0]["iva"]."'}]";
            }else{
                echo "[{'rc':'-1','ivaproduct':'0'}]";
            }
            break;

case "ValidarCodigoBarrasItem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE codigo_barras = '" . $_GET["v1"] . "'");
                        
            if (count($campos)!=0) {
                                
                $campos2 = $conn->ObtenerFilasBySqlSelect("SELECT cmd._pvp,cmd._estatus_producto,cme.estatus_name
                    FROM selectrapyme_central.cotizacion_mercado_detalle cmd
                    INNER JOIN selectrapyme.item i ON i.id_item = cmd.id_producto
                    INNER JOIN selectrapyme_central.cotizacion_mercado_estatus cme ON cme.id_estatus = cmd._estatus_producto
                    WHERE i.codigo_barras = '" . $_GET["v1"] . "'");

                if (count($campos2)!=0) {
                    echo "[{'rc':'-1','mensaje1':'".$campos2[0]["_pvp"]."','mensaje2':'".$campos2[0]["estatus_name"]."'}]";
                }else{
                    echo "[{'rc':'-1','mensaje1':'0','mensaje2':'Rubro no cotizado'}]";
                }

            }else{

                $campos2 = $conn->ObtenerFilasBySqlSelect("SELECT cmd._pvp,cmd._estatus_producto,cme.estatus_name
                    FROM selectrapyme_central.cotizacion_mercado_detalle cmd
                    INNER JOIN selectrapyme.item i ON i.id_item = cmd.id_producto
                    INNER JOIN selectrapyme_central.cotizacion_mercado_estatus cme ON cme.id_estatus = cmd._estatus_producto
                    WHERE i.codigo_barras = '" . $_GET["v1"] . "'");

                if (count($campos2)!=0) {
                    echo "[{'rc':'0','mensaje1':'".$campos2[0]["_pvp"]."','mensaje2':'".$campos2[0]["estatus_name"]."'}]";
                }else{
                    echo "[{'rc':'0','mensaje1':'0','mensaje2':'Rubro no cotizado'}]";
                }
            };

            break;


        case "ValidarCodigoBarras":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT descripcion1 FROM item WHERE codigo_barras = '" . $_GET["v1"] . "'");
            //echo (count($campos) == 0) ? "1" : "-1";
            if(!$campos[0][descripcion1])
                echo "NO EXISTE EL PRODUCTO";
            else
                echo $campos[0][descripcion1];
            /*echo json_encode(array(
                "success" => true,
                "nombre" => $campos[0][descripcion1]
            ));*/
            break;
        case "crearArchivo":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT codigo_barras,
                CONCAT( i.descripcion1,' ',m.marca,' ',i.pesoxunidad,' ',um.nombre_unidad) as descripcion1,
                i.seriales,i.cod_departamento,i.cod_grupo,i.iva,i.producto_vencimiento,i.unidad_venta,
                pre.precio_sin_iva as precio1 ,pre.id_estado,i.cantidad_bulto
                FROM item i
                JOIN marca m ON i.id_marca=m.id
                JOIN unidad_medida um ON i.unidadxpeso=um.id
                JOIN item_variacion_precio pre ON pre.codigo_barra=i.codigo_barras
                WHERE id_var_precio_cab = '" . $_GET["v1"] . "'");

                $txt = "";
                foreach ($campos as $key => $campo)
                {

                    if ($campo[unidad_venta]='Unidad'){
                        $campo[unidad_venta]=1;
                    }
                    $txt .= $campo[codigo_barras].";";
                    $txt .= $campo[descripcion1].";";
                    $txt .= $campo[seriales].";";
                    $txt .= $campo[cod_departamento].";";
                    $txt .= $campo[cod_grupo].";";
                    $txt .= $campo[iva].";";
                    $txt .= $campo[producto_vencimiento].";";
                    $txt .= $campo[unidad_venta].";";
                    $txt .= $campo[precio1].";";
                    $txt .= $campo[id_estado].";";
                    $txt .= $campo[cantidad_bulto]."\r\n";

                    $precio_iva = $campo[precio1]+(($campo[precio1] * $campo[iva])/100);
                    $instruccion = "UPDATE item SET precio1='".$campo[precio1]."', precio2='".$campo[precio1]."', precio3='".$campo[precio1]."', coniva1='$precio_iva' , coniva2='$precio_iva', coniva3='$precio_iva' WHERE codigo_barras = '".$campo[codigo_barras]."' ;";
                    $conn->Execute2($instruccion); 

                }
                $correl=str_pad($_GET["correl"], 4, "0", STR_PAD_LEFT);
                $nombre = "../../../uploads/precios/actualizacion".$correl.".txt";
                $archivo = fopen($nombre, "w") or die("No se pudo crear el archivo!");
                fwrite($archivo, $txt);
                fclose($archivo);

                $instruccion = "update item_variacion_precio_cabecera set estatus = 'Inactivo' WHERE id_var_precio_cab = '".$_GET["v1"]."'";
                $conn->Execute2($instruccion);
            break;

        case "filtroProveedores":
            /**
             * Procedimiento de busqueda de filtroProveedores
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      levieraf@gmail.com
             *
             */

            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {

                // Filtro para los proveedores
                $id_proveedor = (isset($_POST["id_proveedor"])) ? $_POST["id_proveedor"] : "";
                $rif = (isset($_POST["ciruc"])) ? $_POST["ciruc"] : "";
                $cod_proveedor = (!isset($_POST["cod_proveedor"])) ? "" : $_POST["cod_proveedor"];
                $nombre = (!isset($_POST["proveedor"])) ? "" : $_POST["proveedor"];

                if ($cod_proveedor != "") {
                    $andWHERE .= " and upper(cod_proveedor) like upper('%" . $cod_proveedor . "%')";
                }

                if ($nombre != "") {
                    if ($cod_proveedor != "") {
                        $andWHERE .= " and ";
                    } else {
                        $andWHERE = " and ";
                    }
                    $andWHERE .= " upper(descripcion) like upper('%" . $nombre . "%')";
                }

                $sql = "SELECT * FROM proveedores WHERE estatus = 'A' " . $andWHERE;

                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);

                $sql = "SELECT * FROM proveedores WHERE estatus = 'A' " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);

            } else {
                $sql = "SELECT * FROM proveedores WHERE estatus = 'A'";
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT * FROM proveedores WHERE estatus = 'A' limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));
            break;


       case "filtroClientes":
            /**
             * Procedimiento de busqueda de Clientes
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      levieraf@gmail.com
             *
             */

            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {

                // Filtro para los cliente
                $id_cliente = (isset($_POST["id_cliente"])) ? $_POST["id_cliente"] : "";
                $rif = (isset($_POST["ciruc"])) ? $_POST["ciruc"] : "";
                $cod_cliente = (!isset($_POST["cod_cliente"])) ? "" : $_POST["cod_cliente"];
                $nombre = (!isset($_POST["cliente"])) ? "" : $_POST["cliente"];

                $andWHERE = " and ";
                if ($rif != "") {
                    $andWHERE .= " upper(rif) like upper('%" . $rif . "%')";
                }

                if ($cod_cliente != "") {
                    $andWHERE .= " upper(cod_cliente) like upper('%" . $cod_cliente . "%')";
                }

                if ($nombre != "") {
                    if ($cod_cliente != "") {
                        $andWHERE .= " and ";
                    } else {
                        $andWHERE = " and ";
                    }
                    $andWHERE .= " upper(nombre) like upper('%" . $nombre . "%')";
                }

                if ($rif != "") {
                    if ($nombre != "" || $cod_cliente != "") {
                        $andWHERE .= " and ";
                    } else {
                        $andWHERE = " and ";
                    }
                    $andWHERE .= " upper(rif) like upper('%" . $rif . "%')";
                }

                if ($rif == "" && $cod_cliente == "" && $nombre == "") {
                    $andWHERE = "";
                }

                $sql = "SELECT * FROM clientes WHERE estado = 'A' " . $andWHERE;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);

                $sql = "SELECT * FROM clientes WHERE estado = 'A' " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);

            } else {
                $sql = "SELECT * FROM clientes WHERE estado = 'A'";
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT * FROM clientes WHERE estado = 'A' limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));
            break;
        case "DetalleCliente":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM clientes WHERE id_cliente = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : json_encode($campos);
            break;
        case "Detalleproveedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM proveedor WHERE id_proveedor = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : json_encode($campos);
            break;
        case "ValidarCodigoCliente":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM clientes WHERE cod_cliente = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "ValidarCodigoVendedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor WHERE cod_vendedor = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "ValidarNombreUsuario":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM usuarios WHERE usuario = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;
        case "ValidarCedulaUsuario":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM usuarios WHERE ced_usuario = '" . $_GET["v1"] . "'");
            echo (count($campos) == 0) ? "1" : "-1";
            break;

        case "Selectitem":
#$campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` AS i INNER JOIN `item_existencia_almacen` AS ie ON i.id_item = ie.id_item WHERE i.cod_item_forma` = '" . $_GET["v1"] . "' AND i.estatus = 'A' AND ie.cantidad>0");
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `cod_item_forma` = '" . $_GET["v1"] . "' and estatus = 'A' order by descripcion1 asc");
//SELECT * FROM `item` as i left join compra as c on c.id_proveedor=6 left join compra_detalle as cd on c.id_compra=cd.id_compra WHERE i.cod_item_forma = 1 and i.id_item)
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                $i=0;
                foreach ($campos as $key => $value) {
                   $campos[$i]["descripcion1"]= utf8_encode($value["descripcion1"]);
                   $i++;
                }
                echo json_encode($campos);
            }
            break;

        case "SelectrubroMercadeo":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `rubros_estudio_mercado` = '" . $_GET["v1"] . "' ");
            //$campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `rubros_estudio_mercado` ");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                $i=0;
                foreach ($campos as $key => $value) {
                   $campos[$i]["nombre_rubro"]= utf8_encode($value["nombre_rubro"]);
                   $i++;
                }
                echo json_encode($campos);
            }
            break;

        case "SelectproductoCotizacion":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `rubros_estudio_mercado` = '" . $_GET["v1"] . "' ");
            //$campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `rubros_estudio_mercado` ");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                $i=0;
                foreach ($campos as $key => $value) {
                   $campos[$i]["nombre_rubro"]= utf8_encode($value["nombre_rubro"]);
                   $i++;
                }
                echo json_encode($campos);
            }
        break;

        case "Selectitemporproveedor":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `cod_item_forma` = '" . $_GET["v1"] . "' and estatus = 'A' order by descripcion1 asc");
//SELECT * FROM `item` as i left join compra as c on c.id_proveedor=6 left join compra_detalle as cd on c.id_compra=cd.id_compra WHERE i.cod_item_forma = 1 and i.id_item)
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "DetalleSelectitem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `id_item` = '" . $_GET["v1"] . "'");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "DetalleProductoMercadeo":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `rubros_estudio_mercado` WHERE `id_rubro` = '" . $_GET["v1"] . "'");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        
        case "DetalleProductoCotizacion":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `id_item` = '" . $_GET["v1"] . "'");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
        break;

        case "CargarAlmacenesDisponiblesByIdItem":
        $codAlmacen= $_GET["codAlmacen"];
         $ubicacion= $_GET["ubicacion"];
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_existenciabyalmacen WHERE id_item = '" . $_GET["v1"] . "' and cantidad > 0 AND cod_alamcen = '" . $_GET["codAlmacen"] . "' AND id_ubicacion= '" . $_GET["ubicacion"] . "' order by cod_almacen");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
              case "CargarAlmacenes":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM almacen order by cod_almacen");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
              } else {
                echo json_encode($campos);
            }
            break;
        case "ExistenciaProductoAlmacenDefaultByIdItem":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT almaexi.* FROM vw_existenciabyalmacen almaexi JOIN parametros_generales pg ON pg.cod_almacen = almaexi.cod_almacen WHERE id_item = '" . $_GET["v1"] . "' and cantidad > 0");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;

        case "verificarExistenciaItemByAlmacen":
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_precomprometidos WHERE id_item = '" . $_GET["v2"] . "' and cod_almacen = '" . $_GET["v1"] . "' and id_ubicacion='". $_GET["ubicacion"]."'");
            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "precomprometeritem":

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT grupo.cantidad_rest, grupo.dias_rest, grupo.cod_grupo, departamentos.cod_departamento, item.referencia FROM
                    grupo INNER JOIN item ON grupo.cod_grupo = item.cod_grupO INNER JOIN departamentos ON grupo.id_rubro = departamentos.cod_departamentoWHERE item.id_item = '".$_GET["v1"]."' ");

            if((CASAEQUIPADA=="SI") && ($campos[0]["cod_departamento"]==2))
            {
                $cliente = $conn->ObtenerFilasBySqlSelect("SELECT clientes.id_cliente, clientes.rif FROM clientes WHERE id_cliente = '".$_GET["cliente"]."'");
                $cantidad = $campos[0]["cantidad_rest"];
                $dias = $campos[0]["dias_rest"];
                $grupo = $campos[0]["cod_grupo"];
                $fecha = date('Y-m-d');
                $nuevafecha = strtotime ( "-".$dias." day" , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
                $cliente =  $cliente[0][rif];

        //echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura join item on item.id_item=factura_detalle.id_item WHERE item.cod_grupo = '".$grupo."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between  '$nuevafecha' and '$fecha'";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT ifnull(count(codigo),0) as cantidad FROM mcbe  WHERE codigo = '".$campos[0]['referencia']."' and cedula = '$cliente' ");
                $cantidadComprada = $campos[0]["cantidad"];

               // echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura WHERE id_item = '".$_GET["v1"]."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between '$nuevafecha' and '$fecha' ";
               // exit;

                if($cantidadComprada>=$cantidad)
                {
                    echo "[{'id':'-98','observacion':'Este cliente a alcazado el limite de compra por persona.'}]";
                    exit;
                }
            }

            if(RESTRICCIONES=="SI")
            {

                $campos = $conn->ObtenerFilasBySqlSelect("SELECT grupo.cantidad_rest, grupo.dias_rest, grupo.cod_grupo FROM grupo INNER JOIN item ON grupo.cod_grupo = item.cod_grupo WHERE item.id_item = '".$_GET["v1"]."' ");
                $cantidad = $campos[0]["cantidad_rest"];
                $dias = $campos[0]["dias_rest"];
		$grupo = $campos[0]["cod_grupo"];
                $fecha = date('Y-m-d');
                $nuevafecha = strtotime ( "-".$dias." day" , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

		//echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura join item on item.id_item=factura_detalle.id_item WHERE item.cod_grupo = '".$grupo."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between  '$nuevafecha' and '$fecha'";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura join item on item.id_item=factura_detalle.id_item WHERE item.cod_grupo = '".$grupo."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between  '$nuevafecha' and '$fecha'");
                $cantidadComprada = $campos[0]["cantidad"];

               // echo "SELECT sum(_item_cantidad) as cantidad FROM factura_detalle INNER JOIN factura ON factura_detalle.id_factura = factura.id_factura WHERE id_item = '".$_GET["v1"]."' and id_cliente = '".$_GET["cliente"]."' and fechaFactura between '$nuevafecha' and '$fecha' ";
               // exit;

                if($cantidadComprada>=$cantidad)
                {
                    echo "[{'id':'-98','observacion':'Este cliente a alcazado el limite de compra por persona.'}]";
                    exit;
                }
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vw_item_precomprometidos
            WHERE id_item = '" . $_GET["v1"] . "' and cod_almacen = '" . $_GET["codalmacen"] . "' and id_ubicacion='". $_GET["ubicacion"]."'");

            $cantidadExistenteOLD = $campos[0]["cantidad"];
            $cantidadPedidad = $_GET["cpedido"];

            $cantidadExistenteNEW = $cantidadExistenteOLD - $cantidadPedidad;
            if ($cantidadExistenteNEW < 0) {
                echo "[{'id':'-99','observacion':'La cantidad Pedida es mayor a la existente.'}]";
                exit;
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE id_item = " . $_GET["v1"] . " and cod_item_forma = 1"); // 1: item producto
            if (count($campos) > 0) {
//if(strcmp($_GET["tipo_transaccion"],"presupuesto")){
#echo $_GET["tipo_transaccion"];exit;
                $sql = "INSERT INTO item_precompromiso (
                        `id_item_precomiso`, `cod_item_precompromiso`, `id_item`, `cantidad`, `usuario_creacion`,
                        `fecha_creacion`, `idSessionActualphp`, `almacen`,`ubicacion`)
                        VALUES (
                        NULL , '" . $_GET["codprecompromiso"] . "','" . $_GET["v1"] . "', '" . $_GET["cpedido"] . "', '" . $login->getUsuario() . "',
                        CURRENT_TIMESTAMP , '" . $login->getIdSessionActual() . "','" . $_GET["codalmacen"] . "','" . $_GET["ubicacion"] . "');";
                $conn->Execute2($sql);
                echo "[{'id':'1','observacion':''}]";
//}
            } else {
                echo "[{'id':'-1','observacion':''}]";
            }
            break;
        case 'seleccionarPedidoPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM pedido_detalle pd inner join item i on i.id_item = pd.id_item WHERE id_pedido = {$_GET["id_pedido"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        case 'seleccionarNotaEntregaPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM nota_entrega_detalle WHERE id_nota_entrega = {$_GET["id_nota"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        case 'seleccionarCotizacionPendiente':
            header("Content-Type: text/plain");
            $sql = "SELECT * FROM cotizacion_presupuesto_detalle WHERE id_cotizacion = {$_GET["id_cotizacion"]};";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);
            echo json_encode($campos);
            break;
        /* case 'generarCuotas':

          header("Content-Type: text/plain");

          $correlativos = new Correlativos();
          $cuotas = $_POST["cuota"];
          $cuota_precios = $_POST["precio"];
          $cuota_anios = $_POST["anio"];
          $cuota_meses = $_POST["mes"];

          $conn->Execute2("UPDATE `cuota` SET `estatus` = 1 WHERE `id` = {$_POST["id_cuota"]};");

          foreach ($cuotas as $key => $cuota_descripcion) {//Pendiente de quitar el 1 para `cuota_cuota_id` y eliminar dicho campo de la tabla

          $codigo = $correlativos->getUltimoCorrelativo("cod_producto", 1, "si", "C");
          $conn->ExecuteTrans("UPDATE correlativos SET contador = contador + 1 WHERE campo = 'cod_producto';");

          $cuota = $conn->ObtenerFilasBySqlSelect("SELECT descripcion FROM cuota WHERE id = {$_POST["id_cuota"]};");

          //$cuota_descripcion = $cuota[0]["descripcion"] . " " . $cuota_anios[$key] . "-" . ($cuota_meses[$key] < 10 ? "0" . $cuota_meses[$key] : $cuota_meses[$key]);

          $instruccion = "INSERT INTO `item`(
          `cod_item`, `costo_actual`, `descripcion1`,
          `precio1`, `utilidad1`, `coniva1`, `precio2`, `utilidad2`,
          `coniva2`, `precio3`, `utilidad3`, `coniva3`, `existencia_min`,
          `existencia_max`, `monto_exento`, `iva`,
          `estatus`,`usuario_creacion`, `fecha_creacion`, `cod_item_forma`, unidad_empaque,
          costo_promedio, costo_anterior)
          VALUES(
          '{$codigo}', '{$cuota_precios[$key]}', '{$cuota_descripcion}',
          '{$cuota_precios[$key]}', '0', '{$cuota_precios[$key]}', '{$cuota_precios[$key]}',
          '0', '{$cuota_precios[$key]}', '{$cuota_precios[$key]}', '0',
          '{$cuota_precios[$key]}', '0',  '0', '0', '0',
          'I', '" . $login->getUsuario() . "', CURRENT_TIMESTAMP, 4, NULL,
          '{$cuota_precios[$key]}', '{$cuota_precios[$key]}');";

          $conn->ExecuteTrans($instruccion);

          $id_item = $conn->getInsertID();

          $instruccion = "INSERT INTO `cuota_mes` (`id`, `cuota_id`, `cuota_cuota_id`, `id_item`, `precio`, `mes`, `anio`)
          VALUES (NULL, {$_POST["id_cuota"]}, 1, {$id_item}, {$cuota_precios[$key]}, $cuota_meses[$key], {$cuota_anios[$key]});";
          $campos = $conn->Execute2($instruccion);
          //echo (count($campos) == 0) ? "1" : "-1";
          }
          break; */
        case 'ponerCuotaPagada':
            header("Content-Type: text/plain");
            /* Aqui debo recibir un array con todas las cuotas seleeccionadas en la pantalla de facturacion */
            $cuotas = json_decode($_POST["cuotas"]);
            $cliente = json_decode($_POST["cliente"]);
            foreach ($cuotas as $cuota) {
#$conn->Execute2("UPDATE `cuota_cliente` SET estatus = 1 WHERE id = {$cuota};");
                $seleccionadas .= $cuota . ",";
            }
            $seleccionadas = trim($seleccionadas, ",");
//$campos = $conn->ObtenerFilasBySqlSelect("SELECT cc.id, cc.id_cuota_generada, cc.id_cliente, cm.cuota_id, cm.precio AS precio, cm.mes AS mes, cm.anio AS anio, c.descripcion FROM cuota_cliente AS cc INNER JOIN cuota_mes AS cm ON cc.id_cuota_generada = cm.id INNER JOIN cuota AS c ON cm.cuota_id = c.id WHERE cc.id_cliente = {$cliente} AND cc.estatus = 0 AND cc.id IN ({$seleccionadas});");
            $campos = $conn->ObtenerFilasBySqlSelect("
                    SELECT i.cod_item, cc.id, cc.id_cuota_generada, cc.id_cliente, cm.cuota_id, cm.id_item, cm.precio AS precio, cm.mes AS mes, cm.anio AS anio, c.descripcion
                    FROM cuota_cliente AS cc
                    INNER JOIN cuota_mes AS cm ON cc.id_cuota_generada = cm.id
                    INNER JOIN cuota AS c ON cm.cuota_id = c.id
                    INNER JOIN item AS i ON i.id_item = cm.id_item
                    WHERE cc.id_cliente = {$cliente} AND cc.estatus = 0 AND cc.id IN ({$seleccionadas});");
            echo json_encode($campos);
            break;
        /* case "asignarCuotas":
          $clientes_existentes = $conn->ObtenerFilasBySqlSelect("SELECT id_cliente, fdeuda FROM clientes WHERE cod_tipo_cliente = 1 --Contadores;");
          $datacuota = $conn->ObtenerFilasBySqlSelect("SELECT id, mes, anio FROM cuota_mes WHERE cuota_id = {$_GET["id_cuota"]} AND estado = 0 -- No asignada");

          $cant = count($datacuota);

          foreach ($clientes_existentes as $cli) {
          $fdeuda = explode("-", $cli["fdeuda"]);
          if ($cli["fdeuda"] != "0000-00-00") {
          $asignar = false;
          foreach ($datacuota as $cuota) {
          if ($fdeuda[0] == $cuota["anio"] && $fdeuda[1] == $cuota["mes"]) {
          $asignar = true;
          }
          if ($asignar) {
          $conn->Execute2("INSERT INTO `cuota_cliente` (`id_cuota_generada`, `id_cliente`) VALUES ({$cuota["id"]}, {$cli["id_cliente"]});");
          }
          }
          }
          $conn->ExecuteTrans("UPDATE cuota_mes SET estado = 1 WHERE cuota_id = {$_GET["id_cuota"]};");
          }
          //$cuotas = $conn->Execute2("UPDATE `cuota` SET `estatus` = 2 WHERE `id` = {$_GET["id_cuota"]};");
          echo json_encode(array(
          "success" => true,
          "total" => $cant, //count($cuotas),
          "data" => $cuotas
          ));
          break; */
        case 'tipoFacturacion':
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT tipo_facuracion FROM parametros_generales");
            echo json_encode($campos);
            break;
        case "delete_precomprometeritem":
            $sql = "delete from item_precompromiso
                    WHERE cod_item_precompromiso = '" . $_GET["codprecompromiso"] . "'  and
                    idSessionActualphp = '" . $login->getIdSessionActual() . "'      and
                    usuario_creacion = '" . $login->getUsuario() . "' and id_item = '" . $_GET["v1"] . "'";
            $conn->Execute2($sql);
            break;
        case "det_edocuentacxp":
            $data_parametros = $conn->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
            foreach ($data_parametros as $key => $lista) {
                $valueSELECT[] = $lista["cod_empresa"];
                $outputidfiscalSELECT[] = $lista["moneda"];
            }
            $campos = $conn->ObtenerFilasBySqlSelect("
                SELECT *, vw_cxp.numero AS num_cdet, cxp_edocuenta.vencimiento_persona_contacto, cxp_edocuenta.vencimiento_telefono, cxp_edocuenta.vencimiento_descripcion
                FROM vw_cxp
                INNER JOIN cxp_edocuenta ON cxp_edocuenta.cod_edocuenta = vw_cxp.cod_edocuenta
                WHERE vw_cxp.cod_edocuenta = " . $_GET["cod_edocuenta"]);
            if (count($campos) == 0) {
                exit;
            }
            echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border: 1px solid #ededed; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px; margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Documento</th>
                        <th style="border-bottom: 1px solid #949494;">N&uacute;mero</th>
                        <th style="border-bottom: 1px solid #949494;width:120px;">Fecha Emisi&oacute;n</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">Descripci&oacute;n</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Abonos/Pagos</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Deuda</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Opt</th>
                    </thead>
                    <tbody>';

            $acuDebitos = 0;
            $acuCreditos = 0;
            foreach ($campos as $key => $item) {
                if ($item["estado"] <> '0') {
                    echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">' . $item["cod_edocuenta_detalle"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:110px;">' . $item["documento_cdet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;">' . $item["num_cdet"] . '</td>
                            <td align="center" style="border-bottom: 1px solid #949494;width:120px;">' . $item["fecha_emision_edodet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:300px;">' . $item["descripcion"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;width:110px;">' . number_format($item['debito'], 2, ",", ".") . ' ' . $lista["moneda"] . ' </td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['credito'], 2, ",", ".") . ' ' . $lista["moneda"] . ' </td>
                            <td align="right" style="border-bottom: 1px solid #949494;">';

                    if ($key > 0) {
                        echo "<input type='hidden' id='detalle_asiento' name='detalle_asiento' value='" . $item["cod_edocuenta_detalle"] . "'>";
                        echo '<img onclick="javascript: guardarr(' . $item["cod_edocuenta_detalle"] . ')" style="cursor:pointer;" title="Eliminar Asiento" src="../../libs/imagenes/cancel.gif">';
                    }

                    echo '</td>
        </tr>';
                }
                $acuDebitos += $item['debito'];
                $acuCreditos += $item['credito'];
            }
            echo '
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;width:300px;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Total Pagos,Abonos/Deudas:</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuCreditos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Saldo Pendiente:</b></td>
                            <td colspan="2"align="right" style="border-bottom: 1px solid #949494;"><b style="color:red;">' . number_format($acuCreditos - $acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;">

                            </td>
                        </tr>
    ';

            if ($campos[0]["marca"] != "X") {
                echo '

                        <tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=89&opt_seccion=88&opt_subseccion=pagoabonoCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Agregar Pago/Abono</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
			<td colspan="7" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=89&opt_seccion=88&opt_subseccion=facturasCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Facturas/Notas de Credito</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
        ';
            }
            if ($campos[0]["marca"] == "X") {
                echo '

                        <tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;">
                        </td>
			<td colspan="7" style="text-align: left; border-bottom: 1px solid #949494;">

                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=85&opt_seccion=88&opt_subseccion=facturasCXP&cod=' . $_GET["codigo_proveedor"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/list.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Facturas/Notas de Credito</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
        ';
            }


            echo
            '</tbody>
    </table>
    </div>
    </td>
    </tr>';




            break;

        case "det_edocuenta":
            $data_parametros = $conn->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
            foreach ($data_parametros as $key => $lista) {
                $valueSELECT[] = $lista["cod_empresa"];
                $outputidfiscalSELECT[] = $lista["moneda"];
            }
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *
,vw_cxc.numero as num_cdet
,cxc_edocuenta.vencimiento_persona_contacto,
cxc_edocuenta.vencimiento_telefono,
cxc_edocuenta.vencimiento_descripcion from vw_cxc
 inner join cxc_edocuenta on cxc_edocuenta.cod_edocuenta = vw_cxc.cod_edocuenta
WHERE vw_cxc.cod_edocuenta = " . $_GET["cod_edocuenta"]);
            if (count($campos) == 0) {
                exit;
            }
            echo '<tr class="edocuenta_detalle">
          <td colspan="8">
            <div  style=" background-color:#fdfdfd; border: 1px solid #ededed;-moz-border-radius: 7px;padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px;  font-size: 13px; ">
                <table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">ID</th>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Documento</th>
                        <th style="border-bottom: 1px solid #949494;">Numero</th>
                        <th style="border-bottom: 1px solid #949494;width:120px;">Fecha EmisiÃ³n</th>
                        <th align="justify" style="border-bottom: 1px solid #949494;width:300px;">DescripciÃ³n</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Deuda</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Pago/Abono</th>
                        <th align="right" style="border-bottom: 1px solid #949494;width:110px;">Opt</th>
                    </thead>
                    <tbody>';


            $acuDebitos = 0;
            $acuCreditos = 0;
            foreach ($campos as $key => $item) {
                echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">' . $item["cod_edocuenta_detalle"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:110px;">' . $item["documento_cdet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;">' . $item["num_cdet"] . '</td>
                            <td align="center" style="border-bottom: 1px solid #949494;width:120px;">' . $item["fecha_emision_edodet"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:300px;">' . $item["descripcion"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['debito'], 2, ",", ".") . ' ' . $lista["moneda"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">' . number_format($item['credito'], 2, ",", ".") . ' ' . $lista["moneda"] . '</td>
                            <td align="right" style="border-bottom: 1px solid #949494;">';
//if($item['debito']=="0.00"){
                if ($key > 0) {
                    echo '<img class="eliminarAsiento"  style="cursor:pointer;" title="Eliminar Asiento" src="../../libs/imagenes/cancel.gif">';
                    echo "<input type='hidden' id='detalle_asiento' name='detalle_asiento' value='" . $item["cod_edocuenta_detalle"] . "'>";
                }

                echo '</td>
        </tr>';

                $acuDebitos += $item['debito'];
                $acuCreditos += $item['credito'];
            }
            echo '
                        <tr>
                            <td colspan="8" align="right" style="border-bottom: 1px solid #949494;width:300px;"></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Total Deudas,Pagos/Abonos:</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuDebitos, 2, ",", ".") . ' ' . $lista["moneda"] . '</b></td>
                            <td align="right" style="border-bottom: 1px solid #949494;"><b>' . number_format($acuCreditos, 2, ",", ".") . '  ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="border-bottom: 1px solid #949494;width:300px;"><b>Saldo Pendiente:</b></td>
                            <td colspan="2"align="right" style="border-bottom: 1px solid #949494;"><b style="color:red;">' . number_format($acuDebitos - $acuCreditos, 2, ",", ".") . '  ' . $lista["moneda"] . '</b></td>
                        </tr>
                        <tr>
                            <td colspan="7" align="right" style="border-bottom: 1px solid #949494;width:300px;">

                            </td>
                        </tr>
    ';


            if ($campos[0]["marca"] != "X") {
                echo '<tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;width:110px;">
                            <table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=5&opt_seccion=59&opt_subseccion=pagooabono&cod=' . $_GET["codigo_cliente"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Agregar Pago/Abono</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                            <br>
                            <img src="../../libs/imagenes/ico_user.gif"> Persona de Contacto: ' . $campos[0]["vencimiento_persona_contacto"] . '<br>
                            <img src="../../libs/imagenes/ico_cel.gif"> Telefono de Contacto: ' . $campos[0]["vencimiento_telefono"] . '<br>
                            <img src="../../libs/imagenes/ico_view.gif"> ObservaciÃ³n: ' . $campos[0]["vencimiento_descripcion"] . '<br>
                            <img src="../../libs/imagenes/ew_calendar.gif"> Fecha de Vencimiento: ' . $campos[0]["vencimiento_fecha"] . '<br>

                        </td>
                        </tr>
        ';
            }
            echo '</tbody></table></div></td></tr>';
            break;
        case "det_items":
            if ($_GET["id_tipo_movimiento_almacen"] == '3' || $_GET["id_tipo_movimiento_almacen"] == '1') {
                $operacion = "Entrada";

                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad AS cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        FROM kardex_almacen_detalle AS kad JOIN kardex_almacen AS k ON kad.id_transaccion=k.id_transaccion LEFT JOIN almacen AS alm ON kad.id_almacen_entrada=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id LEFT JOIN item AS ite ON kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            } else if ($_GET["id_tipo_movimiento_almacen"] == '4' || $_GET["id_tipo_movimiento_almacen"] == '2' || $_GET["id_tipo_movimiento_almacen"] == '8') {
                $operacion = "Salida";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item, ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            } else if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                $operacion = "Traslado";
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad left join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_entrada=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
                $campos1 = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item,ubi.descripcion as ubicacion, alm.descripcion as almacen
        from kardex_almacen_detalle as kad join kardex_almacen as k on kad.id_transaccion=k.id_transaccion left join almacen as alm on kad.id_almacen_salida=alm.cod_almacen LEFT JOIN ubicacion AS ubi ON kad.id_ubi_salida=ubi.id left join item as ite on kad.id_item=ite.id_item WHERE kad.id_transaccion =" . $_GET["id_transaccion"]);
            }
//$campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
//from kardex_almacen_detalle as kad left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE id_transaccion = ".$_GET["id_transaccion"]);
//echo $campos;
            if (count($campos) == 0) {
                exit;
            }

            if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                echo '<tr class="detalle_items">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="width:110px; font-weight: bold; text-align: center;">ID</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n Entrada</th>
                        <th style="width:150px; font-weight: bold;">Ubicaci&oacute;n Entrada</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n Salida</th>
                         <th style="width:150px; font-weight: bold;">Ubicaci&oacute;n Salida</th>
                        <th style="width:300px; font-weight: bold;">Item</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                    </thead>
                    <tbody>';
            } else {
                echo '<tr class="detalle_items">
          <td colspan="8">
            <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px; padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                <table >
                    <thead>
                        <th style="width:110px; font-weight: bold; text-align: center;">ID</th>
                        <th style="width:150px; font-weight: bold;">Almac&eacute;n ' . $operacion . '</th>
                        <th style="width:150px; font-weight: bold;">Ubicaci&oacute;n' . $operacion . '</th>
                        <th style="width:300px; font-weight: bold;">Item</th>
                        <th style="width:110px; font-weight: bold; text-align: center;">Cantidad</th>
                    </thead>
                    <tbody>';
            }

            foreach ($campos as $key => $item) {
                if ($_GET["id_tipo_movimiento_almacen"] == '5') {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["id_transaccion_detalle"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["almacen"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["ubicacion"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["almacen"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["ubicacion"] . '</td>
                            <td style="width:300px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                        </tr>';
                } else {
                    echo '
                        <tr>
                            <td style="width:110px; text-align: right; padding-right:10px;">' . $item["id_transaccion_detalle"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["almacen"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["ubicacion"] . '</td>
                            <td style="width:300px; padding-left:10px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>
                        </tr>';
                }
            }

            if ($campos[0]["estado"] == "Pendiente") {
                echo '<tr>
                            <td colspan="6" style="text-align: left; border-bottom: 1px solid #949494;width:110px;">
<br/><!--form>
<label for="fecha">Fecha</label><input type="text" name="fecha">
<label for="control">Nro. Control</label><input type="text" name="control">
<label for="factura">Nro. Factura</label><input type="text" name="factura"-->
<table style="cursor: pointer;" align="right" class="btn_bg" onClick="javascript:window.location=\'?opt_menu=3&opt_seccion=109&opt_subseccion=add&cod=' . $_GET["id_transaccion"] . '&cod2=' . $_GET["cod_edocuenta"] . '\'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/factu.png" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Realizar Entrada</td>
                                    <td style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                            <!--/form-->
                        </td>
                        </tr>';
            }
            echo
            '</tbody>
    </table>
    </div>
    </td>
    </tr>';

            break;

            case "det_mercadeo":

                //$campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
                //from kardex_almacen_detalle as kad left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE id_transaccion = ".$_GET["id_transaccion"]);
                //echo $campos;
            //$sql=

            $bdCentral = "selectrapyme_central";
            $camposPrueba = $conn->ObtenerFilasBySqlSelect("SELECT imd.id_detalle_producto AS ID,
                et.nombre_establecimiento AS establecimiento,
                rem.nombre_rubro AS nombre,
                imd.precio AS precio
                FROM $bdCentral.investigacion_mercado_detalle imd
                INNER JOIN $bdCentral.investigacion_mercado im ON im.id_estudio = imd.id_estudio
                INNER JOIN $bdCentral.rubros_estudio_mercado rem ON rem.id_rubro = imd.id_producto
                INNER JOIN $bdCentral.establecimientos et ON et.id_establecimiento = imd.id_establecimiento
                WHERE imd.id_estudio =" .$_GET["id_estudio"]);

            //print_r($camposPrueba);

            if (count($camposPrueba) > 0) {
                // echo "hola";
                // exit();

                echo '<tr class="detalle_items">
                <td colspan="8">
                <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                    <table >
                        <thead>
                            <th style="width:110px; font-weight: bold; text-align: center;">ID</th>
                            <th style="width:150px; font-weight: bold;">Establecimiento</th>
                            <th style="width:150px; font-weight: bold;">Rubro</th>
                            <th style="width:150px; font-weight: bold;">Precio</th>
                        </thead>
                        <tbody>';

                foreach ($camposPrueba as $key => $item) {

                    //echo "hola";
                    echo '
                        <tr>
                            <td style="width:110px; text-align: center; padding-right:10px;">' . $item["ID"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["establecimiento"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["nombre"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["precio"] . '</td>
                            <!--<td style="width:150px;">' . $campos1[0]["almacen"] . '</td>
                            <td style="width:150px;">' . $campos1[0]["ubicacion"] . '</td>
                            <td style="width:300px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: right; padding-right:10px;">' . $item['cantidad_item'] . '</td>-->
                        </tr>';
                }
            }else{
                echo '<tr class="detalle_items">
                        <td colspan="8">
                        <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                            <table >
                                <thead>
                                    <!--<th style="width:110px; font-weight: bold; text-align: center;">ID</th>-->
                                    <!--<th style="width:150px; font-weight: bold;">Rubro</th>-->
                                    <th style="width:150px; font-weight: bold;">No tiene Rubros asociados</th>
                                </thead>
                                <tbody>';
            }

            echo
                '</tbody>
                </table>
                </div>
                </td>
                </tr>';

        break;

        case "det_cotizacion":

            $bdCentral = "selectrapyme_central";
            $bdSelectrapyme = "selectrapyme";
            $camposPrueba = $conn->ObtenerFilasBySqlSelect("SELECT cmd.id_detalle_producto AS ID,
                i.descripcion1 AS nombre,
                i.codigo_barras AS barras,
                cmd._precio_sugerido AS precio_sugerido,
                cmd.costo_sin_iva AS precio,
                cmd._ivaproduct AS ivaproduct,
                cmd._margen_ganancia AS margen_ganancia,
                cmd._pvp AS pvp,
                cme.estatus_name AS estatus,
                cme.id_estatus AS id_estatus,
                cm.cerrado AS cerrado
                FROM $bdCentral.cotizacion_mercado_detalle cmd
                INNER JOIN $bdCentral.cotizacion_mercado cm ON cm.id_estudio = cmd.id_estudio
                INNER JOIN $bdSelectrapyme.item i ON i.id_item  = cmd.id_producto
                INNER JOIN $bdCentral.cotizacion_mercado_estatus cme ON cme.id_estatus = cmd._estatus_producto
                WHERE cmd.id_estudio =" .$_GET["id_estudio"]);

            if (count($camposPrueba) > 0) {
                // echo "hola";
                // exit();

                echo '<tr class="detalle_items">
                <a id="mo" href="#">mostrar</a> | <a id="ocu" href="#">ocultar</a>
                <td colspan="8">
                <div class="respuesta" style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                    <table >
                        <thead>
                            <th style="width:110px; font-weight: bold; text-align: center;">Código de Barras</th>
                            <th style="width:150px; font-weight: bold;">Producto</th>
                            <th style="width:150px; font-weight: bold;">Costo Total sin IVA</th>
                            <th style="width:150px; font-weight: bold;">IVA</th>
                            <th style="width:150px; font-weight: bold;">Precio Sugerido</th>
                            <th style="width:150px; font-weight: bold;">Margen de Ganancia</th>
                            <th style="width:150px; font-weight: bold;">P.V.P</th>
                            <th style="width:150px; font-weight: bold;">Estatus del Producto</th>
                        </thead>
                        <tbody>
                ';
                
                $i=0;
                foreach ($camposPrueba as $key => $item) {

                    echo '
                        <tr>
                            <td style="width:110px; text-align: center; ">' . $item["barras"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["nombre"] . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["precio"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["precio"], 2, ',', '') . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["ivaproduct"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["ivaproduct"], 2, ',', '') . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["precio_sugerido"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["precio_sugerido"], 2, ',', '') . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["margen_ganancia"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["margen_ganancia"], 2, ',', '') . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["pvp"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["pvp"], 2, ',', '') . '</td>';

                    echo '<td style="width:150px; padding-left:10px;">' . $item["estatus"] . '</td>';

                    $i++;
                }
                echo "<th style='vertical-align:middle; height: 5px; width:100px; text-align: center;'><p ><a href='?opt_menu=".$_GET['id_menu']."&amp;opt_seccion=".$_GET['id_seccion']."'><b>Minimizar</b></a></p></th>";
                echo '</tr>';
            }else{
                echo '<tr class="detalle_items">
                        <td colspan="8">
                        <div style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                            <table >
                                <thead>
                                    
                                    <th style="width:150px; font-weight: bold;">No tiene productos asociados</th>';

                echo "<th style='vertical-align:middle; height: 30px; width:1050px;'><p align='right'><a href='?opt_menu=".$_GET['id_menu']."&amp;opt_seccion=".$_GET['id_seccion']."'><b>Minimizar</b></a></p></th>";

                echo '
                                </thead>
                                <tbody>';
            }

            echo
                '</tbody>
                </table>
                </div>
                </td>
                </tr>';

        break;



        case "det_cotizacion_agregar":

                //$campos = $conn->ObtenerFilasBySqlSelect("SELECT *,kad.cantidad as cantidad_item
                //from kardex_almacen_detalle as kad left join almacen as alm on kad.id_almacen_entrada=alm.cod_almacen left join item as ite on kad.id_item=ite.id_item WHERE id_transaccion = ".$_GET["id_transaccion"]);
                //echo $campos;
            //$sql=

            $bdCentral = "selectrapyme_central";
            $bdSelectrapyme = "selectrapyme";
            $camposPrueba = $conn->ObtenerFilasBySqlSelect("SELECT cmd.id_detalle_producto AS ID,
                i.descripcion1 AS nombre,
                i.codigo_barras AS barras,
                cmd._precio_sugerido AS precio_sugerido,
                cmd.costo_sin_iva AS precio,
                cmd._ivaproduct AS ivaproduct,
                cmd._margen_ganancia AS margen_ganancia,
                cmd._pvp AS pvp,
                cme.estatus_name AS estatus,
                cme.id_estatus AS id_estatus,
                cm.cerrado AS cerrado
                FROM $bdCentral.cotizacion_mercado_detalle cmd
                INNER JOIN $bdCentral.cotizacion_mercado cm ON cm.id_estudio = cmd.id_estudio
                INNER JOIN $bdSelectrapyme.item i ON i.id_item  = cmd.id_producto
                INNER JOIN $bdCentral.cotizacion_mercado_estatus cme ON cme.id_estatus = cmd._estatus_producto
                WHERE cmd.id_estudio =" .$_GET["id_estudio"]);

            //print_r($camposPrueba);

            if (count($camposPrueba) > 0) {

                echo '<tr class="detalle_items">
                <a id="mo" href="#">mostrar</a> | <a id="ocu" href="#">ocultar</a>
                <td colspan="8">
                <div class="respuesta" style=" background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;">
                    <table >
                        <thead>
                            <th style="width:110px; font-weight: bold; text-align: center;">Código de Barras</th>
                            <th style="width:150px; font-weight: bold;">Producto</th>
                            <th style="width:150px; font-weight: bold;">Costo Total sin IVA</th>
                            <th style="width:150px; font-weight: bold;">IVA</th>
                            <th style="width:150px; font-weight: bold;">Precio Sugerido</th>
                            <th style="width:150px; font-weight: bold;">Margen de Ganancia</th>
                            <th style="width:150px; font-weight: bold;">P.V.P</th>
                            <th style="width:150px; font-weight: bold;">Estatus del Producto</th>
                        </thead>
                        <tbody>
                        ';

                $aprobado = 0;
                $rechazado = 0;
                $estudio = 0;
                $bandera1 = 0;
                $bandera2 = 0;
                $bandera3 = 0;
                $bandera4 = 4;
                foreach ($camposPrueba as $key => $item) {

                    if ($item["id_estatus"] == '1') {
                        $aprobado++;
                        $bandera1 = 1;
                    }
                    if ($item["id_estatus"] == '2') {
                        $rechazado++;
                        $bandera2 = 2;
                    }
                    if ($item["id_estatus"] == '3') {
                        $estudio++;
                        $bandera3 = 3;
                    }

                    echo '
                        <tr>
                            <td style="width:110px; text-align: center; ">' . $item["barras"] . '</td>
                            <td style="width:150px; padding-left:10px;">' . $item["nombre"] . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["precio"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["precio"], 2, ',', '') . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["ivaproduct"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["ivaproduct"], 2, ',', '') . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["precio_sugerido"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["precio_sugerido"], 2, ',', '') . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["margen_ganancia"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["margen_ganancia"], 2, ',', '') . '</td>
                            <!--<td style="width:150px; padding-left:10px;">' . $item["pvp"] . '</td>-->
                            <td style="width:150px; padding-left:10px;">' . number_format($item["pvp"], 2, ',', '') . '</td>';

                    echo '<td style="width:150px; padding-left:10px;">' . $item["estatus"] . '</td>';
                    
                    if ($item["id_estatus"] == '3' && $item["cerrado"] == '1') {
                        echo '<td style="width:30px; padding-left:10px;">
                        <img class="editar" id='.$item['ID'].' width="17" height="17" onclick="editarItem(this)" title="Editar" src="../../../includes/imagenes/edit.gif" />
                        </td>';
                    }
                }

                if ($bandera1 != $_GET['estatus_cotizacion']) {
                    if($bandera2 != $_GET['estatus_cotizacion']) {
                        if($bandera4 != $_GET['estatus_cotizacion']) {
                

                            if ($aprobado !=0 && $estudio == 0) {
                                if (count($camposPrueba) == $aprobado) {
                                    // echo "Cotización Aprobada<br>";

                                    $resultado=$conn->Execute2("update $bdCentral.cotizacion_mercado set estatus_cotizacion = '1' where id_estudio=".$_GET["id_estudio"]);

                                    echo "{literal}
                                        <script language='JavaScript' type='text/JavaScript'>
                                            function reFresh() {
                                                location.reload(true)
                                            }
                                            window.setInterval('reFresh()',1000);
                                        </script>
                                    {/literal}";
                                }
                            }else if ($rechazado != 0 && $estudio == 0) {
                                if (count($camposPrueba) == $rechazado) {
                                    // echo "Cotización Rechazada -> ID ".$_GET["id_estudio"]."<br>";

                                    $resultado=$conn->Execute2("update $bdCentral.cotizacion_mercado set estatus_cotizacion = '2' where id_estudio=".$_GET["id_estudio"]);

                                    echo "{literal}
                                        <script language='JavaScript' type='text/JavaScript'>
                                            function reFresh() {
                                                location.reload(true)
                                            }
                                            window.setInterval('reFresh()',1000);
                                        </script>
                                    {/literal}";
                                }
                            }

                            $suma = $aprobado + $rechazado;
                            if (count($camposPrueba) == $suma) {
                                
                                if ($bandera1 == 1 && $bandera2 == 2) {
                                
                                    // echo "Cotización Aprobada Parcialmente<br>";

                                    $resultado=$conn->Execute2("update $bdCentral.cotizacion_mercado set estatus_cotizacion = '4' where id_estudio=".$_GET["id_estudio"]);

                                    echo "{literal}
                                        <script language='JavaScript' type='text/JavaScript'>
                                            function reFresh() {
                                                location.reload(true)
                                            }
                                            window.setInterval('reFresh()',1000);
                                        </script>
                                    {/literal}";
                                }
                            }else{
                                // echo "Cotizacion aún en Estudio...!<br>";
                            }

                            // echo "{literal}
                            //     <script language='JavaScript' type='text/JavaScript'>
                            //         function reFresh() {
                            //             location.reload(true)

                            //         }

                            //         window.setInterval('reFresh()',10000);
                            //     </script>
                            // {/literal}";
                        }
                    }
                }

                // echo "Cantidad de Aprobados: ".$aprobado." ".$bandera1."<br>";
                // echo "Cantidad de Rechazados: ".$rechazado." ".$bandera2."<br>";
                // echo "Cantidad en Estudio: ".$estudio." ".$bandera3."<br>";
                // echo "ID Estudio: ".$_GET['estatus_cotizacion']."<br>";
                // echo "Cantidad de Rubros: ".count($camposPrueba);

                if ($item["cerrado"] == '0') {
                    echo '<th style="width:30px; padding-left:10px;">
                        <img class="cerrarCotizacion" id="'.$_GET['id_estudio'].'" width="17" height="17" onclick="cerrarCotizacion(this)" title="Cerrar Cotización" src="../../../includes/imagenes/ico_cancel.gif" />
                    </th>';
                }else{
                    echo '<th style="width:30px; padding-left:10px;">
                        <img class="cerrarCotizacion" title="Cotización Cerrada" src="../../../includes/imagenes/ico_ok.gif" />
                    </th>';
                }

                echo "<th style='vertical-align:middle; height: 5px; width:100px; text-align: center;'><p ><a href='?opt_menu=".$_GET['id_menu']."&amp;opt_seccion=".$_GET['id_seccion']."'><b>Minimizar</b></a></p></th>";
                echo '</tr>';
            }else{
                echo "<tr class='detalle_items'>";
                        echo "<td colspan='8'>";
                        echo "<div style='background-color:#f3ed8b; border-radius: 7px; padding:1px; margin-top:0.3%; margin-bottom: 10px;padding-bottom: 7px;margin-left: 10px; font-size: 13px;' >";
                        //echo "<p align='right' style='vertical-align:middle;'><a id='ocu' href='?opt_menu=".$_GET['id_menu']."&amp;opt_seccion=".$_GET['id_seccion']."'>ocultar</a></p>";
                            echo "<table >";
                                echo "<thead>";
                                    echo "<!--<th style='width:110px; font-weight: bold; text-align: center;'>ID</th>-->
                                    <!--<th style='width:150px; font-weight: bold;'>Rubro</th>-->";
                                    echo "<th style='width:150px; font-weight: bold;'>No tiene productos asociados</th>";
                                    echo "<th style='cursor:pointer; width:30px; text-align:center'>";
                                        
                                        $a = "?opt_menu={".$_GET['id_menu']."}&amp;opt_seccion={".$_GET['id_seccion']."}&amp;opt_subseccion=edit&amp;cod={".$_GET['id_estudio']."}";
                                        //echo $a;
                                        //echo "<a href='../../reportes/entrada_cotizacion.php?id_estudio=".$_GET['id_estudio']."'>enlace</a>";
                                        echo "<th style='color:blue; text-decoration:underline;color:blue; vertical-align:middle; height: 30px; width:300px;'><p align='left'><a href='?opt_menu=".$_GET['id_menu']."&amp;opt_seccion=".$_GET['id_seccion']."&amp;opt_subseccion=add&amp;cod=".$_GET['id_estudio']."'><b>REGISTRAR</b></a></p></th>";
                                        //echo "<img class='editar' onclick='javascript: window.location.href=?opt_menu={".$smarty.get.opt_menu."}&amp;opt_seccion={".$smarty.get.opt_seccion."}&amp;opt_subseccion=edit&amp;cod={".$campos.id_estudio."}' title='Editar' src='../../../includes/imagenes/edit.gif'/>";
                                        //echo "<img class='editar' onclick='javascript: window.location.href=?opt_menu=".$_GET['id_menu']."&amp;opt_seccion=".$_GET['id_seccion']."&amp;opt_subseccion=edit&amp;cod=".$_GET['id_estudio']."' title='Editar' src='../../../includes/imagenes/edit.gif'/>";

                                    echo "</th>";
                                    echo "<th style='vertical-align:middle; height: 30px; width:900px;'><p align='right'><a href='?opt_menu=".$_GET['id_menu']."&amp;opt_seccion=".$_GET['id_seccion']."'><b>Minimizar</b></a></p></th>";
                                echo "</thead>";
            }

            echo
                '</tbody>
                </table>
                </div>
                </td>
                </tr>';

        break;


        case "getAlmacen":

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM almacen");

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
        case "getPuntos":
            $bdCentral=DB_REPORTE_CENTRAL;
            $estado=$_GET["estados"];
            $sql="SELECT `nombre_punto`,codigo_siga_punto as siga  from $bdCentral.puntos_venta";
            if($estado!=0){
                $sql.=" WHERE codigo_estado_punto=$estado";
            }
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }

            break;

        case "getSubCategoria":
            //$bdCentral=DB_REPORTE_CENTRAL;
            $BD_selectrapyme='selectrapyme';
            $categoria=$_GET["categoria"];

            $sql="SELECT descripcion,id_sub_grupo
            FROM $bdSelectrapyme.sub_grupo
            WHERE id_sub_grupo != ''
            ";

            if($categoria!=0){
                $sql.=" AND cod_grupo=$categoria";
            }
            $sql.=" ORDER BY descripcion";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }

        break;

        case "getProductos":
            //$bdCentral=DB_REPORTE_CENTRAL;
            $BD_selectrapyme=DB_SELECTRA_FAC;
            //$categoria=$_GET["categoria"];
            $sub_categoria=$_GET["sub_categoria"];

            $sql="SELECT TRIM(descripcion1) as descripcion1,codigo_barras
            FROM $bdSelectrapyme.item
            WHERE estatus = 'A'";

            if($sub_categoria!=0){
                $sql.=" AND sub_categoria=$sub_categoria";
            }
            /*if($categoria!=0){
                $sql.=" AND cod_grupo=$categoria";
            }*/
            $sql.=" ORDER BY descripcion1";
            $campos = $conn->ObtenerFilasBySqlSelect($sql);

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }

        break;


        case 'listaCXPpendientes':
            header("Content-Type: text/plain");
            $groupByBeneficiario = isset($_POST["groupBene"]) ? 'si' : 'no';
            if ($groupByBeneficiario == "no") {
                $sql_ = "SELECT   pro.id_proveedor, cxpd. * , pro.descripcion AS beneficiario, pro.rif, cxpd.monto as monto_pagar, (
    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'd'
    ) AS sum_debito, (

    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'c'
    ) AS sum_credito, (
    (

    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'c'
    ) - (
    SELECT ifnull( sum( monto ) , 0.00 )
    FROM cxp_edocuenta_detalle
    WHERE cod_edocuenta = cxp.cod_edocuenta
    AND tipo = 'd' )
    ) AS monto_pendiente
    FROM cxp_edocuenta_detalle cxpd
    INNER JOIN cxp_edocuenta cxp ON cxpd.cod_edocuenta = cxp.cod_edocuenta
    INNER JOIN proveedores pro ON pro.id_proveedor = cxp.id_proveedor
    WHERE cxpd.marca = 'P' and cxpd.documento<>'PAGOxCOM'
    ";

                if (isset($_POST["id_proveedor"])) {
                    $sql_ .= " and  pro.id_proveedor = " . $_POST["id_proveedor"];
                }


                $campos = $conn->ObtenerFilasBySqlSelect($sql_);



                $start = isset($_POST['start']) ? $_POST['start'] : 0; //posiciÃ³n a iniciar
                $limit = isset($_POST['limit']) ? $_POST['limit'] : 30; //nÃºmero de registros a mostrar

                echo json_encode(array(
                    "success" => true,
                    "total" => count($campos),
                    "data" => array_splice($campos, $start, $limit)
                ));
            }

            if ($groupByBeneficiario == "si") {
                $sql_ = "SELECT  distinct  pro.id_proveedor, pro.descripcion AS beneficiario
FROM cxp_edocuenta_detalle cxpd
INNER JOIN cxp_edocuenta cxp ON cxpd.cod_edocuenta = cxp.cod_edocuenta
INNER JOIN proveedores pro ON pro.id_proveedor = cxp.id_proveedor
WHERE cxpd.marca = 'P' and cxpd.documento<>'PAGOxCOM'";


                $campos = $conn->ObtenerFilasBySqlSelect($sql_);

                echo json_encode(array(
                    "success" => true,
                    "total" => count($campos),
                    "data" => $campos
                ));
            }





            break;
        case "convertiraLetras":

            header("Content-Type: text/plain");

            $n = new numerosALetras();
            $numero = $_GET["monto"];
            $num_letras = $n->convertir($numero);

            $array = array(
                "success" => true,
                "monto" => $num_letras
            );
            echo json_encode($array);
            break;
        case "tesodetasientos":
            header("Content-Type: text/plain");
            $cod_cheque = $_POST["cod_cheque"];
            $sql_ = "
                SELECT cod_cheque_bauchedet, cod_cheque, descripcion, cuenta_contable,
                CASE tipo WHEN  'd' THEN monto ELSE  '' END AS debito,
                CASE tipo WHEN  'c' THEN monto ELSE  '' END AS credito
                FROM `cheque_bache_det` WHERE cod_cheque = {$cod_cheque} ORDER BY tipo DESC;";
            /* SELECT
              cod_cheque_bauchedet,
              cod_cheque,
              descripcion,
              cuenta_contable,
              case tipo when 'd' then monto else '' end as debito,
              case tipo when 'c' then monto else '' end as credito
              FROM `cheque_bache_det` WHERE cod_cheque = " . $cod_cheque . " order by tipo desc
              "; */
            $campos = $conn->ObtenerFilasBySqlSelect($sql_);
            echo json_encode(array(
                "success" => true,
                "total" => count($campos),
                "data" => $campos
            ));
            break;
        case "store_cuContable":
            header("Content-Type: text/plain");
// CONSULTA DE CUENTAS CONTABLES
            $global = new bd(SELECTRA_CONF_PYME);

            if (isset($_POST["query"])) {
                /* if ($_POST["query"] == "") {
                  $cuentalike = " order by cuenta";
                  } else {
                  $cuentalike = " and upper(concat(cuenta,' .-',Descrip)) like upper('%" . $_POST["query"] . "%') order by cuenta";
                  } */
                $cuentalike = ($_POST["query"] == "") ? " ORDER BY cuenta" : " AND UPPER (CONCAT(cuenta,' .-',Descrip)) LIKE UPPER('%{$_POST["query"]}%') ORDER BY cuenta";
            }
            $sentencia = "SELECT * FROM nomempresa WHERE bd = '{$_SESSION['EmpresaFacturacion']}';";
            $contabilidad = $global->query($sentencia);
            $fila = $contabilidad->fetch_assoc();
            $campos_cuentas_cont = $conn->ObtenerFilasBySqlSelect("SELECT CONCAT(cuenta,' .-',Descrip) AS descripcion, cuenta FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo = 'P'" . $cuentalike);
//echo "select cuenta as descripcion, cuenta from ".$fila['bd_contabilidad'].".cwconcue WHERE Tipo='P'".$cuentalike." order Cuenta";
            $campos_cuentas_cant = $conn->ObtenerFilasBySqlSelect("SELECT cuenta AS descripcion, cuenta FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo = 'P'" . $cuentalike);

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_cuentas_cant),
                "data" => $campos_cuentas_cont
            ));
            break;
        case "store_vendedores":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor ORDER BY nombre");
#$campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM vendedor WHERE nombre = '".$_GET["nombre_vendedor"]."'");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case "store_tipoCuenta":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("SELECT * FROM tipo_cuenta_banco");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case "aCheBaucheDetCRUP":

            if ($_POST["cod_cheque_bauchedet"] != "" && $_POST["in_deleted"] != 1) {//UPDATIAR
                $sql = "
            update cheque_bache_det set
                        `monto` = " . $_POST["monto"] . ",
                        `tipo` = '" . (($_POST["tipo_a"] == "Debito") ? 'd' : 'c') . "',
                        `descripcion` = '" . $_POST["descripcion"] . "',
                        cuenta_contable = '" . $_POST["cuenta_contable"] . "'
           WHERE cod_cheque_bauchedet = " . $_POST["cod_cheque_bauchedet"];
                $conn->Execute2($sql);
            } elseif ($_POST["in_deleted"] == "1") {

                $sql = "delete from cheque_bache_det WHERE cod_cheque_bauchedet = " . $_POST["cod_cheque_bauchedet"];
                $conn->Execute2($sql);
            } else {//NUEVO ASIENTO CHEQUE BAUCHE DET
                $sql = "
            INSERT INTO `cheque_bache_det` (
                        `cod_cheque`,
                        `monto`,
                        `tipo`,
                        `fecha`,
                        `descripcion`,
                        `fecha_creacion`,
                        `usuario_creacion`,cuenta_contable)
                        VALUES (
                            " . $_POST["cod_cheque"] . ",
                            " . $_POST["monto"] . ",
                            '" . (($_POST["tipo_a"] == "Debito") ? 'd' : 'c') . "',
                            '" . date("Y-m-d") . "',
                            '" . $_POST["descripcion"] . "',
                            CURRENT_TIMESTAMP,
                            '" . $_SESSION['usuario'] . "',
                            '" . $_POST["cuenta_contable"] . "');";
                $conn->Execute2($sql);
            }

            echo json_encode(array(
                "success" => true,
                "msg" => "Asiento registrado exitosamente."
            ));


            break;
        case "listaProveedores":
            $campos_comunes = $conn->ObtenerFilasBySqlSelect("
    select
        id_proveedor,
        cod_proveedor,
        descripcion as  beneficiario,
        direccion,
        telefonos,
        fax,
        email,
        rif,
        nit
    from
        proveedores
	WHERE
	estatus='A'");
            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));
            break;
        case 'movimientos_bancarios_conciliar':

            list($dia1, $mes1, $anio1) = explode("/", $_POST["fecha1_"]);
            list($dia2, $mes2, $anio2) = explode("/", $_POST["fecha2_"]);
            $fecha1 = $anio1 . "-" . $mes1 . "-" . $dia1;
            $fecha2 = $anio2 . "-" . $mes2 . "-" . $dia2;
            $cod_cuenta = $_POST["cod_cuenta"];
            $sql = "
SELECT
mb.cod_movimiento_ban,
mb.cod_tesor_bancodet,
tm.descripcion as tipo_movimiento_desc,
mb.numero_movimiento,
mb.fecha_movimiento,
mb.concepto,
case when mb.tipo_movimiento =  3 or mb.tipo_movimiento =  4 then mb.monto  else 0 end debe,
case when mb.tipo_movimiento  =  1 or mb.tipo_movimiento =  2 then mb.monto  else 0 end haber,
mb.tipo_movimiento,
mb.estado,
mb.cod_conciliacion,
'false' as conciliar
 FROM `movimientos_bancarios` mb inner join tipo_movimientos_ban tm
 on tm.cod_tipo_movimientos_ban = mb.tipo_movimiento
 WHERE mb.fecha_movimiento between '" . $fecha1 . "' and '" . $fecha2 . "'
 and mb.cod_tesor_bancodet = " . $cod_cuenta . "  and mb.cod_conciliacion is null
order by mb.cod_movimiento_ban";
            $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes),
                "data" => $campos_comunes
            ));

            break;

        case 'cxpIvaFactura':

            $MONTOBASE = $_GET[montoBase];
            $codIva = $_GET[codIva];

            $ivas = $conn->ObtenerFilasBySqlSelect("select li.alicuota, fi.formula from lista_impuestos li join formulacion_impuestos fi on (li.cod_formula=fi.cod_formula) WHERE li.cod_impuesto=$codIva");
            $PORCENTAJE = $ivas[0][alicuota];
            eval($ivas[0][formula]);
            echo $cad = $PORCENTAJE . '-' . $MONTO;
            break;

        case 'cxpRetIslrFactura':
            $par1 = $conn->ObtenerFilasBySqlSelect("select unidad_tributaria from parametros_generales");
            $id_item = $_GET[servicio];
            $cod_entidad = $_GET[entidad];
            $item_totalsiniva = $_GET[montoBase];
            $islr = $conn->ObtenerFilasBySqlSelect("select si.cod_lista_impuesto, fi.formula, li.alicuota, li.pago_mayor_a, li.monto_sustraccion, li.descripcion, li.cod_impuesto from servicios_islr si join lista_impuestos li on (si.cod_lista_impuesto=li.cod_impuesto) join formulacion_impuestos fi on (fi.cod_formula=li.cod_formula) WHERE si.cod_item=$id_item and li.cod_entidad=$cod_entidad and li.pago_mayor_a<$item_totalsiniva");
            if ($islr[0]) {
                $UT = $par1[0]["unidad_tributaria"];
                $FACTORSUST = $islr[0]["monto_sustraccion"];
                $FACTORM = $islr[0]["pago_mayor_a"];
                $PORCENTAJE = $islr[0]["alicuota"];
                $MONTOBASE = $item_totalsiniva;
                $formula = $islr[0]["formula"];
                eval($formula);

                echo number_format($MONTO, 2, ".", "");
            }
            else
                echo $cad = 0;
            break;

        case 'retencionesFactura':

            $codFacs = $_GET["facs"];

            $retenciones = $conn->ObtenerFilasBySqlSelect("SELECT cpf.cod_impuesto, li.descripcion, sum(cpf.monto_iva) as base, porcentaje_iva_retenido, sum(cpf.monto_retenido) as montoRet, sum(cpf.monto_exento) as exento, li.cod_tipo_impuesto FROM cxp_factura cpf JOIN lista_impuestos li ON ( li.cod_impuesto = cpf.cod_impuesto ) WHERE id_factura in ($codFacs) group by cpf.cod_impuesto");
            $reg = '';
            $i = 0;
            foreach ($retenciones as $key => $campos) {
                if ($campos[montoRet] > 0) {
                    $reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_iva_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
                    $i++;
                }
            }

            $retenciones2 = $conn->ObtenerFilasBySqlSelect("SELECT cpfd.cod_impuesto, li.descripcion, sum(cpfd.monto_base) as base, porcentaje_retenido, sum(cpfd.monto_retenido) as montoRet, li.cod_tipo_impuesto FROM cxp_factura_detalle cpfd JOIN lista_impuestos li ON ( li.cod_impuesto = cpfd.cod_impuesto ) WHERE id_factura_fk in ($codFacs) group by cpfd.cod_impuesto");
            foreach ($retenciones2 as $key => $campos) {
                $reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
                $i++;
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;

// 	case 'retencionesFactura':
//
// 		$codFacs=$_GET["facs"];
// 		$retenciones2=$conn->ObtenerFilasBySqlSelect("SELECT cpfd.cod_impuesto, li.descripcion, sum(cpfd.monto_base) as base, porcentaje_retenido, sum(cpfd.monto_retenido) as montoRet, li.cod_tipo_impuesto FROM cxp_factura_detalle cpfd JOIN lista_impuestos li ON ( li.cod_impuesto = cpfd.cod_impuesto ) WHERE id_factura_fk in ($codFacs) group by cpfd.cod_impuesto");
// 		foreach($retenciones2 as $key => $campos)
// 		{
// 			$reg.="<tr><TD><input type='hidden' name='codImp$i' id='codImp$i' value='$campos[cod_impuesto]'><input type='hidden' name='exento$i' id='exento$i' value='$campos[exento]'><input type='hidden' name='tipoImp$i' id='tipoImp$i' value='$campos[cod_tipo_impuesto]'>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='base$i' id='base$i' value='$campos[base]'></TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='porcen$i' id='porcen$i' value='$campos[porcentaje_retenido]'></TD><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montoRet$i' id='montoRet$i' value='$campos[montoRet]'></TD></tr>";
// 			$i++;
// 		}
// 		$reg.="*l*l*l*".$i;
// 		echo $reg;
// 	break;

        case 'anticipos':

            $edoCta = $_GET["edoCta"];
            $retenciones2 = $conn->ObtenerFilasBySqlSelect("SELECT * FROM cxp_edocuenta_detalle WHERE cod_edocuenta=$edoCta AND tipo='d' and cod_edocuenta_detalle not in (select cxp_edocuenta_detalle_fk from cxp_factura_pago) and marca in ('P','X')");
            $reg = '';
            $i = 0;
            foreach ($retenciones2 as $key => $campos) {
                $reg.="<tr><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='numero$i' id='numero$i' value='$campos[numero]'></TD><TD>$campos[descripcion]</TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='monto$i' id='monto$i' value='$campos[monto]'></TD><TD><input name='optAnticipo{$i}' id='optAnticipo{$i}' type='checkbox' onchange='javascript:totalAnticipos();' value='{$i}'></TD></tr>";
                $i++;
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;

        case 'cambiarClave';
            $clave = $_GET["clave1"];
            $clave2 = $_GET["claveOLD"];

            $usuario = $login->getIdUsuario();
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM usuarios WHERE cod_usuario = '" . $login->getIdUsuario() . "' and
		 clave='" . $_GET["claveOLD"] . "'");

//echo "SELECT * FROM usuarios WHERE cod_usuario = '".$login->getIdUsuario()."' and
// clave='".$_GET["claveOLD"]."'";
//count($campos);
            if (count($campos) == 0) {
                echo "1";
            } else {
                /* echo "update usuarios set
                  `clave` = '".$_GET["clave1"]."'
                  WHERE cod_usuario = ".$login->getIdUsuario();
                 */
                $sql = "UPDATE usuarios SET `clave` = '" . $_GET["clave1"] . "' WHERE cod_usuario ='{$usuario}'";
                $conn->Execute2($sql);
//echo "-1";
            }
            break;
        case 'anularFactura';
            $idFac = $_GET["idFac"];
            $sql = "UPDATE cxp_factura SET cod_estatus = 2 WHERE id_factura = {$idFac};";
            $conn->Execute2($sql);
            break;
        case "eliminar_ordenCXP":
            $instruccion = "UPDATE cxp_edocuenta SET marca='A', fecha_anulacion='{$_GET["fechaOrden"]}', observacion_anulado='{$_GET ["motivoAnulacionOrden"]}' WHERE cod_edocuenta = '{$_GET["cod"]}'";
            $conn->Execute2($instruccion);
            /*
             * Modificado por: Charli Vivenes
             * Objetivo: incluir la eliminacion de las entradas en el inventario cuando se cancela la compra.
             * Desccripcion: se creo la tabla 'compra_kardex' para mantener la relacion entre el kardex y la compra
             *
             */
            $campos = $conn->ObtenerFilasBySqlSelect("SELECT id_kardex FROM compra_kardex WHERE id_compra = {$_GET["cod"]};");
            $campos2 = $conn->ObtenerFilasBySqlSelect("SELECT estado FROM kardex_almacen WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            $campos3 = $conn->ObtenerFilasBySqlSelect("SELECT * FROM kardex_almacen_detalle WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            $instruccion = "INSERT INTO kardex_almacen (tipo_movimiento_almacen, autorizado_por, observacion, fecha, usuario_creacion, fecha_creacion, estado, fecha_ejecucion)
                VALUES (8, 'Nadie', 'Salida por Devolucion Compra', '{$_GET["fechaOrden"]}', '{$_SESSION["usuario"]}', CURRENT_TIMESTAMP, 'Entregado', CURRENT_TIMESTAMP);";
            $conn->ExecuteTrans($instruccion);
            $id_kardex_almacen = $conn->getInsertID();
            /*
             * En este punto decidi registrar el detalle de la devolución.
             * Por ello está comentada la condición que fue relegada al interior del foreach.
             */
#if ($campos2[0]["estado"] == "Entregado") {
            foreach ($campos3 as $key => $kardex_almacen_detalle) {
                $conn->ExecuteTrans("INSERT INTO kardex_almacen_detalle (id_transaccion_detalle, id_transaccion, id_almacen_entrada, id_almacen_salida, id_item, cantidad)
                    VALUES (NULL, '{$id_kardex_almacen}', '0', '{$kardex_almacen_detalle["id_almacen_entrada"]}', '{$kardex_almacen_detalle["id_item"]}', '{$kardex_almacen_detalle["cantidad"]}');");
                if ($campos2[0]["estado"] == "Entregado") {
                    $conn->ExecuteTrans("UPDATE item_existencia_almacen SET cantidad = cantidad - '{$kardex_almacen_detalle["cantidad"]}'
                    WHERE id_item  = '{$kardex_almacen_detalle["id_item"]}' AND cod_almacen = '{$kardex_almacen_detalle["id_almacen_entrada"]}';");
                }
            }
#}
            if ($campos2[0]["estado"] == "Pendiente") {
                $conn->ExecuteTrans("UPDATE kardex_almacen SET estado = 'Cancelado' WHERE id_transaccion = {$campos[0]["id_kardex"]};");
            }
            break;
        case 'movimiento':
            $cliente = $_GET["cliente"];
//$movimiento=$conn->ObtenerFilasBySqlSelect("SELECT * FROM movimientos_bancarios  WHERE id_cliente=$cliente AND monto<>monto_aplicado");
            $movimiento = $conn->ObtenerFilasBySqlSelect("SELECT cod_movimiento_ban, numero_movimiento, concepto, (monto-(ifnull(monto_aplicado,0.00))) as monto, fecha_movimiento FROM movimientos_bancarios  WHERE id_cliente=$cliente and estatus IS NULL");
            $reg = '';
            $i = 0;
            if ($movimiento) {
                foreach ($movimiento as $key => $campos) {
                    $reg.="<tr><TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='numerom$i' id='numerom$i' value='$campos[numero_movimiento]'><input type='hidden' name='codmov$i' id='codmov$i' value='$campos[cod_movimiento_ban]'></TD><TD>$campos[concepto]</TD> <TD><input type='text' style='border:0px; background-color:#ffffff;' size='15' name='fechamov$i' id='fechamov$i' value='" . fecha($campos[fecha_movimiento]) . "'></TD><TD> <input type='text' style='border:0px; background-color:#ffffff;' size='15' name='montosss$i' id='montosss$i' value='$campos[monto]'></TD><TD><input name='optMov{$i}' id='optMov{$i}' type='checkbox' onchange='javascript:totalPagos();' value='{$i}'></TD></tr>";
                    $i++;
                }
            }
            $reg.="*l*l*l*" . $i;
            echo $reg;
            break;
				case "filtroItemByRCCB":
            /**
             * Procedimiento de busqueda de productos/servicios
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      lviera@armadillotec.com
             *      levieraf@gmail.com
             *
             */
            $tipo_item = (isset($_POST["cmb_tipo_item"])) ? $_POST["cmb_tipo_item"] : 1;

            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {
                //filtro para productos
                if ($tipo_item == 1) {
                    $codigo = (string)((isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "");
                    $andWHERE = "UPPER(i.referencia) = UPPER('".$codigo."') or UPPER(i.cod_item) like UPPER('%".$codigo."%') or UPPER(i.codigo_barras) like UPPER('%".$codigo."%')";
                }

                $sql = "SELECT i.* FROM item i WHERE i.cod_item_forma = " . $tipo_item . " and (" . $andWHERE . ") ";

                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);

                $sql = "SELECT i.* FROM item i WHERE i.cod_item_forma = " . $tipo_item . " and " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            } else {
                $sql = "SELECT i.* FROM item i WHERE i.cod_item_forma = " . $tipo_item;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT i.* FROM item i WHERE i.cod_item_forma = " . $tipo_item . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));

            break;
            case "cargarRegion":
                //cargar la region
                    $idEstado = $_POST["idEstado"];

                     $campos = $conn->ObtenerFilasBySqlSelect("SELECT a.id_region,b.descripcion FROM estado_region a, region b where a.id_estado=".$idEstado." and a.id_region=b.id limit 1;");
                     echo $campos[0]["descripcion"];
            break;
            
            case "reporte_productoCentral":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $producto = $_GET["producto"];
                    $categoria = $_GET["categoria"];
                    $sub_categoria = $_GET["sub_categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $tipo_punto = $_GET["tipo_punto"];
                    $tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    $marca_producto = $_GET["marca_producto"];
                    $indices = $_GET["indices"];
                    $codigo_barras = $_GET["codigo_barras"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            $ventas_mes_anno="ventas_13_".$anno_des."_xproducto";
                            $kardex_mes_anno="kardex_13_".$anno_des."";
                    }else{

                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_xproducto";
                   $ventas_mes_anno2="ventas_".$mes_des."_".$anno_des;
                   $kardex_mes_anno="kardex_".$mes_des."_".$anno_des;
                }


                    $ventas_anno="ventas_".$anno_des."_xproducto";
                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;


                    //ORIGINAL
                   /*$sql=" SELECT v.CODE,(i.pesoxunidad*um.transformar*SUM(v.UNITS))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   INNER JOIN selectrapyme.item i on i.codigo_barras=v.code
                   INNER JOIN selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";
                   $sql="SELECT v.CODE,(i.pesoxunidad*um.transformar*SUM(v.UNITS))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto,
                   SUM(v.UNITS) as UNITS, SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,
                   SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                    FROM $BD_central.$ventas_mes_anno v
                    LEFT JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                    LEFT JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    LEFT JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                    LEFT JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    LEFT JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    WHERE v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    $sql="SELECT v.cod_bar,(i.pesoxunidad*um.transformar*SUM(v.cantidad))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto, i.pesoxunidad as pesoxunidad,
                   SUM(v.cantidad) as UNITS, SUM(v.precio*v.cantidad) as TOTALSINIVA,
                   SUM(((v.precio*tx.RATE)+v.precio)*v.cantidad) as TOTALCONIVA,
                   v.precio,((v.precio*tx.RATE) + v.precio) as PRECIOIVA, tx.RATE, i.estatus,
                   v.tipo_almacenamiento as tipo_almacenamiento,
                   mc.marca as marca, um.nombre_unidad as nombre_unidad
                    FROM $BD_central.$ventas_anno v
                    LEFT JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                    LEFT JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    LEFT JOIN $BD_selectrapyme.marca mc on mc.id = v.id_marca
                    LEFT JOIN $BD_central.taxes tx ON tx.CATEGORY = v.id_taxes
                    WHERE v.fecha BETWEEN '$desde' and '$hasta'
                    ";

                    /*
                    $sql_cod_barras = "SELECT v.CODE
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    WHERE v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    if($punto!="0"){
                        $sql.=" and v.punto_venta='$punto'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and v.id_estado='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }
                    if($producto!="0"){
                        $sql.=" and v.cod_bar='$producto'";
                        //$sql_cod_barras.=" and v.CODE='$producto'";
                    }
                    if($categoria!="0"){

                        $sql.=" and v.id_categoria='$categoria'";
                        //$sql_cod_barras.=" and v.category='$categoria'";
                    }
                    if($sub_categoria!="0"){

                        $sql.=" and v.id_subcategoria='$sub_categoria'";
                        //$sql_cod_barras.=" and v.category='$sub_categoria'";
                    }
                    if($tipo_punto!=""){

                        $sql.=" and v.id_tipo_punto='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }
                    if($tipo_almacenamiento!=""){

                        $sql.=" and v.tipo_almacenamiento ='$tipo_almacenamiento'";
                        //$sql_cod_barras.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }
                    if($marca_producto!="0"){

                        $sql.=" and v.id_marca ='$marca_producto'";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }

                    if($indices=='regulado'){

                        $sql.=" and i.regulado =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='cestack_basica'){

                        $sql.=" and i.cestack_basica =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='bcv'){

                        $sql.=" and i.bcv =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='sae'){

                        $sql.=" and i.sae =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if ($codigo_barras!="") {
                        $sql.=" and i.codigo_barras = '$codigo_barras'";
                    }

                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto"; Para ver el query y luego probarlo en el PHPMYADMIN
                    $sql.=" GROUP BY v.cod_bar ORDER BY i.descripcion1";
                    //echo $sql; exit(); 
                    //$sql_cod_barras.=" GROUP BY v.CODE ORDER BY v.nombre_producto";

                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);



                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="12" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="20px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="150px" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="300px" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="100px" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="100px" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="100px" align="center"><b><font color="white">ENT. INV.</font></b></td>
                            <td width="100px" align="center"><b><font color="white">PRECIO UND SIN IVA</font></b></td>
                            <td width="100px" align="center"><b><font color="white">PRECIO UND CON IVA</font></b></td>
                            <td width="100px" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="100px" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            <td width="30px" align="center"><b><font color="white">IVA</font></b></td>
                            <td width="30px" align="center"><b><font color="white">ACT</font></b></td>
                        </tr>
                        </table>
                    <div style="max-height: 400px; overflow: auto;overflow: scroll;" >
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        $listaCodeBarras="";
                        $totalfrio=0;
                        $totalseco=0;
                        $i=1;
                        foreach ($reporte as $lista ) {
                            $totalUni+=$lista["UNITS"];
                            $totalTone+=$lista["TONELAJE"];
                            $totalSinIva+=$lista["TOTALSINIVA"];
                            $totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["precio"];//Nuevo, para imprimir al final del total
                            $listaCodeBarras.="'".$lista["cod_bar"]."',";

                            if($lista["tipo_almacenamiento"]=='SECO'){

                                $totalseco=$totalseco+$lista["UNITS"]; 

                            }elseif($lista["tipo_almacenamiento"]=='FRIO'){

                                $totalfrio=$totalfrio+$lista["UNITS"];
                            }

                            //Calculo las entradas a inventario de ese producto:

                            $sql_inv="SELECT sum(i.cantidad) as entradas
                            FROM $BD_central.$kardex_mes_anno i
                            WHERE i.fecha BETWEEN '$desde' and '$hasta'
                            AND i.codigo_barras = '".$lista["cod_bar"]."'
                            AND tipo_movimiento='Cargo'
                            ";
                            $entradas = $conn->ObtenerFilasBySqlSelect($sql_inv);


                            //$totalSeco+=$lista['CANTSECO'];

                     ?>
                        <tr>
                            <td width="20px" align="center"><?php echo $i; ?></td>
                            <td width="150px" align="center"><?php echo $lista["cod_bar"] ?></td>
                            <td width="300px" align="center">
                                <?php
                                    echo utf8_encode($lista["nombre_producto"]);
                                    echo " - ".$lista["marca"]." ";
                                    echo $lista["pesoxunidad"].$lista["nombre_unidad"];
                                ?>
                            </td>
                            <td width="100px" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo number_format($lista["TONELAJE"], 3, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo number_format($entradas[0]['entradas'], 3, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo number_format($lista["precio"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo number_format($lista["TOTALSINIVA"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>                            
                            <td width="30px" align="center"><?php echo $lista["RATE"] ?></td>
                            <td width="30px" align="center"><?php echo $lista["estatus"] ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                        $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>
                     <tr>
                        <td colspan="3" align="center"><b>TOTALES</b></td>
                        <td align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                        <td align="center"><b><?php echo number_format($totalTone, 3, ',', '.'); ?></b></td>
                        <td align="center"><b><?php echo number_format($totalSinIva, 2, ',', '.');  ?></b></td>
                        <td align="center"><b><?php echo number_format($totalConIva, 2, ',', '.'); ?></b></td>
                        <td align="center"><b><?/*php echo number_format($totalPdSinIva, 2, ',', '.'); */?></b></td>
                        <td align="center"></td>
                        
                    </tr>
                    <tr>
                        <td colspan="9"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                        <td align="center" colspan="3"><b>TOTAL PRODUCTOS SECOS</b></td>
                        <td align="center"><b>
                            <?php


                                /*Este es el SCRIPT ORIGINAL....!!!!*/
                                /*$sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno2 v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND pv.codigo_estado_punto='$estado'
                                AND tipo_almacenamiento = 'SECO'
                                ";
                                $sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                                WHERE v.DATENEW BETWEEN  '$desde' and '$hasta'
                                AND i.codigo_barras IN ($listaCodeBarras)
                                AND i.tipo_almacenamiento ='SECO'
                                AND i.estatus = 'A'
                                ";

                                //$varcero = 0;
                                /*Se le concatena cada uno de los items y tomarlos en cuenta al momento de realizar
                                 consultas especificas
                                if($estado!="0"){
                                    $sql_seco.=" and pv.codigo_estado_punto='$estado'";
                                }
                                if($punto!="0"){
                                    $sql_seco.=" and v.codigo_siga='$punto'";
                                }
                                if($categoria!="0"){
                                    $sql_seco.=" and i.cod_grupo='$categoria'";
                                }
                                if($producto!="0"){
                                    $sql_seco.=" and v.code='$producto'";
                                }
                                if($tipo_punto!=""){
                                    $sql_seco.=" and ptp.id_tipo ='$tipo_punto'";
                                }

                                //print_r($listaCodeBarras);
                                //echo $sql_seco; exit(); 

                                /*Se coloca un COUNT que sera igual a 1 (uno) y sino esta vacio '' imprima la variable $valor
                                $valor = "0";
                                $reporteSeco = $conn->ObtenerFilasBySqlSelect($sql_seco);
                                $cantidadSeco = count($reporteSeco);
                                if($reporteSeco[0]['totalUnits']>0){*/

                                    //echo $reporteSeco[0]['totalUnits'];
                                    echo number_format($totalseco, 2, ',', '.');

                                   /*
                                }elseif($reporteSeco[0]['totalUnits']==''){

                                    echo $valor;

                                }
                                //print("Hola");
                                al llenar un array con "ObtenerFilasBySqlSelect" lo llena de forma matricial
                                por lo que al imprimir el resultado hay que colocarlo [0]['campo_a_mostrar']*/
                                //$reporteSeco = $conn->ObtenerFilasBySqlSelect($sql_seco);
                                //echo $reporteSeco[0]['totalUnits'];

                            ?>
                        </b></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                    </tr>
                    <tr>
                        <td align="center" colspan="3"><b>TOTAL PRODUCTOS FRIOS</b></td>
                        <td align="center"><b>
                            <?php


                                /*Este es el SCRIPT ORIGINAL....!!!!
                                $sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND pv.codigo_estado_punto='$estado'
                                AND tipo_almacenamiento = 'SECO'
                                ";
                                $sql_frio = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                                INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                                WHERE v.DATENEW BETWEEN  '$desde' and '$hasta'
                                AND i.codigo_barras IN ($listaCodeBarras)
                                AND i.tipo_almacenamiento ='FRIO'
                                AND i.estatus = 'A'
                                ";

                                //$varcero = 0;
                                /*Se le concatena cada uno de los items y tomarlos en cuenta al momento de realizar
                                 consultas especificas
                                if($estado!="0"){
                                    $sql_frio.=" and pv.codigo_estado_punto='$estado'";
                                }
                                if($punto!="0"){
                                    $sql_frio.=" and v.codigo_siga='$punto'";
                                }
                                if($categoria!="0"){
                                    $sql_frio.=" and i.cod_grupo='$categoria'";
                                }
                                if($producto!="0"){
                                    $sql_frio.=" and v.code='$producto'";
                                }
                                if($tipo_punto!=""){
                                    $sql_frio.=" and ptp.id_tipo ='$tipo_punto'";
                                }

                                /*Se coloca un COUNT que sera igual a 1 (uno) y sino esta vacio '' imprima la variable $valor
                                $valor = "0";
                                $reporteFrio = $conn->ObtenerFilasBySqlSelect($sql_frio);
                                $cantidadFrio = count($reporteFrio);
                                if($reporteFrio[0]['totalUnits']>0){

                                    //echo $reporteFrio[0]['totalUnits'];
                                    */
                                    echo number_format($totalfrio, 2, ',', '.');
                                    /*
                                }elseif($reporteFrio[0]['totalUnits']==''){

                                    echo $valor;

                                }*/

                            ?>
                        </b></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                    </tr>

                    </table>
                    </div>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            // termina el else de las fechas iguales

            break;

            case "reporte_productoCentral_xprecio":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $producto = $_GET["producto"];
                    $categoria = $_GET["categoria"];
                    $sub_categoria = $_GET["sub_categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $tipo_punto = $_GET["tipo_punto"];
                    $tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    $marca_producto = $_GET["marca_producto"];
                    $indices = $_GET["indices"];
                    $codigo_barras = $_GET["codigo_barras"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);



                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            $ventas_mes_anno="ventas_13_".$anno_des."_xproducto";
                    }else{


                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_xproducto";
                   $ventas_mes_anno2="ventas_".$mes_des."_".$anno_des;
                    }


                    $ventas_anno="ventas_".$anno_des."_xproducto";
                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    //ORIGINAL
                   /*$sql=" SELECT v.CODE,(i.pesoxunidad*um.transformar*SUM(v.UNITS))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   INNER JOIN selectrapyme.item i on i.codigo_barras=v.code
                   INNER JOIN selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";
                   $sql="SELECT v.CODE,(i.pesoxunidad*um.transformar*SUM(v.UNITS))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto,
                   SUM(v.UNITS) as UNITS, SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,
                   SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                    FROM $BD_central.$ventas_mes_anno v
                    LEFT JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                    LEFT JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    LEFT JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                    LEFT JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    LEFT JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    WHERE v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    $sql="SELECT v.cod_bar,(i.pesoxunidad*um.transformar*SUM(v.cantidad))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto,
                   mc.marca as marca,um.nombre_unidad as nombre_unidad,i.pesoxunidad as pesoxunidad,
                   SUM(v.cantidad) as UNITS, SUM(v.precio*v.cantidad) as TOTALSINIVA,
                   SUM(((v.precio*tx.RATE)+v.precio)*v.cantidad) as TOTALCONIVA,
                   v.precio,((v.precio*tx.RATE) + v.precio) as PRECIOIVA, tx.RATE
                    FROM $BD_central.$ventas_anno v
                    LEFT JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.punto_venta
                    LEFT JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                    LEFT JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    LEFT JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    LEFT JOIN $BD_central.taxes tx ON tx.CATEGORY = v.id_taxes
                    WHERE v.fecha BETWEEN '$desde' and '$hasta'
                    ";

                    /*
                    $sql_cod_barras = "SELECT v.CODE
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    WHERE v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    if($punto!="0"){
                        $sql.=" and v.punto_venta='$punto'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }
                    if($producto!="0"){
                        $sql.=" and v.cod_bar='$producto'";
                        //$sql_cod_barras.=" and v.CODE='$producto'";
                    }
                    if($categoria!="0"){

                        $sql.=" and i.cod_grupo='$categoria'";
                        //$sql_cod_barras.=" and v.category='$categoria'";
                    }
                    if($sub_categoria!="0"){

                        $sql.=" and i.sub_categoria='$sub_categoria'";
                        //$sql_cod_barras.=" and v.category='$sub_categoria'";
                    }
                    if($tipo_punto!=""){

                        $sql.=" and ptp.id_tipo ='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }
                    if($tipo_almacenamiento!=""){

                        $sql.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                        //$sql_cod_barras.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }
                    if($marca_producto!="0"){

                        $sql.=" and mc.id ='$marca_producto'";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }

                    if($indices=='regulado'){

                        $sql.=" and i.regulado =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='cestack_basica'){

                        $sql.=" and i.cestack_basica =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='bcv'){

                        $sql.=" and i.bcv =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='sae'){

                        $sql.=" and i.sae =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if ($codigo_barras!="") {
                        $sql.=" and i.codigo_barras = '$codigo_barras'";
                    }

                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto"; Para ver el query y luego probarlo en el PHPMYADMIN
                    $sql.=" GROUP BY v.cod_bar, v.precio ORDER BY i.descripcion1";
                    //echo $sql; exit();
                    //$sql_cod_barras.=" GROUP BY v.CODE ORDER BY v.nombre_producto";

                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);



                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="10" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="25px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="130px" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="300px" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="80px" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="120px" align="center"><b><font color="white">TONELADAS</font></b></td>
                            <td width="120px" align="center"><b><font color="white">PRECIO UND SIN IVA</font></b></td>
                            <td width="120px" align="center"><b><font color="white">PRECIO UND CON IVA</font></b></td>
                            <td width="130px" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="130px" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            
                            <td width="25px" align="center"><b><font color="white">IVA</font></b></td>
                        </tr>
                        </table>
                    <div style="max-height: 400px; overflow: auto;overflow: scroll;" >
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
	
                    <?php
                        $listaCodeBarras="";
                        $i=1;
                        foreach ($reporte as $lista ) {
                            $totalUni+=$lista["UNITS"];
                            $totalTone+=$lista["TONELAJE"];
                            $totalSinIva+=$lista["TOTALSINIVA"];
                            $totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["precio"];//Nuevo, para imprimir al final del total
                            $listaCodeBarras.="'".$lista["cod_bar"]."',";

                            //$totalSeco+=$lista['CANTSECO'];

                     ?>
                        <tr>
                            <td width="25px" align="center"><?php echo $i; ?></td>
                            <td width="130px" align="center"><?php echo $lista["cod_bar"] ?></td>
                            <td width="300px" align="center">
                                <?php
                                    echo utf8_encode($lista["nombre_producto"]);
                                    echo " ".$lista["marca"]." ";
                                    echo $lista["pesoxunidad"].$lista["nombre_unidad"];
                                ?>
                            </td>
                            <td width="80px" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="120px" align="center"><?php echo number_format($lista["TONELAJE"], 3, ',', '.'); ?></td>
                            <td width="120px" align="center"><?php echo number_format($lista["precio"], 2, ',', '.'); ?></td>
                            <td width="120px" align="center"><?php echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="130px" align="center"><?php echo number_format($lista["TOTALSINIVA"], 2, ',', '.'); ?></td>
                            <td width="130px" align="center"><?php echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>
                            
                            <td width="25px" align="center"><?php echo $lista["RATE"] ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                        $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>
                     <tr>
                            <td></td>
                            <td></td>
                            <td align="center"><b>TOTALES</b></td>
                            <td align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td align="center"><b><?php echo number_format($totalTone, 3, ',', '.'); ?></b></td>
                            <td align="center"><b><?php echo number_format($totalSinIva, 2, ',', '.');  ?></b></td>
                            <td align="center"><b><?php echo number_format($totalConIva, 2, ',', '.'); ?></b></td>
                            <td align="center"><b><?/*php echo number_format($totalPdSinIva, 2, ',', '.'); */?></b></td>
                            <td align="center"></td>
                            <td align="center"></td>
                    </tr>
                    <tr>
                        <td colspan="10"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td align="center"><b>TOTAL PRODUCTOS SECOS</b></td>
                        <td align="center"><b>
                            <?php


                                /*Este es el SCRIPT ORIGINAL....!!!!*/
                                /*$sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno2 v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND pv.codigo_estado_punto='$estado'
                                AND tipo_almacenamiento = 'SECO'
                                ";*/
                                $sql_seco = "SELECT SUM(v.cantidad) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.punto_venta
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                                INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                                WHERE v.fecha BETWEEN  '$desde' and '$hasta'
                                AND i.codigo_barras IN ($listaCodeBarras)
                                AND i.tipo_almacenamiento ='SECO'
                                AND i.estatus = 'A'
                                ";

                                //$varcero = 0;
                                /*Se le concatena cada uno de los items y tomarlos en cuenta al momento de realizar
                                 consultas especificas*/
                                if($estado!="0"){
                                    $sql_seco.=" and pv.codigo_estado_punto='$estado'";
                                }
                                if($punto!="0"){
                                    $sql_seco.=" and v.punto_venta='$punto'";
                                }
                                if($categoria!="0"){
                                    $sql_seco.=" and i.cod_grupo='$categoria'";
                                }
                                if($producto!="0"){
                                    $sql_seco.=" and v.cod_bar='$producto'";
                                }
                                if($tipo_punto!=""){
                                    $sql_seco.=" and ptp.id_tipo ='$tipo_punto'";
                                }

                                //print_r($listaCodeBarras);
                                //echo $sql_seco; exit();

                                /*Se coloca un COUNT que sera igual a 1 (uno) y sino esta vacio '' imprima la variable $valor*/
                                $valor = "0";
                                $reporteSeco = $conn->ObtenerFilasBySqlSelect($sql_seco);
                                $cantidadSeco = count($reporteSeco);
                                if($reporteSeco[0]['totalUnits']>0){

                                    //echo $reporteSeco[0]['totalUnits'];
                                    echo number_format($reporteSeco[0]['totalUnits'], 2, ',', '.');

                                }elseif($reporteSeco[0]['totalUnits']==''){

                                    echo $valor;

                                }
                                //print("Hola");
                                /*al llenar un array con "ObtenerFilasBySqlSelect" lo llena de forma matricial
                                por lo que al imprimir el resultado hay que colocarlo [0]['campo_a_mostrar']*/
                                //$reporteSeco = $conn->ObtenerFilasBySqlSelect($sql_seco);
                                //echo $reporteSeco[0]['totalUnits'];

                            ?>
                        </b></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center"></td>
                        <td align="center"><b>TOTAL PRODUCTOS FRIOS</b></td>
                        <td align="center"><b>
                            <?php


                                /*Este es el SCRIPT ORIGINAL....!!!!
                                $sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND pv.codigo_estado_punto='$estado'
                                AND tipo_almacenamiento = 'SECO'
                                ";*/
                                $sql_frio = "SELECT SUM(v.cantidad) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.punto_venta
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                                INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                                WHERE v.fecha BETWEEN  '$desde' and '$hasta'
                                AND i.codigo_barras IN ($listaCodeBarras)
                                AND i.tipo_almacenamiento ='FRIO'
                                AND i.estatus = 'A'
                                ";

                                //$varcero = 0;
                                /*Se le concatena cada uno de los items y tomarlos en cuenta al momento de realizar
                                 consultas especificas*/
                                if($estado!="0"){
                                    $sql_frio.=" and pv.codigo_estado_punto='$estado'";
                                }
                                if($punto!="0"){
                                    $sql_frio.=" and v.punto_venta='$punto'";
                                }
                                if($categoria!="0"){
                                    $sql_frio.=" and i.cod_grupo='$categoria'";
                                }
                                if($producto!="0"){
                                    $sql_frio.=" and v.cod_bar='$producto'";
                                }
                                if($tipo_punto!=""){
                                    $sql_frio.=" and ptp.id_tipo ='$tipo_punto'";
                                }

                                /*Se coloca un COUNT que sera igual a 1 (uno) y sino esta vacio '' imprima la variable $valor*/
                                $valor = "0";
                                $reporteFrio = $conn->ObtenerFilasBySqlSelect($sql_frio);
                                $cantidadFrio = count($reporteFrio);
                                if($reporteFrio[0]['totalUnits']>0){

                                    //echo $reporteFrio[0]['totalUnits'];
                                    echo number_format($reporteFrio[0]['totalUnits'], 2, ',', '.');

                                }elseif($reporteFrio[0]['totalUnits']==''){

                                    echo $valor;

                                }

                            ?>
                        </b></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                    </tr>

                    </table>
                    </div>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

          

            break;

            case "reporte_productoInexistenteCentral":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    //$producto = $_GET["producto"];
                    //$categoria = $_GET["categoria"];
                    //$sub_categoria = $_GET["sub_categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    //$tipo_punto = $_GET["tipo_punto"];
                    //$tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    //$marca_producto = $_GET["marca_producto"];
                    //$indices = $_GET["indices"];
                    //$codigo_barras = $_GET["codigo_barras"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){
                        $ventas_mes_anno="ventas_13_".$anno_des."_xproducto";
                    }else{
                        $ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_xproducto";
                        $ventas_mes_anno2="ventas_".$mes_des."_".$anno_des;
                    }

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*$sql="SELECT v.cod_bar,(i.pesoxunidad*um.transformar*SUM(v.cantidad))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto, i.pesoxunidad as pesoxunidad,
                   SUM(v.cantidad) as UNITS, SUM(v.precio*v.cantidad) as TOTALSINIVA,
                   SUM(((v.precio*tx.RATE)+v.precio)*v.cantidad) as TOTALCONIVA,
                   v.precio,((v.precio*tx.RATE) + v.precio) as PRECIOIVA, tx.RATE, i.estatus,
                   v.tipo_almacenamiento as tipo_almacenamiento,
                   mc.marca as marca, um.nombre_unidad as nombre_unidad
                    FROM $BD_central.$ventas_mes_anno v
                    LEFT JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                    LEFT JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    LEFT JOIN $BD_selectrapyme.marca mc on mc.id = v.id_marca
                    LEFT JOIN $BD_central.taxes tx ON tx.ID = v.id_taxes
                    WHERE v.fecha BETWEEN '$desde' and '$hasta'
                    ";*/

                    $sql="select xp.cod_bar,TRIM(v.nombre_producto) as nombre_producto,
                    e.nombre_estado,pv.nombre_punto
                    from $BD_central.$ventas_mes_anno xp
                    LEFT JOIN $BD_central.$ventas_mes_anno2 v on v.CODE=xp.cod_bar
                    LEFT JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=xp.punto_venta
                    LEFT JOIN $BD_central.estados e on e.codigo_estado=pv.codigo_estado_punto
                    where xp.fecha BETWEEN '$desde' and '$hasta'
                    and xp.cod_bar not in (select i.codigo_barras from $BD_selectrapyme.item i )
                    ";

                    if($punto!="0"){
                        $sql.=" and xp.punto_venta='$punto'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and xp.id_estado='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }

                    /*if($producto!="0"){
                        $sql.=" and v.cod_bar='$producto'";
                        //$sql_cod_barras.=" and v.CODE='$producto'";
                    }
                    if($categoria!="0"){

                        $sql.=" and v.id_categoria='$categoria'";
                        //$sql_cod_barras.=" and v.category='$categoria'";
                    }
                    if($sub_categoria!="0"){

                        $sql.=" and v.id_subcategoria='$sub_categoria'";
                        //$sql_cod_barras.=" and v.category='$sub_categoria'";
                    }
                    if($tipo_punto!=""){

                        $sql.=" and v.id_tipo_punto='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }
                    if($tipo_almacenamiento!=""){

                        $sql.=" and v.tipo_almacenamiento ='$tipo_almacenamiento'";
                        //$sql_cod_barras.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }
                    if($marca_producto!="0"){

                        $sql.=" and v.id_marca ='$marca_producto'";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='regulado'){

                        $sql.=" and i.regulado =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='cestack_basica'){

                        $sql.=" and i.cestack_basica =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='bcv'){

                        $sql.=" and i.bcv =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='sae'){

                        $sql.=" and i.sae =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if ($codigo_barras!="") {
                        $sql.=" and i.codigo_barras = '$codigo_barras'";
                    }*/

                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto"; Para ver el query y luego probarlo en el PHPMYADMIN
                    $sql.=" group by xp.cod_bar order by e.nombre_estado asc, pv.nombre_punto asc";
                    //echo $sql; exit();
                    //$sql_cod_barras.=" GROUP BY v.CODE ORDER BY v.nombre_producto";

                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);

                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10">
                        <tr bgcolor="#5084A9">
                            <td colspan="5" align="center"><b><font color="white">REPORTE DE PRODUCTOS INEXISTENTES EN SEDE CENTRAL DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="20px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="150px" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="350px" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="150px" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="350px" align="center"><b><font color="white">PUNTO DE VENTA</font></b></td>
                            <!-- <td width="10%" align="center"><b><font color="white">PRECIO UND SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">PRECIO UND CON IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>                            
                            <td width="10%" align="center"><b><font color="white">IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">ACT</font></b></td> -->
                        </tr>
                        </table>
                    <div style="max-height: 400px; overflow: auto;overflow: scroll;" >
                    <table  border="1" align="center" style="margin-left: 10">

                    <?php
                        $listaCodeBarras="";
                        $totalfrio=0;
                        $totalseco=0;
                        $i=1;
                        foreach ($reporte as $lista ) {
                            //$totalUni+=$lista["UNITS"];
                            //$totalTone+=$lista["TONELAJE"];
                            //$totalSinIva+=$lista["TOTALSINIVA"];
                            //$totalConIva+=$lista["TOTALCONIVA"];
                            //$totalPdSinIva+=$lista["precio"];//Nuevo, para imprimir al final del total
                            $listaCodeBarras.="'".$lista["cod_bar"]."',";

                            if($lista["tipo_almacenamiento"]=='SECO'){

                                $totalseco=$totalseco+$lista["UNITS"]; 

                            }elseif($lista["tipo_almacenamiento"]=='FRIO'){

                                $totalfrio=$totalfrio+$lista["UNITS"];
                            }

                            //$totalSeco+=$lista['CANTSECO'];

                     ?>
                        <tr>
                            <td width="20px" align="center"><?php echo $i; ?></td>
                            <td width="150px" align="center"><?php echo $lista["cod_bar"] ?></td>
                            <td width="350px" align="center">
                                <?php
                                    echo utf8_encode($lista["nombre_producto"]);
                                    //echo " - ".$lista["marca"]." ";
                                    //echo $lista["pesoxunidad"].$lista["nombre_unidad"];
                                ?>
                            </td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>
                            <td width="350px" align="center"><?php echo utf8_encode($lista["nombre_punto"]); ?></td>
                            <!-- <td align="center"><?php echo number_format($lista["precio"], 2, ',', '.'); ?></td>
                            <td align="center"><?php echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TOTALSINIVA"], 2, ',', '.'); ?></td>
                            <td align="center"><?php echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>                            
                            <td align="center"><?php echo $lista["RATE"] ?></td>
                            <td align="center"><?php echo $lista["estatus"] ?></td> -->
                        </tr>

                    <?php
                        $i++;
                        }
                        $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>
                     <!-- <tr>
                        <td colspan="3" align="center"><b>TOTALES</b></td>
                        <td align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                        <td align="center"><b><?php echo number_format($totalTone, 3, ',', '.'); ?></b></td>
                        <td align="center"><b><?php echo number_format($totalSinIva, 2, ',', '.');  ?></b></td>
                        <td align="center"><b><?php echo number_format($totalConIva, 2, ',', '.'); ?></b></td>
                        <td align="center"><b><?/*php echo number_format($totalPdSinIva, 2, ',', '.'); */?></b></td>
                        <td align="center"></td>
                        
                    </tr> -->
                    <tr>
                        <td colspan="5"><hr color="black" size="2" /></td>
                    </tr>
                    <!-- <tr>
                        <td align="center" colspan="3"><b>TOTAL PRODUCTOS SECOS</b></td>
                        <td align="center"><b>
                            <?php


                                /*Este es el SCRIPT ORIGINAL....!!!!*/
                                /*$sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno2 v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND pv.codigo_estado_punto='$estado'
                                AND tipo_almacenamiento = 'SECO'
                                ";
                                $sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                                WHERE v.DATENEW BETWEEN  '$desde' and '$hasta'
                                AND i.codigo_barras IN ($listaCodeBarras)
                                AND i.tipo_almacenamiento ='SECO'
                                AND i.estatus = 'A'
                                ";

                                //$varcero = 0;
                                /*Se le concatena cada uno de los items y tomarlos en cuenta al momento de realizar
                                 consultas especificas
                                if($estado!="0"){
                                    $sql_seco.=" and pv.codigo_estado_punto='$estado'";
                                }
                                if($punto!="0"){
                                    $sql_seco.=" and v.codigo_siga='$punto'";
                                }
                                if($categoria!="0"){
                                    $sql_seco.=" and i.cod_grupo='$categoria'";
                                }
                                if($producto!="0"){
                                    $sql_seco.=" and v.code='$producto'";
                                }
                                if($tipo_punto!=""){
                                    $sql_seco.=" and ptp.id_tipo ='$tipo_punto'";
                                }

                                //print_r($listaCodeBarras);
                                //echo $sql_seco; exit(); 

                                /*Se coloca un COUNT que sera igual a 1 (uno) y sino esta vacio '' imprima la variable $valor
                                $valor = "0";
                                $reporteSeco = $conn->ObtenerFilasBySqlSelect($sql_seco);
                                $cantidadSeco = count($reporteSeco);
                                if($reporteSeco[0]['totalUnits']>0){*/

                                    //echo $reporteSeco[0]['totalUnits'];
                                    echo number_format($totalseco, 2, ',', '.');

                                   /*
                                }elseif($reporteSeco[0]['totalUnits']==''){

                                    echo $valor;

                                }
                                //print("Hola");
                                al llenar un array con "ObtenerFilasBySqlSelect" lo llena de forma matricial
                                por lo que al imprimir el resultado hay que colocarlo [0]['campo_a_mostrar']*/
                                //$reporteSeco = $conn->ObtenerFilasBySqlSelect($sql_seco);
                                //echo $reporteSeco[0]['totalUnits'];

                            ?>
                        </b></td>
                        <td width="5%" align="center"></td>
                        <td width="5%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                    </tr>
                    <tr>
                        <td align="center" colspan="3"><b>TOTAL PRODUCTOS FRIOS</b></td>
                        <td align="center"><b>
                            <?php


                                /*Este es el SCRIPT ORIGINAL....!!!!
                                $sql_seco = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.taxes t ON v.taxid_tikestline = t.ID
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                                WHERE v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                                AND pv.codigo_estado_punto='$estado'
                                AND tipo_almacenamiento = 'SECO'
                                ";
                                $sql_frio = "SELECT SUM(v.UNITS) AS totalUnits
                                FROM $BD_central.$ventas_mes_anno v
                                INNER JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                                INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.cod_bar
                                INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                                WHERE v.DATENEW BETWEEN  '$desde' and '$hasta'
                                AND i.codigo_barras IN ($listaCodeBarras)
                                AND i.tipo_almacenamiento ='FRIO'
                                AND i.estatus = 'A'
                                ";

                                //$varcero = 0;
                                /*Se le concatena cada uno de los items y tomarlos en cuenta al momento de realizar
                                 consultas especificas
                                if($estado!="0"){
                                    $sql_frio.=" and pv.codigo_estado_punto='$estado'";
                                }
                                if($punto!="0"){
                                    $sql_frio.=" and v.codigo_siga='$punto'";
                                }
                                if($categoria!="0"){
                                    $sql_frio.=" and i.cod_grupo='$categoria'";
                                }
                                if($producto!="0"){
                                    $sql_frio.=" and v.code='$producto'";
                                }
                                if($tipo_punto!=""){
                                    $sql_frio.=" and ptp.id_tipo ='$tipo_punto'";
                                }

                                /*Se coloca un COUNT que sera igual a 1 (uno) y sino esta vacio '' imprima la variable $valor
                                $valor = "0";
                                $reporteFrio = $conn->ObtenerFilasBySqlSelect($sql_frio);
                                $cantidadFrio = count($reporteFrio);
                                if($reporteFrio[0]['totalUnits']>0){

                                    //echo $reporteFrio[0]['totalUnits'];
                                    */
                                    echo number_format($totalfrio, 2, ',', '.');
                                    /*
                                }elseif($reporteFrio[0]['totalUnits']==''){

                                    echo $valor;

                                }*/

                            ?>
                        </b></td>
                        <td width="5%" align="center"></td>
                        <td width="5%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                        <td width="10%" align="center"></td>
                    </tr> -->

                    </table>
                    </div>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "<h4>NO SE ENCONTRARON RESULTADOS PARA LA BUSQUEDA!!!</h4>";
                }

            // termina el else de las fechas iguales

            break;

            case "reporte_productoSobreLimite": //Creado por Humberto
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $unidades = $_GET["unidades"];
                    $empleados = $_GET["empleados"];
                    $cedula_clientes = $_GET["cedula_clientes"];
                    //$clientes = $_GET["clientes"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            $ventas_mes_anno="ventas_13_".$anno_des."_xproducto";
                            //echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{

                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*Original y funcional*/
                    $sql = "SELECT taxid AS CEDULA, name_persona AS NOMBRE_APELLIDO, CODE,
                    nombre_producto AS PRODUCTO, units as CANTIDAD_UNIDADES, price AS PRICE,
                    datenew_ticketlines AS FECHA_COMPRA, codigo_siga, nombre_punto AS PUNTO_VENTA,
                    nombre_estado AS ESTADO, HOST
                    FROM $BD_central.$ventas_mes_anno
                    LEFT JOIN selectrapyme_central.grupo ON grupo.grupopos = $ventas_mes_anno.category
                    LEFT JOIN selectrapyme_central.puntos_venta ON codigo_siga_punto = codigo_siga
                    LEFT JOIN selectrapyme_central.estados ON codigo_estado_punto = codigo_estado
                    WHERE codigo_estado_punto = codigo_estado
                    AND codigo_siga_punto = codigo_siga
                    AND datenew_ticketlines BETWEEN  '$desde 00:00:00' and '$hasta 23:59:59'
                    ";

                    /* Escript que une que clientes compraron mas de 10 productos a nivel nacional y que sean
                    Empleados de PDVAL
                    SELECT taxid AS CEDULA, name_persona AS NOMBRE_APELLIDO, CODE, nombre_producto AS PRODUCTO, units as CANTIDAD_UNIDADES, price AS PRICE, datenew_ticketlines AS FECHA_COMPRA, codigo_siga, nombre_punto AS PUNTO_VENTA, nombre_estado AS ESTADO
                    FROM selectrapyme_central.ventas_03_16
                    LEFT JOIN selectrapyme_central.grupo ON grupo.grupopos = ventas_03_16.category,
                    selectrapyme_central.puntos_venta, selectrapyme_central.estados,
                    cedula_empleado
                    WHERE codigo_estado_punto = codigo_estado
                    AND codigo_siga_punto = codigo_siga
                    AND datenew_ticketlines BETWEEN '2016-03-01 00:00:00' and '2016-03-14 00:00:00'
                    AND SUBSTR(taxid,2) = numero_cedula
                    AND units>='10'
                    GROUP BY CEDULA, PRODUCTO,CANTIDAD_UNIDADES, FECHA_COMPRA
                    ORDER BY CANTIDAD_UNIDADES DESC
                    */

                    if($punto!="0"){
                        $sql.=" AND codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" AND codigo_estado_punto='$estado'";
                    }
                    if($unidades!=""){
                        $sql.=" AND units>='$unidades'";
                    }
                    if($empleados=="empleados"){
                        $sql.=" AND SUBSTR(taxid,2) IN (SELECT numero_cedula FROM $BD_central.cedula_empleado)";
                    }
                    if($empleados=="clientes"){
                        $sql.=" AND SUBSTR(taxid,2) NOT IN (SELECT numero_cedula FROM $BD_central.cedula_empleado)";
                    }
                    if ($cedula_clientes!="") {
                        $sql.=" AND SUBSTR(taxid,2) = '$cedula_clientes'";
                    }

                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    $sql.=" GROUP BY CEDULA,PRODUCTO,CANTIDAD_UNIDADES,FECHA_COMPRA ORDER BY CANTIDAD_UNIDADES DESC";
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="12" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <!--<td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>-->
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CEDULA</font></b></td>
                            <td width="15%" align="center"><b><font color="white">NOMBRE Y APELLIDO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PRECIO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">TOTAL</font></b></td>
                            <td width="10%" align="center"><b><font color="white">FECHA Y HORA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CAJA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PUNTO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">ESTADO</font></b></td>
                        </tr>

                    <?php
                        $i=1;
                        foreach($reporte as $lista){
                            $totalUni+=$lista["CANTIDAD_UNIDADES"];
                            $totalPrice+=$lista["PRICE"];
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/

                     ?>
                        <tr>
                            <td width="5%" align="center"><?php echo $i; ?></td>
                            <td width="5%"><?php echo $lista["CEDULA"] ?></td>
                            <td width="15%" align="center"><?php echo utf8_encode($lista["NOMBRE_APELLIDO"]); ?></td>
                            <td width="5%" align="center"><?php echo $lista["CODE"] ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["PRODUCTO"]); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["CANTIDAD_UNIDADES"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["PRICE"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center">
                                <?php
                                    $b = $lista["CANTIDAD_UNIDADES"];
                                    $a = $lista["PRICE"];
                                    $c = $a * $b;
                                    echo number_format($c, 2, ',', '.');
                                ?>
                            </td>
                            <td width="10%" align="center"><?php echo $lista["FECHA_COMPRA"]; ?></td>
                            <td width="5%" align="center"><?php echo $lista["HOST"]; ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["PUNTO_VENTA"]); ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["ESTADO"]); ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                    ?>
                     <tr>
                        <td colspan="12"><hr color="black" size="2" /></td>
                    </tr>
                     <tr>
                            <td width="5%"></td>
                            <td width="5%"></td>
                            <td width="15%" align="center"></td>
                            <td width="5%" align="center"><b>TOTAL UNIDADES</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                    </tr>
                    <tr>
                            <td width="5%"></td>
                            <td width="5%"></td>
                            <td width="15%" align="center"></td>
                            <td width="5%" align="center"><b>TOTAL VENTAS</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalPrice, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                    </tr>
                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            }// termina el else de las fechas iguales


            case "reporte_libro_ventas": //Creado por Humberto
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];


                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*Original y funcional*/
                    $sql = "SELECT *
                    FROM $BD_central.libro_ventas_central
                    LEFT JOIN selectrapyme_central.puntos_venta ON codigo_siga_punto = codigo_siga
                    LEFT JOIN selectrapyme_central.estados ON codigo_estado_punto = codigo_estado
                    WHERE codigo_estado_punto = codigo_estado
                    AND codigo_siga_punto = codigo_siga
                    AND fecha BETWEEN  '$desde' and '$hasta'
                    ";

                    /* Escript que une que clientes compraron mas de 10 productos a nivel nacional y que sean
                    Empleados de PDVAL
                    SELECT taxid AS CEDULA, name_persona AS NOMBRE_APELLIDO, CODE, nombre_producto AS PRODUCTO, units as CANTIDAD_UNIDADES, price AS PRICE, datenew_ticketlines AS FECHA_COMPRA, codigo_siga, nombre_punto AS PUNTO_VENTA, nombre_estado AS ESTADO
                    FROM selectrapyme_central.ventas_03_16
                    LEFT JOIN selectrapyme_central.grupo ON grupo.grupopos = ventas_03_16.category,
                    selectrapyme_central.puntos_venta, selectrapyme_central.estados,
                    cedula_empleado
                    WHERE codigo_estado_punto = codigo_estado
                    AND codigo_siga_punto = codigo_siga
                    AND datenew_ticketlines BETWEEN '2016-03-01 00:00:00' and '2016-03-14 00:00:00'
                    AND SUBSTR(taxid,2) = numero_cedula
                    AND units>='10'
                    GROUP BY CEDULA, PRODUCTO,CANTIDAD_UNIDADES, FECHA_COMPRA
                    ORDER BY CANTIDAD_UNIDADES DESC
                    */

                    if($punto!="0"){
                        $sql.=" AND codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" AND codigo_estado_punto='$estado'";
                    }

                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    //echo $sql; exit();
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10">
                        <tr bgcolor="#5084A9">
                            <td colspan="12" align="center"><b><font color="white">REPORTE DE LIBRO DE VENTAS DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <!--<td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>-->
                            <td width="20px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="150px" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="150px" align="center"><b><font color="white">ESTABLECIMIENTO</font></b></td>
                            <td width="110px" align="center"><b><font color="white">SERIAL IMPRESORA</font></b></td>
                            <td width="50px" align="center"><b><font color="white">N° Z</font></b></td>
                            <td width="100px" align="center"><b><font color="white">N° FACTURAS</font></b></td>
                            <td width="100px" align="center"><b><font color="white">FECHA ELABORACION</font></b></td>
                            <td width="110px" align="center"><b><font color="white">MONTO BRUTO</font></b></td>
                            <td width="110px" align="center"><b><font color="white">MONTO EXENTO</font></b></td>
                            <td width="110px" align="center"><b><font color="white">BASE IMPONIBLE</font></b></td>
                            <td width="110px" align="center"><b><font color="white">IVA</font></b></td>
                            
                        </tr>
                    </table>
                    <div style="max-height: 400px; overflow: auto;overflow: scroll;" >
                    <table  border="1" align="center" style="margin-left: 10">
        
                    <?php
                        $i=1;
                        $total_bruto=0;
                        $total_excento=0;
                        $total_base=0;
                        $total_iva=0;
                        foreach($reporte as $lista){
                            $totalUni+=$lista["CANTIDAD_UNIDADES"];
                            $totalPrice+=$lista["PRICE"];
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/
                            $vowels = array("PUNTO DE VENTA - ",$lista["nombre_estado"], "-");
                     ?>
                        <tr>
                            <td width="20px" align="center"><?php echo $i; ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode(str_replace($vowels, "", $lista["nombre_punto"])); ?></td>
                            <td width="110px" align="center"><?php echo utf8_encode($lista["serial_impresora"]); ?></td>
                            <td width="50px" align="center"><?php echo $lista["numero_z_usuario"]; ?></td>
                            <td width="100px" align="center"><?php echo $lista["numeros_facturas"]; ?></td>
                            <td width="100px" align="center"><?php echo $lista["fecha_emision"]; ?></td>
                            <td width="110px" align="center"><?php echo number_format($lista["monto_bruto_usuario"], 2, ',', '.');?></td>
                            <td width="110px" align="center"><?php echo number_format($lista["monto_exento_usuario"], 2, ',', '.');?></td>
                            <td width="110px" align="center"><?php echo number_format($lista["base_imponible_usuario"], 2, ',', '.');?></td>
                            <td width="110px" align="center"><?php echo number_format($lista["iva_usuario"], 2, ',', '.'); ?></td>
                            
                        </tr>

                    <?php
                        $total_bruto=$total_bruto+$lista["monto_bruto_usuario"];
                        $total_excento=$total_excento+$lista["monto_exento_usuario"];
                        $total_base=$total_base+$lista["base_imponible_usuario"];
                        $total_iva=$total_iva+$lista["iva_usuario"];
                        $i++;
                        }
                    ?>
                     <tr>
                        <td colspan="12"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                        <td colspan="7" align="center"><b>TOTALES</b></td><td align="center"><?php echo number_format($total_bruto, 2, ',', '.'); ?></td>
                        <td align="center"><?php echo number_format($total_excento, 2, ',', '.'); ?></td>
                        <td align="center"><?php echo number_format($total_base, 2, ',', '.'); ?></td>
                        <td align="center"><?php echo number_format($total_iva, 2, ',', '.'); ?></td>
                    </tr>
                    </table>
                    </div>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                var titulo_reporte='Reporte Libro de Ventas';
                                window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }


            break;

            case "reporte_productoSobreLimite2": //Creado por Humberto
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $unidades = $_GET["unidades"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $codigo_barras = $_GET["codigo_barras"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){
                    		//$ventas_mes_anno="ventas_13_".$anno_des."_xproducto";
                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{
                    //$ventas_mes_anno="ventas_".$mes_des."_".$anno_des."_xproducto";		
                    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;
                	
                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*Original y funcional*/
                    $sql = "SELECT v.TAXID as taxid, v.name_persona, v.CODE, v.nombre_producto, v.UNITS,
                    v.PRICE, v.datenew_ticketlines, v.codigo_siga,
                    pv.nombre_punto, et.nombre_estado,
                    mc.marca as marca, um.nombre_unidad as nombre_unidad, i.pesoxunidad as pesoxunidad
                    FROM $BD_central.$ventas_mes_anno v
                    LEFT JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                    LEFT JOIN $BD_central.estados et ON pv.codigo_estado_punto = et.codigo_estado
                    LEFT JOIN $BD_selectrapyme.item i ON i.codigo_barras = v.CODE
                    LEFT JOIN $BD_selectrapyme.unidad_medida um ON i.unidadxpeso = um.id
                    LEFT JOIN $BD_selectrapyme.marca mc ON mc.id = i.id_marca
                    WHERE v.UNITS>0
                    AND datenew_ticketlines BETWEEN '$desde 00:00:00' and '$hasta 23:59:59'
                    ";
                    //echo $sql;exit()
                    /*$sql= "SELECT v.taxid,v.name_persona,v.CODE as CODE,v.nombre_producto,v.units as UNITS,v.price as PRICE,v.datenew_ticketlines,v.codigo_siga,pv.nombre_punto,et.nombre_estado
                        FROM $BD_central.$ventas_mes_anno v
                        INNER JOIN $BD_central.grupo gp ON gp.grupopos = v.category
                        INNER JOIN $BD_central.puntos_venta pv
                        INNER JOIN $BD_central.estados et

                        WHERE pv.codigo_estado_punto = et.codigo_estado
                        AND pv.codigo_siga_punto = v.codigo_siga
                        AND v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    //ORIGINAL como ejemplo para el nuevo ajax
                   /*$sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";*/


                    if($punto!="0"){
                        $sql.=" and codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and codigo_estado_punto='$estado'";
                    }
                    /*if($unidades!=""){
                        $sql.=" and units>='$unidades'";
                    }*/
                    /*if($unidades!="0"){

                        $sql.=" and v.category='$categoria'";
                    }*/
                    if ($codigo_barras!="") {
                        $sql.=" and i.codigo_barras = '$codigo_barras'";
                    }


                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    $sql.=" ORDER BY nombre_punto, taxid, nombre_producto, datenew_ticketlines";
                    //echo $sql; exit();
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="11" align="center"><b><font color="white">REPORTE DE UNIDADES VENDIDAS POR CLIENTE DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <!--<td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>-->
                            <td width="50px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="80px" align="center"><b><font color="white">CEDULA</font></b></td>
                            <td width="150px" align="center"><b><font color="white">NOMBRE Y APELLIDO</font></b></td>
                            <td width="120px" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="300px" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="50px" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="80px" align="center"><b><font color="white">PRECIO</font></b></td>
                            <td width="100px" align="center"><b><font color="white">FECHA Y HORA</font></b></td>
                            <td width="50px" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="150px" align="center"><b><font color="white">PUNTO</font></b></td>
                            <td width="100px" align="center"><b><font color="white">ESTADO</font></b></td>
                        </tr>
                        </table>
                    <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        $i=1;
                        foreach ($reporte as $lista ) {
                            $total_iva=$lista["UNITS"]*$lista["PRICE"];
                            $totalUni+=$lista["UNITS"];
                            $totalPrice+=$total_iva;
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/

                     ?>
                        <tr>
                            <td width="50px" align="center"><?php echo $i; ?></td>
                            <td width="80px" align="center"><?php echo $lista["taxid"] ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["name_persona"]); ?></td>
                            <td width="120px" align="center"><?php echo $lista["CODE"] ?></td>
                            <td width="300px" align="center">
                                <?php
                                //echo utf8_encode($lista["nombre_producto"]);
                                echo utf8_encode($lista["nombre_producto"]);
                                echo " - ".$lista["marca"]." ";
                                echo $lista["pesoxunidad"].$lista["nombre_unidad"];
                                ?>
                            </td>
                            <td width="50px" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="80px" align="center"><?php echo number_format($lista["PRICE"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo $lista["datenew_ticketlines"]; ?></td>
                            <td width="50px" align="center"><?php echo $lista["codigo_siga"]; ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre_punto"]); ?></td>
                            <td width="100px" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                    ?>
                     <tr>
                        <td colspan="11"><hr color="black" size="2" /></td>
                    </tr>
                     <tr>
                            <td></td>
                            <td></td>
                            <td align="center"></td>
                            <td align="center"><b>TOTAL UNIDADES</b></td>
                            <td align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                    </tr>
                    <tr>
                            <td></td>
                            <td></td>
                            <td align="center"></td>
                            <td align="center"><b>TOTAL VENTAS</b></td>
                            <td align="center"><b><?php echo number_format($totalPrice, 2, ',', '.');  ?></b></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                    </tr>
                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            }// termina el else de las fechas iguales

            break;

            case "reporte_productoEstablecimiento": //Creado por HZ
                    //$punto = $_GET["punto"];
                    //$estado = $_GET["estados"];
                    //$unidades = $_GET["unidades"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $codigo_barras = $_GET['codigo_barras'];
                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{

                    $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    
                    $sql = "SELECT v.CODE, v.nombre_producto, SUM(v.UNITS) as TotalUnidades,
                    v.datenew_ticketlines, v.codigo_siga,
                    pv.nombre_punto, et.nombre_estado
                    FROM $BD_central.$ventas_mes_anno v
                    LEFT JOIN $BD_central.puntos_venta pv ON pv.codigo_siga_punto = v.codigo_siga
                    LEFT JOIN $BD_central.estados et ON pv.codigo_estado_punto = et.codigo_estado
                    WHERE v.UNITS>0
                    AND datenew_ticketlines BETWEEN '$desde 00:00:00' and '$hasta 23:59:59'
                    
                    ";


                    /*if($punto!="0"){
                        $sql.=" and codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and codigo_estado_punto='$estado'";
                    }*/
                    /*if($unidades!=""){
                        $sql.=" and units>='$unidades'";
                    }*/
                    /*if($unidades!="0"){

                        $sql.=" and v.category='$categoria'";
                    }*/

                    if($codigo_barras!=""){

                        $sql.=" AND v.CODE='$codigo_barras'";
                    }


                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    $sql.=" GROUP BY v.nombre_producto, pv.nombre_punto, et.nombre_estado
                     ORDER BY et.codigo_estado, TotalUnidades DESC";
                    //echo $sql; //exit();
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="10" align="center"><b><font color="white">REPORTE DE PRODUCTOS VENDIDOS POR ESTABLECIMIENTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="50px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="150px" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="350px" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="50px" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="100px" align="center"><b><font color="white">FECHA Y HORA</font></b></td>
                            <td width="100px" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="200px" align="center"><b><font color="white">PUNTO</font></b></td>
                            <td width="150px" align="center"><b><font color="white">ESTADO</font></b></td>
                        </tr>
                        </table>
                        <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        $i=1;
                        foreach ($reporte as $lista ) {
                            //$totalUni+=$lista["UNITS"];
                            //$totalPrice+=$lista["PRICE"];
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/

                     ?>
                        <tr>
                            <td width="50px" align="center"><?php echo $i; ?></td>
                            <td width="150px" align="center"><?php echo $lista["CODE"] ?></td>
                            <td width="350px" align="center"><?php echo utf8_encode($lista["nombre_producto"]); ?></td>
                            <td width="50px" align="center"><?php echo number_format($lista["TotalUnidades"], 2, ',', '.'); ?></td>
                            <td width="100px" align="center"><?php echo $lista["datenew_ticketlines"]; ?></td>
                            <td width="100px" align="center"><?php echo $lista["codigo_siga"]; ?></td>
                            <td width="200px" align="center"><?php echo utf8_encode($lista["nombre_punto"]); ?></td>
                            <td width="150px" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                    ?>
                    <!--<tr>
                        <td colspan="11"><hr color="black" size="2" /></td>
                    </tr>-->
                     <!-- <tr>
                            <td width="5%"></td>
                            <td width="5%"></td>
                            <td width="15%" align="center"></td>
                            <td width="5%" align="center"><b>TOTAL UNIDADES</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                    </tr>
                    <tr>
                            <td width="5%"></td>
                            <td width="5%"></td>
                            <td width="15%" align="center"></td>
                            <td width="5%" align="center"><b>TOTAL VENTAS</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalPrice, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                    </tr> -->
                    </table>
                    </div>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            }// termina el else de las fechas iguales

            break;

            case "rep_trans_rango": //Creado por Junior
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $unidades = $_GET["unidades"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{

                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;




                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    $sql = "SELECT taxid, name_persona, CODE, nombre_producto, units as UNITS, price as PRICE,
                    datenew_ticketlines, codigo_siga, nombre_punto, nombre_estado
                    FROM $BD_central.$ventas_mes_anno
                    LEFT JOIN selectrapyme_central.grupo ON grupo.grupopos = $ventas_mes_anno.category,
                    selectrapyme_central.puntos_venta, selectrapyme_central.estados
                    WHERE codigo_estado_punto = codigo_estado
                    AND codigo_siga_punto = codigo_siga
                    AND datenew_ticketlines
                    BETWEEN  '$desde' and '$hasta'
                    ";

                    //ORIGINAL como ejemplo para el nuevo ajax
                   /*$sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,
                   SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                   FROM $BD_central.$ventas_mes_anno v
                   INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                   INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                   WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta'
                   ";*/


                    if($punto!="0"){
                        $sql.=" and codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and codigo_estado_punto='$estado'";
                    }
                    if($unidades!=""){
                        $sql.=" and units>='$unidades'";
                    }
                    /*if($unidades!="0"){

                        $sql.=" and v.category='$categoria'";
                    }*/


                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";
                    $sql.=" ORDER BY nombre_punto, taxid, nombre_producto, datenew_ticketlines";
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="10" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <!--<td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>-->
                            <td width="5%" align="center"><b><font color="white">CEDULA</font></b></td>
                            <td width="15%" align="center"><b><font color="white">NOMBRE Y APELLIDO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PRECIO</font></b></td>
                            <td width="10%" align="center"><b><font color="white">FECHA Y HORA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PUNTO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">ESTADO</font></b></td>
                        </tr>

                    <?php
                        foreach ($reporte as $lista ) {
                            $totalUni+=$lista["UNITS"];
                            $totalPrice+=$lista["PRICE"];
                            /*$totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            //$totalSeco+=$lista['CANTSECO'];*/

                     ?>
                        <tr>
                            <td width="5%"><?php echo $lista["taxid"] ?></td>
                            <td width="15%" align="center"><?php echo utf8_encode($lista["name_persona"]); ?></td>
                            <td width="5%" align="center"><?php echo $lista["CODE"] ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["nombre_producto"]); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["PRICE"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo $lista["datenew_ticketlines"]; ?></td>
                            <td width="5%" align="center"><?php echo $lista["codigo_siga"]; ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["nombre_punto"]); ?></td>
                            <td width="10%" align="center"><?php echo utf8_encode($lista["nombre_estado"]); ?></td>
                        </tr>

                    <?php
                        }
                    ?>
                     <tr>
                        <td colspan="10"><hr color="black" size="2" /></td>
                    </tr>
                     <tr>
                            <td width="5%"></td>
                            <td width="15%" align="center"></td>
                            <td width="5%" align="center"><b>TOTAL UNIDADES</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                    </tr>
                    <tr>
                            <td width="5%"></td>
                            <td width="15%" align="center"></td>
                            <td width="5%" align="center"><b>TOTAL VENTAS</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalPrice, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                    </tr>
                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            }// termina el else de las fechas iguales

            break;

            case "reporte_productoTotal":
                    //$punto = $_GET["punto"];
                    //$estado = $_GET["estados"];
                    $producto = $_GET["producto"];
                    $categoria = $_GET["categoria"];
                    $sub_categoria = $_GET["sub_categoria"];
                    //$desde = $_GET["desde"];
                    //$hasta = $_GET["hasta"];
                    //$tipo_punto = $_GET["tipo_punto"];
                    $tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    $marca_producto = $_GET["marca_producto"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{

                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;


                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    //ORIGINAL
                   /*$sql="SELECT v.CODE,(i.pesoxunidad*um.transformar*SUM(v.UNITS))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto,
                   SUM(v.UNITS) as UNITS, SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,
                   SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    WHERE v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    $sql = "SELECT i.fecha_creacion as FCREACION,i.codigo_barras as CODE,
                    TRIM(i.descripcion1) as nombre_producto,
                    mc.marca as MARCAP,
                    i.pesoxunidad as PESOUNI,
                    um.nombre_unidad as UNIXPESO,
                    i.regulado as REGULADO,
                    i.cestack_basica as CESTABASICA,
                    i.bcv as BCV,
                    i.iva as iva,
                    i.cantidad_bulto as cantidad_bulto, 
                    i.producto_vencimiento as producto_vencimiento,
                    i.precio1 as preciosiniva,
                    i.coniva1 as precioiva
                        FROM $BD_selectrapyme.item i, $BD_selectrapyme.grupo gp, $BD_selectrapyme.marca mc, $BD_selectrapyme.unidad_medida um

                        WHERE mc.id = i.id_marca
                        AND um.id = i.unidadxpeso
                        AND i.estatus = 'A'
                        
                        ";

                    /*if($punto!="0"){
                        $sql.=" and v.codigo_siga='$punto'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }*/
                    /*if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }*/
                    if($categoria!="0"){

                        $sql.=" and i.cod_grupo=$categoria";

                    }

                    if($sub_categoria!="0"){

                        $sql.=" and i.sub_categoria=$sub_categoria";

                    }
                    /*if($tipo_punto!=""){

                        $sql.=" and ptp.id_tipo ='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }*/
                    if($producto!=""){
                        $sql.=" and i.codigo_barras='$producto'";
                    }
                    if($tipo_almacenamiento!=""){
                        $sql.=" and i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }
                    if($marca_producto!="0"){
                        $sql.=" and mc.id ='$marca_producto'";
                    }

                    //echo $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto"; Para ver el query y luego probarlo en el PHPMYADMIN
                    $sql.=" GROUP BY i.codigo_barras";

                    //echo $sql; 
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);



                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="15" align="center"><b><font color="white">REPORTE DE PRODUCTOS TOTALES: </font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="50px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="100px" align="center"><b><font color="white">Fecha Creaci&oacute;n</font></b></td>
                            <td width="150px" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="200px" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="100px" align="center"><b><font color="white">MARCA</font></b></td>
                            <td width="50px" align="center"><b><font color="white">PESO UNIDAD</font></b></td>
                            <td width="50px" align="center"><b><font color="white">UNIDAD MEDIDA</font></b></td>
                            <td width="80px" align="center"><b><font color="white">REGULADO</font></b></td>
                            <td width="50px" align="center"><b><font color="white">CESTA BÁSICA</font></b></td>
                            <td width="50px" align="center"><b><font color="white">BCV</font></b></td>
                            <td width="80px" align="center"><b><font color="white">PRECIO S/IVA</font></b></td>
                            <td width="80px" align="center"><b><font color="white">PRECIO C/IVA</font></b></td>
                            <td width="80px" align="center"><b><font color="white">IVA</font></b></td>
                            <td width="50px" align="center"><b><font color="white">Cant. Bulto</font></b></td>
                            <td width="50px" align="center"><b><font color="white">Fecha Venc.</font></b></td>

                        </tr>
                        </table>
                        <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        //$listaCodeBarras="";
                        $i="0";
                        $j=1;
                        foreach ($reporte as $lista ) {
                            $totalPesoUnidad+=$lista["PESOUNI"];
                            $totalProductos+=$lista["CANTPRODUCTOS"];

                     ?>
                        <tr>
                            <td width="50px" align="center"><?php echo $j; ?></td>
                            <td width="100px" align="center"><?php echo $lista["FCREACION"]; ?></td>
                            <td width="150px" align="center"><?php echo $lista["CODE"]; ?></td>
                            <td width="200px" align="center"><?php echo utf8_encode($lista["nombre_producto"]); ?></td>
                            <td width="100px" align="center"><?php echo utf8_encode($lista["MARCAP"]); ?></td>
                            <td width="50px" align="center"><?php echo number_format($lista["PESOUNI"]); ?></td>
                            <td width="50px" align="center"><?php echo $lista["UNIXPESO"]; ?></td>
                            <?php
                                if ($lista["REGULADO"]==0) {?>
                                    <td width="80px" align="center">X</td>
                                <?php
                                } else { ?>
                                    <td width="80px" align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <?php
                                if ($lista["CESTABASICA"]==0) {?>
                                    <td width="50px" align="center">X</td>
                                <?php
                                } else { ?>
                                    <td width="50px" align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <?php
                                if ($lista["BCV"]==0) {?>
                                    <td width="50px" align="center">X</td>
                                <?php
                                } else { ?>
                                    <td width="50px" align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <td width="80px" align="center"><?php echo $lista["preciosiniva"]; ?></td>
                            <td width="80px" align="center"><?php echo $lista["precioiva"]; ?></td>
                            <td width="80px" align="center"><?php echo $lista["iva"]; ?></td>
                            <td width="50px" align="center"><?php echo $lista["cantidad_bulto"]; ?></td>
                            <td width="50px" align="center"><?php echo $lista["producto_vencimiento"]; ?></td>
                        </tr>
                    <?php
                         $i++;
                         $j++;
                        }
                    ?>
                    </table>
                    </div>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                    }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                    }

            }// termina el else de las fechas iguales
            break;

            case "reporte_productoTotal_Priorizados":
                    //$punto = $_GET["punto"];
                    //$estado = $_GET["estados"];
                    $producto = $_GET["producto"];

                    $sql = "SELECT * FROM item_priorizados ORDER BY grupo, subgrupo, rubro";


                    //echo $sql; 
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);



                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="15" align="center"><b><font color="white">REPORTE DE PRODUCTOS PRIORIZADOS: </font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="50px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="250px" align="center"><b><font color="white">GRUPO</font></b></td>
                            <td width="250px" align="center"><b><font color="white">SUBGRUPO</font></b></td>
                            <td width="250px" align="center"><b><font color="white">RUBRO</font></b></td>
                            <td width="150px" align="center"><b><font color="white">CODIGO ARANCELARIO</font></b></td>
                            <td width="250px" align="center"><b><font color="white">DESCRIPCION ARANCEL</font></b></td>

                        </tr>
                        </table>
                        <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        //$listaCodeBarras="";
                        $i="0";
                        $j=1;
                        foreach ($reporte as $lista ) {
                            $totalPesoUnidad+=$lista["PESOUNI"];
                            $totalProductos+=$lista["CANTPRODUCTOS"];

                     ?>
                        <tr>
                            <td width="50px" align="center"><?php echo $j; ?></td>
                            <td width="250px" align="center"><?php echo utf8_encode($lista["grupo"]); ?></td>
                            <td width="250px" align="center"><?php echo utf8_encode($lista["subgrupo"]); ?></td>
                            <td width="250px" align="center"><?php echo utf8_encode($lista["rubro"]); ?></td>
                            <td width="150px" align="center"><?php echo $lista["codigo_arancelario"]; ?></td>
                            <td width="250px" align="center"><?php echo utf8_encode($lista["descripcion_arancel"]); ?></td>
                        </tr>
                    <?php
                         $i++;
                         $j++;
                        }
                    ?>
                    </table>
                    </div>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                    }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                    }

            break;

            case "reporte_art_autorizados":
                    //$punto = $_GET["punto"];
                    //$estado = $_GET["estados"];
                    //$producto = $_GET["producto"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    //$tipo_punto = $_GET["tipo_punto"];
                    //$tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    //$art_siscolp_siga = $_GET["art_siscolp_siga"];
                    //$marca_producto = $_GET["marca_producto"];

                    /*$anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{

                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;
                   */


                    //$BD_central=DB_REPORTE_CENTRAL;
                    //$BD_selectrapyme=DB_SELECTRA_FAC;

                    //ORIGINAL
                   /*$sql="SELECT i.cod_item as CODEITEM,i.codigo_barras as CODE, TRIM(i.descripcion1) as nombre_producto, mc.marca as MARCAP, i.pesoxunidad as PESOUNI, um.nombre_unidad as UNIXPESO, i.regulado as REGULADO, i.cestack_basica as CESTABASICA, i.bcv as BCV, ag.precio, ag.costo
                    FROM selectrapyme.item i,
                    selectrapyme.marca mc,
                    selectrapyme.unidad_medida um,
                    selectrapyme_central.articulos_siga ag
                    WHERE mc.id = i.id_marca
                    AND um.id = i.unidadxpeso
                    AND i.codigo_barras = ag.cod_bar

                    ";*/




                    $sql = "SELECT i.cod_item as CODEITEM,
                    i.codigo_barras as CODE,
                    TRIM(i.descripcion1) as nombre_producto,
                    mc.marca as MARCAP,
                    i.tipo_almacenamiento AS ALMACENAMIENTO,
                    i.cantidad_bulto AS CANTIDADBULTO,
                    i.pesoxunidad as PESOUNI,
                    um.nombre_unidad as UNIXPESO,
                    i.regulado as REGULADO, i.cestack_basica as CESTABASICA,
                    i.bcv as BCV,  i.coniva1 AS PRECIO, i.costo_actual AS COSTO,
                    i.usuario_creacion
                        FROM $BD_selectrapyme.item i
                        left join $BD_selectrapyme.grupo gp on gp.cod_grupo = i.cod_grupo
                        left join $BD_selectrapyme.marca mc on mc.id = i.id_marca
                        left join $BD_selectrapyme.unidad_medida um on um.id = i.unidadxpeso
                        WHERE fecha_creacion>='$desde 00:00:00' and fecha_creacion<='$hasta 23:00:00' AND i.estatus='A'";


                    /*if($punto!="0"){
                        $sql.=" and v.codigo_siga='$punto'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }*/
                    /*if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }*/
                    /*if($categoria!="0"){

                        $sql.=" and gp.cod_grupo='$categoria'";

                    }*/
                    /*if($tipo_punto!=""){

                        $sql.=" and ptp.id_tipo ='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }*/

                   /* if($art_siscolp_siga=="IN"){
                        $sql.=" AND i.codigo_barras IN (SELECT ag.cod_bar FROM selectrapyme_central.articulos_siga ag)";
                    }

                    if($art_siscolp_siga=="OUT"){
                        $sql.=" AND i.codigo_barras NOT IN (SELECT ag.cod_bar FROM selectrapyme_central.articulos_siga ag)";
                    }

                    if($producto!="0"){
                        $sql.=" AND i.codigo_barras='$producto'";
                    }

                    if($tipo_almacenamiento!=""){
                        $sql.=" AND i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }

                    if($marca_producto!="0"){
                        $sql.=" AND mc.id ='$marca_producto'";
                    }*/


                    //$sql.=" GROUP BY CODE, nombre_producto";


                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);

                    //echo $sql; exit();
                    if(count($reporte)>0){
                        //Exportar a hoja de calculo
                    ?>
                       <div style="padding:10px;width:180px;color:#5084A9;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 100px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php

                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="16" align="center"><b><font color="white">PRODUCTOS CREADOS DESDE <?php echo $desde;?> </font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="5%" align="center"><b><font color="white">SKU</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="35%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">MARCA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">TIPO ALMACENAMIENTO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CANTIDAD POR BULTO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PESO POR UNIDAD</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNIDAD DE MEDIDA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">REGULADO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CESTA BÁSICA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">BCV</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PRECIO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">COSTO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">USUARIO CREACION</font></b></td>
                        </tr>

                    <?php
                        //$listaCodeBarras="";
                        $i="0";
                        $j=1;
                        foreach ($reporte as $lista ) {
                            $totalPesoUnidad+=$lista["PESOUNI"];
                            $totalProductos+=$lista["CANTPRODUCTOS"];
                            /*$totalTone+=$lista["TONELAJE"];
                            $totalSinIva+=$lista["TOTALSINIVA"];
                            $totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            $listaCodeBarras.=$lista["CODE"].",";*/

                            //$totalSeco+=$lista['CANTSECO'];

                     ?>
                        <tr>
                            <td width="5%" align="center"><?php echo $j; ?></td>
                            <td width="5%" align="center"><?php echo $lista["CODEITEM"]; ?></td>
                            <td width="5%" align="center"><?php echo $lista["SIGA"]; ?></td>
                            <td width="10%" align="center"><?php echo $lista["CODE"]; ?></td>
                            <td width="35%" align="center"><?php echo utf8_encode($lista["nombre_producto"]); ?></td>
                            <td width="5%" align="center"><?php echo utf8_encode($lista["MARCAP"]); ?></td>
                            <td width="5%" align="center"><?php echo utf8_encode($lista["ALMACENAMIENTO"]); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["CANTIDADBULTO"]); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["PESOUNI"]); ?></td>
                            <td width="10%" align="center"><?php echo $lista["UNIXPESO"]; ?></td>

                            <?php
                                if ($lista["REGULADO"]==0) {?>
                                    <td align="center">X</td>
                                <?php
                                } else { ?>
                                    <td align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <?php
                                if ($lista["CESTABASICA"]==0) {?>
                                    <td align="center">X</td>
                                <?php
                                } else { ?>
                                    <td align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <?php
                                if ($lista["BCV"]==0) {?>
                                    <td align="center">X</td>
                                <?php
                                } else { ?>
                                    <td align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <td width="5%" align="center"><?php echo number_format($lista["PRECIO"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["COSTO"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo $lista["usuario_creacion"]; ?></td>

                            <!--<td width="10%" align="center"><?php //echo number_format($lista["TONELAJE"], 3, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["TOTALSINIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["PRICESELL"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php //echo $lista["rate"] ?></td>-->
                        </tr>
                    <?php
                         $i++;
                         $j++;
                        }
                        //$listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>
                    <tr>
                        <td colspan="16"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%" align="center"></td>
                        <td width="5%"></td>
                        <td width="5%" align="center"><b>TOTAL PRODUCTOS</b></td>
                        <td width="5%" align="center"><b><?php echo $i; ?></b></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                    </tr>

                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                    }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                    }

            // termina el else de las fechas iguales
            break;

            case "reporte_SiscolpSiga":
                    //$punto = $_GET["punto"];
                    //$estado = $_GET["estados"];
                    $producto = $_GET["producto"];
                    //$categoria = $_GET["categoria"];
                    //$desde = $_GET["desde"];
                    //$hasta = $_GET["hasta"];
                    //$tipo_punto = $_GET["tipo_punto"];
                    $tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    $art_siscolp_siga = $_GET["art_siscolp_siga"];
                    $marca_producto = $_GET["marca_producto"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{

                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;


                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    //ORIGINAL
                   /*$sql="SELECT i.cod_item as CODEITEM,i.codigo_barras as CODE, TRIM(i.descripcion1) as nombre_producto, mc.marca as MARCAP, i.pesoxunidad as PESOUNI, um.nombre_unidad as UNIXPESO, i.regulado as REGULADO, i.cestack_basica as CESTABASICA, i.bcv as BCV, ag.precio, ag.costo
                    FROM selectrapyme.item i,
                    selectrapyme.marca mc,
                    selectrapyme.unidad_medida um,
                    selectrapyme_central.articulos_siga ag
                    WHERE mc.id = i.id_marca
                    AND um.id = i.unidadxpeso
                    AND i.codigo_barras = ag.cod_bar

                    ";*/


                    $sql = "SELECT i.cod_item as CODEITEM,
                    ag.codigo_siga AS SIGA,
                    i.codigo_barras as CODE,
                    TRIM(i.descripcion1) as nombre_producto,
                    mc.marca as MARCAP,
                    i.tipo_almacenamiento AS ALMACENAMIENTO,
                    i.cantidad_bulto AS CANTIDADBULTO,
                    i.pesoxunidad as PESOUNI,
                    um.nombre_unidad as UNIXPESO,
                    i.regulado as REGULADO, i.cestack_basica as CESTABASICA,
                    i.bcv as BCV, ag.precio AS PRECIO, ag.costo AS COSTO
                        FROM $BD_selectrapyme.item i
                        LEFT JOIN $BD_central.articulos_siga ag on i.codigo_barras = ag.cod_bar
                        left join $BD_selectrapyme.grupo gp on gp.cod_grupo = i.cod_grupo
                        left join $BD_selectrapyme.marca mc on mc.id = i.id_marca
                        left join $BD_selectrapyme.unidad_medida um on um.id = i.unidadxpeso
                        WHERE i.id_item IS NOT NULL
                        ";

                    /*if($punto!="0"){
                        $sql.=" and v.codigo_siga='$punto'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }*/
                    /*if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }*/
                    /*if($categoria!="0"){

                        $sql.=" and gp.cod_grupo='$categoria'";

                    }*/
                    /*if($tipo_punto!=""){

                        $sql.=" and ptp.id_tipo ='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }*/

                    if($art_siscolp_siga=="IN"){
                        $sql.=" AND i.codigo_barras IN (SELECT ag.cod_bar FROM selectrapyme_central.articulos_siga ag)";
                    }

                    if($art_siscolp_siga=="OUT"){
                        $sql.=" AND i.codigo_barras NOT IN (SELECT ag.cod_bar FROM selectrapyme_central.articulos_siga ag)";
                    }

                    if($producto!="0"){
                        $sql.=" AND i.codigo_barras='$producto'";
                    }

                    if($tipo_almacenamiento!=""){
                        $sql.=" AND i.tipo_almacenamiento ='$tipo_almacenamiento'";
                    }

                    if($marca_producto!="0"){
                        $sql.=" AND mc.id ='$marca_producto'";
                    }


                    $sql.=" GROUP BY CODE, nombre_producto";


                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="15" align="center"><b><font color="white">REPORTE DE PRODUCTOS TOTALES: </font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="5%" align="center"><b><font color="white">SKU</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="35%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">MARCA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">TIPO ALMACENAMIENTO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CANTIDAD POR BULTO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PESO POR UNIDAD</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNIDAD DE MEDIDA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">REGULADO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CESTA BÁSICA</font></b></td>
                            <td width="5%" align="center"><b><font color="white">BCV</font></b></td>
                            <td width="5%" align="center"><b><font color="white">PRECIO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">COSTO</font></b></td>
                        </tr>

                    <?php
                        //$listaCodeBarras="";
                        $i="0";
                        $j=1;
                        foreach ($reporte as $lista ) {
                            $totalPesoUnidad+=$lista["PESOUNI"];
                            $totalProductos+=$lista["CANTPRODUCTOS"];
                            /*$totalTone+=$lista["TONELAJE"];
                            $totalSinIva+=$lista["TOTALSINIVA"];
                            $totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            $listaCodeBarras.=$lista["CODE"].",";*/

                            //$totalSeco+=$lista['CANTSECO'];

                     ?>
                        <tr>
                            <td width="5%" align="center"><?php echo $j; ?></td>
                            <td width="5%" align="center"><?php echo $lista["CODEITEM"]; ?></td>
                            <td width="5%" align="center"><?php echo $lista["SIGA"]; ?></td>
                            <td width="10%" align="center"><?php echo $lista["CODE"]; ?></td>
                            <td width="35%" align="center"><?php echo utf8_encode($lista["nombre_producto"]); ?></td>
                            <td width="5%" align="center"><?php echo utf8_encode($lista["MARCAP"]); ?></td>
                            <td width="5%" align="center"><?php echo utf8_encode($lista["ALMACENAMIENTO"]); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["CANTIDADBULTO"]); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["PESOUNI"]); ?></td>
                            <td width="10%" align="center"><?php echo $lista["UNIXPESO"]; ?></td>

                            <?php
                                if ($lista["REGULADO"]==0) {?>
                                    <td align="center">X</td>
                                <?php
                                } else { ?>
                                    <td align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <?php
                                if ($lista["CESTABASICA"]==0) {?>
                                    <td align="center">X</td>
                                <?php
                                } else { ?>
                                    <td align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <?php
                                if ($lista["BCV"]==0) {?>
                                    <td align="center">X</td>
                                <?php
                                } else { ?>
                                    <td align="center">&#10004;</td>
                                <?php
                                }

                            ?>
                            <td width="5%" align="center"><?php echo number_format($lista["PRECIO"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["COSTO"], 2, ',', '.'); ?></td>

                            <!--<td width="10%" align="center"><?php //echo number_format($lista["TONELAJE"], 3, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["TOTALSINIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["PRICESELL"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php //echo $lista["rate"] ?></td>-->
                        </tr>
                    <?php
                         $i++;
                         $j++;
                        }
                        //$listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>
                    <tr>
                        <td colspan="15"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%" align="center"></td>
                        <td width="5%"></td>
                        <td width="5%" align="center"><b>TOTAL PRODUCTOS</b></td>
                        <td width="5%" align="center"><b><?php echo $i; ?></b></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                    </tr>

                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                    }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                    }

            }// termina el else de las fechas iguales
            break;

            case "reporte_productoMasVendido":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    //$producto = $_GET["producto"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $indices = $_GET["indices"];
                    $codigo_barras = $_GET["codigo_barras"];
                    //$tipo_punto = $_GET["tipo_punto"];
                    //$tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    //$marca_producto = $_GET["marca_producto"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);


                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{

                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;




                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    //ORIGINAL
                   /*$sql="SELECT v.CODE,(i.pesoxunidad*um.transformar*SUM(v.UNITS))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto,
                   SUM(v.UNITS) as UNITS, SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,
                   SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    WHERE v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    $sql = "SELECT v.CODE as CODE,
                    v.nombre_producto as nombre_producto,
                    SUM(v.UNITS) as UNITS,
                    COUNT(v.id_tickets) as TICKETS
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    LEFT JOIN item i on i.codigo_barras=v.CODE
                    WHERE v.datenew_ticketlines BETWEEN '$desde 00:00:00' and '$hasta 23:59:59'

                    ";

                    if($punto!="0"){
                        $sql.=" and v.codigo_siga='$punto'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }
                    if($indices=='regulado'){

                        $sql.=" and i.regulado =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='cestack_basica'){

                        $sql.=" and i.cestack_basica =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='bcv'){

                        $sql.=" and i.bcv =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($indices=='sae'){

                        $sql.=" and i.sae =1";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }
                    if($codigo_barras!=''){

                        $sql.=" and v.CODE ='".$codigo_barras."'";
                        //$sql_cod_barras.=" and mc.marca_producto ='$marca_producto'";
                    }



                    $sql.=" GROUP BY v.CODE ORDER BY TICKETS DESC";

                    //
                    //echo $sql; exit();
                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <!-- <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='40' height='40' /></div> -->
                       <div style="padding:10px;width:80px;color:white;cursor:pointer;font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='30' height='30' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="5" align="center"><b><font color="white">REPORTE DE PRODUCTOS MAS VENDIDOS DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="5%" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="10%" align="center"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="35%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TICKETS</font></b></td>
                            <!--<td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>-->
                        </tr>

                    <?php
                    $i=1;
                        $listaCodeBarras="";

                        foreach ($reporte as $lista ) {
                            /*$totalUni+=$lista["UNITS"];
                            $totalTone+=$lista["TONELAJE"];
                            $totalSinIva+=$lista["TOTALSINIVA"];
                            $totalConIva+=$lista["TOTALCONIVA"];
                            $totalPdSinIva+=$lista["PRICESELL"];//Nuevo, para imprimir al final del total
                            $listaCodeBarras.=$lista["CODE"].",";
                            */

                            //$totalSeco+=$lista['CANTSECO'];

                     ?>
                        <tr>
                            <td width="5%" align="center"><?php echo $i; ?></td>
                            <td width="10%" align="center"><?php echo $lista["CODE"] ?></td>
                            <td width="30%" align="center"><?php echo utf8_encode($lista["nombre_producto"])  ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TICKETS"]); ?></td>
                            <!--<td width="10%" align="center"><?php //echo number_format($lista["TONELAJE"], 3, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["PRICESELL"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php //echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php //echo $lista["rate"] ?></td>                -->
                        </tr>

                    <?php
                        $i++;
                        }
                        $listaCodeBarras = substr ($listaCodeBarras, 0, strlen($listaCodeBarras) - 1);
                    ?>
                     <!--<tr>
                            <td width="10%"></td>
                            <td width="30%" align="center"><b>TOTALES</b></td>
                            <td width="5%" align="center"><b><?php //echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?php //echo number_format($totalTone, 3, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?php //echo number_format($totalSinIva, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"><b><?php //echo number_format($totalConIva, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?/*php echo number_format($totalPdSinIva, 2, ',', '.'); */?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                    </tr>-->
                    <!--<tr>
                        <td colspan="9"><hr color="black" size="2" /></td>
                    </tr>-->


                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteProducto.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            }// termina el else de las fechas iguales

            break;

            case "reporte_votantesPdval":
                    $gerencia = $_GET["gerencia"];
                    $estado = $_GET["estados"];
                    //$producto = $_GET["producto"];
                    //$categoria = $_GET["categoria"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    //$tipo_punto = $_GET["tipo_punto"];
                    //$tipo_almacenamiento = $_GET["tipo_almacenamiento"];
                    //$marca_producto = $_GET["marca_producto"];

                    $anno_des=substr($_GET["desde"],2,2);
                    $anno_has=substr($_GET["hasta"],2,2);
                    $mes_des=substr($_GET["desde"],5,2);
                    $mes_has=substr($_GET["hasta"],5,2);

                    if($anno_des!=$anno_has || $mes_des!=$mes_has){

                            echo " Los Años Y Meses Deben Ser Iguales"; exit();
                    }else{

                   $ventas_mes_anno="ventas_".$mes_des."_".$anno_des;




                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    //ORIGINAL
                   /*$sql="SELECT v.CODE,(i.pesoxunidad*um.transformar*SUM(v.UNITS))/1000000 as TONELAJE,
                   TRIM(i.descripcion1) as nombre_producto,
                   SUM(v.UNITS) as UNITS, SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,
                   SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,
                   v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate
                    FROM $BD_central.$ventas_mes_anno v
                    INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                    INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                    INNER JOIN $BD_selectrapyme.item i on i.codigo_barras=v.code
                    INNER JOIN $BD_selectrapyme.unidad_medida um on i.unidadxpeso = um.id
                    INNER JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    INNER JOIN $BD_selectrapyme.marca mc on mc.id = i.id_marca
                    WHERE v.datenew_ticketlines BETWEEN '$desde' and '$hasta'
                    ";*/

                    $sql = "SELECT * FROM $BD_central.votacion v

                    WHERE v.hora_vota BETWEEN '$desde' and '$hasta'

                    ";

                    if($gerencia!="0"){
                        $sql.=" and v.gerencia='$gerencia'";
                        //$sql_cod_barras.=" and v.codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and v.estado_centro='$estado'";
                        //$sql_cod_barras.=" and pv.codigo_estado_punto='$estado'";
                    }

                    $sql.="ORDER BY estado_centro, gerencia";


                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);


                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="6" align="center"><b><font color="white">REPORTE DE VOTANTES PDVAL DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="5%"><b><font color="white">C&eacute;dula</font></b></td>
                            <td width="20%" align="center"><b><font color="white">Nombres y Apellidos</font></b></td>
                            <td width="20%" align="center"><b><font color="white">Gerencia</font></b></td>
                            <td width="10%" align="center"><b><font color="white">Estado donde Vota</font></b></td>
                            <td width="10%" align="center"><b><font color="white">Miembro de Mesa</font></b></td>
                            <td width="10%" align="center"><b><font color="white">Fecha de Votación</font></b></td>
                            <!--<td width="10%" align="center"><b><font color="white">TONELADAS</font></b></td>-->
                        </tr>

                    <?php
                        $listaCodeBarras="";
                        $i="0";
                        foreach ($reporte as $lista ) {

                     ?>
                        <tr>
                            <td width="5%" align="center"><?php echo $lista["nacionalidad_emp"].$lista["cedula_emp"] ?></td>
                            <td width="20%" align="center"><?php echo $lista["nombre_emp"]." ".$lista["apellido_emp"] ?></td>
                            <td width="20%" align="center"><?php echo $lista["gerencia"] ?></td>
                            <td width="10%" align="center"><?php echo $lista["estado_centro"] ?></td>
                            <td width="10%" align="center"><?php echo $lista["miembro"] ?></td>
                            <td width="10%" align="center"><?php echo $lista["hora_vota"] ?></td>
                        </tr>

                    <?php
                        $i++;
                        }
                    ?>

                    <tr>
                        <td colspan="14"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="5%" align="center"></td>
                        <td width="5%" align="center"><b>VOTARON</b></td>
                        <td width="5%" align="center"><b><?php echo $i; ?></b></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="5%" align="center"></td>
                        <td width="5%" align="center"><b>FALTAN POR VOTAR</b></td>
                        <td width="5%" align="center"><b>
                            <?php

                                $sql2 = "SELECT (COUNT(*)-$i) AS CONTADORVOTANTES FROM $BD_central.votacion vt
                                WHERE vt.id
                                ";

                                if($gerencia!="0"){
                                    $sql2.=" AND vt.gerencia='$gerencia'";
                                }
                                if($estado!="0"){
                                    $sql2.=" AND vt.estado_centro='$estado'";
                                }

                                //echo $sql2;

                                $reporte2 = $conn->ObtenerFilasBySqlSelect($sql2);
                                $cantidadVotantes = count($reporte2);
                                foreach ($reporte2 as $lista) {
                                    $totalTone+=$lista["CONTADORVOTANTES"];
                                }

                                echo $lista["CONTADORVOTANTES"];
                            ?>
                        </b></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                    </tr>

                    </table>
                    <?php
                    $_SESSION['contenido'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['contenido'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                var titulo_reporte='Reporte Votantes PDVAL';
                                window.location.href = "imprimirReporte.php?titulo="+titulo_reporte;
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            }// termina el else de las fechas iguales

            break;

            case "reporte_inventarioCentral":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    //$producto = $_GET["producto"];
                    $fecha = $_GET["fecha"];
                    $ubicacion = $_GET["ubicacion"];
                    $categoria = $_GET["categoria"];
                    $tipo_punto = $_GET["tipo_punto"];

                    $anno_des=substr($_GET["fecha"],2,2);
                    $mes_des=substr($_GET["fecha"],5,2);
                    $des_punto = substr($_GET['punto'],3,3);

                    $inventario_mes_anno="inventario_".$mes_des."_".$anno_des;
                    $codigo_barras = $_GET["codigo_barras"];

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*$sql="SELECT  i.* ,p.descripcion1,pv.codigo_estado_punto,pv.nombre_punto
                    FROM $BD_central.inventario i
                    INNER JOIN $BD_selectrapyme.item p on  i.codigo_barra=p.codigo_barras
                    INNER JOIN $BD_central.puntos_venta pv on i.siga=substring(pv.codigo_siga_punto,4)
                    where fecha='$fecha'";*/

                    $sql="SELECT codigo_barra, ubicacion, sum(i.cantidad) as cantidad, codigo_siga_punto, nombre_punto,
                    i.fecha,descripcion1,
                    (it.pesoxunidad*um.transformar*SUM(i.cantidad))/1000000 as TONELAJE,
                    mc.marca as marca, um.nombre_unidad as nombre_unidad, it.pesoxunidad as pesoxunidad, cantidad_bulto, it.precio1, it.iva
                    FROM $BD_central.$inventario_mes_anno i
                    INNER JOIN $BD_central.puntos_venta pv ON i.siga = SUBSTRING( pv.codigo_siga_punto, 4 )
                    LEFT JOIN $BD_selectrapyme.item it ON i.codigo_barra = it.codigo_barras
                    LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                    LEFT JOIN $BD_selectrapyme.unidad_medida um on it.unidadxpeso = um.id
                    LEFT JOIN $BD_selectrapyme.marca mc ON mc.id = it.id_marca
                    WHERE i.fecha = '".$fecha."'";

                    if($estado != 0)
                    {
                        $sql .= "  and pv.codigo_estado_punto = '$estado'";
                    }

                    if($punto != 0)
                    {
                        $sql .= " AND i.siga = '".$des_punto."' ";
                    }

                    if($ubicacion!="0")
                    {
                        $sql.=" and i.ubicacion='$ubicacion'";
                    }

                    if($categoria!="0"){

                        $sql.=" and it.cod_grupo='$categoria'";
                        //$sql_cod_barras.=" and v.category='$categoria'";
                    }
                    if($tipo_punto!=""){

                        $sql.=" and ptp.id_tipo ='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }

                    if($tipo_punto!=""){

                        $sql.=" and ptp.id_tipo ='$tipo_punto'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }

                    if($codigo_barras!=""){

                        $sql.=" and i.codigo_barra ='$codigo_barras'";
                        //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                    }

                   // if(($punto == 0) || ($estado == 0))
                    //{
                        $sql.= " GROUP BY i.codigo_barra, ubicacion ";
                    //}

                    $sql.= "  ORDER BY ubicacion, it.descripcion1";

                    //echo $sql; exit(); 

                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);

                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="11" align="center"><b><font color="white">REPORTE DE INVENTARIO DEL: <?php echo fecha($fecha); ?></font></b></td>
                        </tr>
                        <?php if($codigo_barras!=""){ ?>
                            <tr bgcolor="#5084A9">
                            <td colspan="11" align="center"><b><font color="white">PRODUCTO: <?php echo $codigo_barras; ?></font></b></td>
                        </tr>
                        <?php } ?>
                        <tr bgcolor="#5084A9">
                            <td width="50px" align="center"><b><font color="white">N&deg;</font></b></td>
                            <td width="150px" ><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="200px" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="100px" align="center"><b><font color="white">UBICACION</font></b></td>
                            <td width="100px" align="center"><b><font color="white">UNIDADES</font></b></td>
                            <td width="50px" align="center"><b><font color="white">BULTOS</font></b></td>
                            <td width="80px" align="center"><b><font color="white">TONELAJE</font></b></td>
                            <td width="100px" align="center"><b><font color="white">PRECIO S/IVA</font></b></td>
                            <td width="50px" align="center"><b><font color="white">IVA</font></b></td>
                            <td width="160px" align="center"><b><font color="white">TOTAL S/IVA</font></b></td>
                            <td width="160px" align="center"><b><font color="white">TOTAL C/IVA</font></b></td>

                           
                        </tr>
                        </table>
                        <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        $i=1;
                        $totalConIva=0;
                        $totalpreciosivaunidades=0;
                        $totalpreciocivaunidades=0;
                        foreach ($reporte as $lista ) {
                        //     $totalUni+=$lista["UNITS"];
                        //     $totalSinIva+=$lista["TOTALSINIVA"];
                        //     $totalConIva+=$lista["TOTALCONIVA"];
                                $preciociva=$lista["precio1"]+$lista["precio1"]*$lista["iva"]/100;

                                $preciosivaunidades=$lista["precio1"]*$lista["cantidad"];
                                $preciocivaunidades=$preciociva*$lista["cantidad"];

                                $totalTone+=$lista["TONELAJE"];
                                $totalConIva=$totalConIva+$precioivaunidades;
                                $totalpreciosivaunidades=$totalpreciosivaunidades+$preciosivaunidades;
                                $totalpreciocivaunidades=$totalpreciocivaunidades+$preciocivaunidades;
                                if($lista["cantidad_bulto"]==0){
                                	$bulto=0;
                                }else{
                                	$bulto=$lista["cantidad"]/$lista["cantidad_bulto"];
                                }
                                

                     ?>
                        <tr>
                            <td width="50px" align="center"><?php echo $i; ?></td>
                            <td width="150px" align="center"><?php echo $lista["codigo_barra"] ?></td>
                            <td width="200px" align="center">
                                <?php
                                echo $lista["descripcion1"];
                                //echo utf8_encode($lista["nombre_producto"]);
                                echo " - ".$lista["marca"]." ";
                                echo number_format($lista["pesoxunidad"]).$lista["nombre_unidad"];
                                ?>
                            </td>
                            <td  width="100px" align="center"><?php echo $lista["ubicacion"] ?></td>
                            <td  width="100px" align="center"><?php echo number_format($lista["cantidad"], 2, ',', '.'); ?></td>
                            <td  width="50px" align="center"><?php echo number_format($bulto, 0, ',', '.'); ?></td>
                            <td  width="80px" align="center"><?php echo number_format($lista["TONELAJE"], 3, ',', '.'); ?></td>
                            <td  width="100px" align="center"><?php echo number_format($lista["precio1"], 2, ',', '.'); ?></td>
                            <td  width="50px" align="center"><?php echo number_format($lista["iva"], 2, ',', '.'); ?></td>
                            <td  width="160px" align="center"><?php echo number_format($preciosivaunidades, 2, ',', '.'); ?></td>
                            <td  width="160px" align="center"><?php echo number_format($preciocivaunidades, 2, ',', '.'); ?></td>
                            
                            
                        </tr>


                    <?php
                        $i++;
                        }
                    ?>
                    <tr>
                        <td colspan="11"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                            <td align="center"><b></b></td>
                            <td  align="center"><b></b></td>
                            <td  align="center"><b>TOTALES</b></td>
                            <td  align="center"><b></b></td>
                            <td  align="center"><b></b></td>
                            <td  align="center"><b></b></td>
                            <td  align="center"><b><?php echo number_format($totalTone, 3, ',', '.'); ?></b></td>
                            <td  align="center"><b></b></td>
                            <td  align="center"><b></b></td>
                            <td  align="center"><b><?php echo number_format($totalpreciosivaunidades, 3, ',', '.'); ?></b></td>
                            <td  align="center"><b><?php echo number_format($totalpreciocivaunidades, 3, ',', '.'); ?></b></td>

                    </tr>
                    </table>
                    </div>
                    <?php
                    $_SESSION['inventario'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['inventario'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteInventario.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            break;
            case "reporte_inventarioCentralEstados":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    //$producto = $_GET["producto"];
                    $fecha = $_GET["fecha"];
                    $ubicacion = $_GET["ubicacion"];
                    $categoria = $_GET["categoria"];
                    $tipo_punto = $_GET["tipo_punto"];
                    $codigo_barras = $_GET["codigo_barras"];

                    $anno_des=substr($_GET["fecha"],2,2);
                    $mes_des=substr($_GET["fecha"],5,2);
                    $des_punto = substr($_GET['punto'],3,3);


                    $inventario_mes_anno="inventario_".$mes_des."_".$anno_des;

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*$sql="SELECT  i.* ,p.descripcion1,pv.codigo_estado_punto,pv.nombre_punto
                    FROM $BD_central.inventario i
                    INNER JOIN $BD_selectrapyme.item p on  i.codigo_barra=p.codigo_barras
                    INNER JOIN $BD_central.puntos_venta pv on i.siga=substring(pv.codigo_siga_punto,4)
                    where fecha='$fecha'";*/

                    $sql="SELECT  nombre_estado, codigo_estado
                    FROM $BD_central.estados
                    ORDER BY nombre_estado asc ";

                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);

                    if(count($reporte)>0){
                    ?>
                       <div style="padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="14" align="center"><b><font color="white">REPORTE DE INVENTARIO DEL: <?php echo $fecha; ?> POR ESTADOS</font></b></td>
                        </tr>
                        <?php if($codigo_barras!=""){ ?>
                            <tr bgcolor="#5084A9">
                            <td colspan="14" align="center"><b><font color="white">PRODUCTO: <?php echo $codigo_barras; ?></font></b></td>
                        </tr>
                        <?php } ?>
                        <tr bgcolor="#5084A9">
                            <td width="3%" align="center"><b><font color="white">N</font></b></td>
                            <td width="30%"><b><font color="white">ESTADOS</font></b></td>
                            <td width="30%" align="center"><b><font color="white">INVENTARIO DISPONIBLE (TON)</font></b></td>
                            <td width="30%" align="center"><b><font color="white">PRECIO SIN IVA</font></b></td>
                            <td width="30%" align="center"><b><font color="white">PRECIO CON IVA</font></b></td>
                            <!--<td width="30%" align="center"><b><font color="white">SIGA</font></b></td>-->
                            <!--<td width="10%" align="center"><b><font color="white">UBICACION</font></b></td>
                            <td width="10%" align="center"><b><font color="white">UNIDADES</font></b></td>
                            <td width="20%" align="center"><b><font color="white">TONELAJE</font></b></td>
                            <td width="10%" align="center"><b><font color="white">FECHA</font></b></td> -->
                        </tr>

                    <?php
                    $i=1;
                        foreach ($reporte as $lista ) {
                        //     $totalUni+=$lista["UNITS"];
                        //     $totalSinIva+=$lista["TOTALSINIVA"];
                        //     $totalConIva+=$lista["TOTALCONIVA"];


                            $cad1=$cad2=$cad3=$cad4='';
                            if($ubicacion!="0")
                            {
                                $cad1 = " and i.ubicacion='$ubicacion' ";
                            }

                            if($categoria!="0"){

                                $cad2 =" and it.cod_grupo='$categoria' ";
                                //$sql_cod_barras.=" and v.category='$categoria'";
                            }
                            if($tipo_punto!=""){

                                $cad3 =" and ptp.id_tipo ='$tipo_punto' ";
                                //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                            }

                            if($codigo_barras!=""){

                                $cad4 =" and i.codigo_barra ='$codigo_barras' ";
                                //$sql_cod_barras.=" and ptp.id_tipo ='$tipo_punto'";
                            }

                            $sql="SELECT  T1.estado, sum( T1.cantidadx ) as canti, sum( T1.TONELAJEx ) as ton, sum( T1.PRECIOSINIVA) PRECIO, sum( T1.PRECIOCONIVA) PRECIO2
                                FROM ( SELECT est.nombre_estado as estado, i.codigo_barra, sum(i.cantidad) as cantidadx,
                                (it.pesoxunidad*um.transformar*SUM(i.cantidad))/1000000 as TONELAJEx, sum(it.precio1*i.cantidad) as PRECIOSINIVA,
                                sum((it.precio1+((it.precio1*it.iva)/100))*i.cantidad) as PRECIOCONIVA
                            FROM $BD_central.$inventario_mes_anno i
                            LEFT JOIN $BD_central.puntos_venta pv ON i.siga = SUBSTRING( pv.codigo_siga_punto, 4 )
                            LEFT JOIN $BD_selectrapyme.item it ON i.codigo_barra = it.codigo_barras
                            LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                            LEFT JOIN $BD_selectrapyme.unidad_medida um on it.unidadxpeso = um.id
                            INNER JOIN $BD_central.estados est on est.codigo_estado = pv.codigo_estado_punto
                            WHERE i.fecha = '".$fecha."' and pv.codigo_estado_punto ='$lista[codigo_estado]' $cad1 $cad2 $cad3 $cad4
                            GROUP BY i.codigo_barra
                            ) as T1
                            GROUP BY T1.estado
                            ORDER BY estado asc ";
                            //echo $sql; exit();
                            $reporte2 = $conn->ObtenerFilasBySqlSelect($sql);

                            foreach ($reporte2 as $lista2 ) {
                            $totalTone+=$lista2["ton"];
                            $totalPrecio+=$lista2["PRECIO"];
                            $totalPrecio2+=$lista2["PRECIO2"];

                     ?>
                        <tr>
                        <td width="3%" align="center"><?php echo $i; ?></td>
                            <td width="30%"><?php echo $lista2["estado"] ?></td>
                            <td width="30%" align="center"><?php echo number_format($lista2["ton"], 2, ',', '.'); ?></td>
                            <td width="30%" align="center"><?php echo number_format($lista2["PRECIO"], 2, ',', '.'); ?></td>
                            <td width="30%" align="center"><?php echo number_format($lista2["PRECIO2"], 2, ',', '.'); ?></td>
                            <!--<td width="30%" align="center"><?php echo $lista["nombre_punto"]  ?></td>
                            <td width="10%" align="center"><?php echo $lista["ubicacion"] ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["cantidad"]); ?></td>
                            <td width="20%" align="center"><?php echo number_format($lista["TONELAJE"]); ?></td>
                            <td width="10%" align="center"><?php echo $lista["fecha"] ?></td>     -->
                        </tr>


                    <?php
                        $i++;
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="4"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                        <td width="3%" align="center"></td>
                        <td width="30%" align="center"><b>TOTAL</b></td>
                        <td width="30%" align="center"><b><?php echo number_format($totalTone, 2, ',', '.'); ?></b></td>
                        <td width="30%" align="center"><b><?php echo number_format($totalPrecio, 2, ',', '.'); ?></b></td>
                        <td width="30%" align="center"><b><?php echo number_format($totalPrecio2, 2, ',', '.'); ?></b></td>
                            <!--<td width="10%" align="center"><b></b></td>
                            <td width="10%" align="center"><b></b></td>
                            <td width="20%" align="center"><b></b></td>
                            <td width="10%" align="center"><b></b></td>-->

                    </tr>
                    </table>
                    <?php
                    $_SESSION['inventario'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['inventario'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteInventario.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            break;

            case "reporte_inventarioCentralEstab":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    //$producto = $_GET["producto"];
                    $fecha = $_GET["fecha"];
                    $ubicacion = $_GET["ubicacion"];
                    $categoria = $_GET["categoria"];
                    $tipo_punto = $_GET["tipo_punto"];
                    $codigo_barra = $_GET["codigo_barras"];

                    $anno_des=substr($_GET["fecha"],2,2);
                    $mes_des=substr($_GET["fecha"],5,2);
                    $des_punto = substr($_GET['punto'],3,3);

                    $codigo_barras = $_GET["codigo_barras"];

                    $inventario_mes_anno="inventario_".$mes_des."_".$anno_des;

                    $BD_central=DB_REPORTE_CENTRAL;
                    $BD_selectrapyme=DB_SELECTRA_FAC;

                    /*$sql="SELECT  i.* ,p.descripcion1,pv.codigo_estado_punto,pv.nombre_punto
                    FROM $BD_central.inventario i
                    INNER JOIN $BD_selectrapyme.item p on  i.codigo_barra=p.codigo_barras
                    INNER JOIN $BD_central.puntos_venta pv on i.siga=substring(pv.codigo_siga_punto,4)
                    where fecha='$fecha'";*/

                    $sql="SELECT  codigo_siga_punto, nombre_punto
                    FROM $BD_central.puntos_venta
                    ORDER BY codigo_estado_punto asc ";

                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);

                    if(count($reporte)>0){
                    ?>
                       <div style="padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="14" align="center"><b><font color="white">REPORTE DE INVENTARIO DEL: <?php echo $fecha; ?> POR ESTABLECIMIENTOS</font></b></td>
                        </tr>
                        
                        <?php if($codigo_barra!=""){ ?>
                            <tr bgcolor="#5084A9">
                            <td colspan="14" align="center"><b><font color="white">PRODUCTO: <?php echo $codigo_barra; ?></font></b></td>
                        </tr>
                        <?php } ?>
                        <tr bgcolor="#5084A9">
                            <td width="50px" align="center"><b><font color="white">N</font></b></td>
                            <td width="450px" align="center"><b><font color="white">ESTABLECIMIENTOS</font></b></td>
                            <td width="350px" align="center"><b><font color="white">INVENTARIO DISPONIBLE (TON)</font></b></td>
                        </tr>
                        </table>
                        <div style="max-height: 400px; overflow: auto;overflow: scroll;">
                    <table border="1" align="center" style="margin-left: 10" cellpadding="1" cellspacing="0">

                    <?php
                        $i=1;
                        foreach ($reporte as $lista ) {
                        //     $totalUni+=$lista["UNITS"];
                        //     $totalSinIva+=$lista["TOTALSINIVA"];
                        //     $totalConIva+=$lista["TOTALCONIVA"];


                            $cad1=$cad2=$cad3=$cad4='';
                            if($ubicacion!="0")
                            {
                                $cad1 = " and i.ubicacion='$ubicacion' ";
                            }

                            if($categoria!="0"){

                                $cad2 =" and it.cod_grupo='$categoria' ";

                            }
                            if($tipo_punto!="0"){

                                $cad3 =" and ptp.id_tipo ='$tipo_punto' ";

                            }

                            if($codigo_barra!=""){

                                $cad4 =" and i.codigo_barra ='$codigo_barra' ";

                            }

                            /*ORIGINAL
                            $sql2="SELECT nombre_punto, sum( T1.cantidadx ) as canti, sum( T1.TONELAJEx ) as ton
                                FROM ( SELECT pv.nombre_punto, i.codigo_barra, sum(i.cantidad) as cantidadx,
                                (it.pesoxunidad*um.transformar*SUM(i.cantidad))/1000000 as TONELAJEx
                            FROM $BD_central.$inventario_mes_anno i
                            LEFT JOIN $BD_central.puntos_venta pv ON i.siga = SUBSTRING( pv.codigo_siga_punto, 4 )
                            LEFT JOIN $BD_selectrapyme.item it ON i.codigo_barra = it.codigo_barras
                            LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                            LEFT JOIN $BD_selectrapyme.unidad_medida um on it.unidadxpeso = um.id
                            WHERE i.fecha = '".$fecha."' and i.siga = pv.'$lista[codigo_siga_punto]' $cad1 $cad2 $cad3
                            GROUP BY i.codigo_barra
                            ) as T1
                            GROUP BY T1.nombre_punto
                            ORDER BY nombre_punto asc ";*/

                            /*QUERY nuevo para pruebas, ya que el anterior no esta funcionando
                            HZ*/
                            $sql2="SELECT nombre_punto, sum( T1.cantidadx ) as canti, sum( T1.TONELAJEx ) as ton
                                FROM ( SELECT pv.nombre_punto, pv.codigo_estado_punto, i.codigo_barra, sum(i.cantidad) as cantidadx,
                                (it.pesoxunidad*um.transformar*SUM(i.cantidad))/1000000 as TONELAJEx
                            FROM $BD_central.$inventario_mes_anno i
                            INNER JOIN $BD_central.puntos_venta pv ON i.siga = SUBSTRING( pv.codigo_siga_punto, 4 )
                            LEFT JOIN $BD_selectrapyme.item it ON i.codigo_barra = it.codigo_barras
                            LEFT JOIN $BD_central.puntos_tipo ptp on ptp.id_tipo = pv.id_tipo
                            LEFT JOIN $BD_selectrapyme.unidad_medida um on it.unidadxpeso = um.id
                            WHERE i.fecha = '".$fecha."'
                            AND pv.codigo_siga_punto='".$lista['codigo_siga_punto']."'
                            $cad1 $cad2 $cad3 $cad4
                            GROUP BY i.codigo_barra
                            ) as T1
                            GROUP BY T1.nombre_punto
                            ORDER BY T1.codigo_estado_punto
                            ";

                            //echo $sql2; exit();

                            $reporte2 = $conn->ObtenerFilasBySqlSelect($sql2);

                            foreach ($reporte2 as $lista2 ) {
                            $totalTone+=$lista2["ton"];

                     ?>
                        <tr>
                            <td width="50px" align="center"><?php echo $i; ?></td>
                            <td width="450px" align="center"><?php echo $lista2["nombre_punto"] ?></td>
                            <td width="350px" align="center"><?php echo number_format($lista2["ton"], 2, ',', '.'); ?></td>
                        </tr>


                    <?php
                        $i++;
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="3"><hr color="black" size="2" /></td>
                    </tr>
                    <tr>
                            <td align="center"></td>
                            <td align="center"><b>TOTAL</b></td>
                            <td align="center"><b><?php echo number_format($totalTone, 2, ',', '.'); ?></b></td>

                    </tr>
                    </table>
                    </div>
                    <?php
                    $_SESSION['inventario'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['inventario'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                // $.ajax({
                                //     type: 'GET',
                                //     data: "opt=imprimirReporteExcel",
                                //     url: '../../libs/php/ajax/ajax.php',
                                //     beforeSend: function() {

                                //     },
                                //     success: function(data) {
                                //         $("#contenido_reporte").empty();

                                //         $("#contenido_reporte").html(data);
                                //     }
                                // });//fin del ajax
                                window.location.href = "imprimirReporteInventario.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            break;
            case "reporte_cestaBasica_per_no_pert":
                    $punto = $_GET["punto"];
                    $estado = $_GET["estados"];
                    $cestaBasica = $_GET["cestaBasica"];
                    $desde = $_GET["desde"];
                    $hasta = $_GET["hasta"];
                    $BD_central=DB_REPORTE_CENTRAL;
                    $sql=" SELECT v.CODE,v.REFERENCE,TRIM(v.nombre_producto) as nombre_producto ,SUM(v.UNITS) as UNITS,SUM(v.PRICESELL*v.UNITS) as TOTALSINIVA,SUM((v.PRICESELL*(t.rate)+v.PRICESELL)*v.UNITS) as TOTALCONIVA,v.PRICESELL,(v.PRICESELL*t.rate) + v.PRICESELL as PRECIOIVA,t.rate FROM $BD_central.ventas v
                           INNER JOIN $BD_central.taxes t on v.taxid_tikestline=t.ID
                           INNER JOIN $BD_central.puntos_venta pv on pv.codigo_siga_punto=v.codigo_siga
                           INNER JOIN selectrapyme_central.productos p on p.codigo_barras=v.CODE
                           WHERE  v.datenew_ticketlines BETWEEN  '$desde' and '$hasta' and p.regulado='$cestaBasica'";

                    if($punto!="0"){
                        $sql.=" and v.codigo_siga='$punto'";
                    }
                    if($estado!="0"){
                        $sql.=" and pv.codigo_estado_punto='$estado'";
                    }
                    $sql.=" GROUP BY v.CODE ORDER BY v.nombre_producto";


                    $reporte = $conn->ObtenerFilasBySqlSelect($sql);
                    if(count($reporte)>0){
                    ?>
                       <div style="background-color:#5084A9;padding:10px;width:80px;color:white;cursor:pointer;  font-weight:bold;margin-bottom: 10px;margin-left: 200px;" id="excelImp"><img src='../../libs/imagenes/ico_export.gif' width='16' height='16' /></div>

                    <?php
                        ob_start();
                    ?>
                    <table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="14" align="center"><b><font color="white">REPORTE DE VENTAS POR PRODUCTO DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="10%"><b><font color="white">CODIGO BARRA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">REFERENCIA</font></b></td>
                            <td width="30%" align="center"><b><font color="white">DESCRIPCION</font></b></td>
                            <td width="5%" align="center"><b><font color="white">UNID</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white">TOTAL CON IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white" >PRECIO UND SIN IVA</font></b></td>
                            <td width="10%" align="center"><b><font color="white" >PRECIO UND CON IVA</font></b></td>
                            <td width="5%" align="center"><b><font color="white" >IVA</font></b></td>
                        </tr>

                    <?php
                        foreach ($reporte as $lista ) {
                            $totalUni+=$lista["UNITS"];
                            $totalSinIva+=$lista["TOTALSINIVA"];
                            $totalConIva+=$lista["TOTALCONIVA"];

                     ?>
                        <tr>
                            <td width="10%"><?php echo $lista["CODE"] ?></td>
                            <td width="10%" align="center"><?php echo $lista["REFERENCE"] ?></td>
                            <td width="30%" align="center"><?php echo utf8_encode($lista["nombre_producto"])  ?></td>
                            <td width="5%" align="center"><?php echo number_format($lista["UNITS"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TOTALSINIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["TOTALCONIVA"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["PRICESELL"], 2, ',', '.'); ?></td>
                            <td width="10%" align="center"><?php echo number_format($lista["PRECIOIVA"], 2, ',', '.'); ?></td>
                            <td width="5%" align="center"><?php echo $lista["rate"] ?></td>
                        </tr>

                    <?php
                        }
                    ?>
                     <tr>
                            <td width="10%"></td>
                            <td width="10%" align="center"></td>
                            <td width="30%" align="center"><b>TOTALES</b></td>
                            <td width="5%" align="center"><b><?php echo number_format($totalUni, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalSinIva, 2, ',', '.');  ?></b></td>
                            <td width="10%" align="center"><b><?php echo number_format($totalConIva, 2, ',', '.'); ?></b></td>
                            <td width="10%" align="center"></td>
                            <td width="10%" align="center"></td>
                            <td width="5%" align="center"></td>
                    </tr>
                    </table>
                    <?php
                    $_SESSION['basicaPerNoPer'] = ob_get_contents();
                    ob_end_clean();

                    echo $_SESSION['basicaPerNoPer'];
                    ?>
                        <script >
                             $("#excelImp").click(function() {
                                window.location.href = "imprimirReportePerNoPer.php";
                            });
                        </script>

                    <?php
                }else{
                    echo "NO SE ENCONTRARON RESULTADOS!!!!";
                }

            break;
              case "imprimirReporteExcel":

                $titulo="Reporte Ventas Diarias";
                header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header ("Cache-Control: no-cache, must-revalidate");
                header ("Pragma: no-cache");
                header ("Content-type: application/x-msexcel");
                header ("Content-Disposition: attachment; filename=\"".$titulo.".xls\"" );
                ?>
                <html>
                <div>hola</div>
                </html>
                <?php
             // echo $_SESSION['contenido'];


            break;
            case "cargarSubrubro":
              // cargar el select dependiente de subrubro
                 $tipoSql = $_POST["tipoSql"];
                 switch ($tipoSql) {
                    case '0':
                       $id_rubro = $_POST["idCarga"];
                        $campos = $conn->ObtenerFilasBySqlSelect("select * from grupo where id_rubro = ".$id_rubro);
                        foreach ($campos as $filas){
                        ?>
                        <option value= "<?php echo $filas['cod_grupo']; ?>"><?php echo $filas['descripcion']; ?> </option>

                        <?php }
                    break;
                    case '1':
                        $id_rubro = $_POST["idCarga"];
                        $id=$_POST["id"];
                        $campo = $conn->ObtenerFilasBySqlSelect("select * from item where id_item = ".$id);
                        $campos = $conn->ObtenerFilasBySqlSelect("select * from grupo where id_rubro = ".$id_rubro);

                        foreach ($campos as $filas){
                              if($campo[0]["cod_grupo"]==$filas["cod_grupo"]){
                               ?>
                                 <option value= "<?php echo $filas['cod_grupo']; ?>" selected ><?php echo $filas['descripcion'];  ?> </option>

                            <?php } else{
                            ?>
                                <option value= "<?php echo $filas['cod_grupo']; ?>"><?php echo $filas['descripcion']; ?> </option>

                            <?php }
                        }

                    break;
                  }
            break;
        case "filtroItem":


            /**
             * Procedimiento de busqueda de productos/servicios
             *
             * Realizado por:
             * Luis E. Viera Fernandez
             *
             * Correo:
             *      lviera@armadillotec.com
             *      levieraf@gmail.com
             *
             */
            $tipo_item = (isset($_POST["cmb_tipo_item"])) ? $_POST["cmb_tipo_item"] : 1;

            $busqueda = (isset($_POST["BuscarBy"])) ? $_POST["BuscarBy"] : "";
            $limit = (isset($_POST["limit"])) ? $_POST["limit"] : 50;
            $start = (isset($_POST["start"])) ? $_POST["start"] : 0;

            if ($busqueda) {
                //filtro para productos
                if ($tipo_item == 1) {
                    $codigo = (isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "";
                    $codigo_barras = (isset($_POST["codigoBarrasProducto"])) ? $_POST["codigoBarrasProducto"] : "";
                    $descripcion = (!isset($_POST["descripcionProducto"])) ? "" : $_POST["descripcionProducto"];
                    $referencia = (!isset($_POST["referencia"])) ? "" : $_POST["referencia"];

                    $andWHERE = " and ";
                    if ($codigo != "") {
                        $andWHERE .= " ( cod_item = '" . $codigo . "' or id_item  = '".$codigo."') ";
                        $entrada_codigo=true;
                    }

################################################################################
                    if ($codigo_barras != "") {
                        if ($codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }

                        $andWHERE .= " upper(codigo_barras) like upper('%" . $codigo_barras . "%')";
                    }

                    if ($referencia != "") {
                        if ($codigo_barras != "" || $codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(referencia) like upper('%" . $referencia . "%')";
                    }

################################################################################
                    if ($descripcion != "") {
                        if ($codigo_barras != "" || $referencia != "" || $codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(descripcion1) like upper('%" . $descripcion . "%')";
                    }
                    if ($codigo == "" && $descripcion == "" && $codigo_barras == "" && $referencia == "") {
                        $andWHERE = "";
                    }
                	//echo "HOLA".$andWHERE;
                	//exit;
                }

                //filtro para productos
                if ($tipo_item == 2) {
                    $codigo = (isset($_POST["codigoProducto"])) ? $_POST["codigoProducto"] : "";
                    $descripcion = (!isset($_POST["descripcionProducto"])) ? "" : $_POST["descripcionProducto"];

                    $andWHERE = " and ";
                    if ($codigo != "") {
                        $andWHERE .= " upper(cod_item) = upper('" . $codigo . "')";
                    }
                    if ($descripcion != "") {
                        if ($codigo != "") {
                            $andWHERE .= " and ";
                        } else {
                            $andWHERE = " and ";
                        }
                        $andWHERE .= " upper(descripcion1) like upper('%" . $descripcion . "%')";
                    }
                    if ($codigo == "" && $descripcion == "") {
                        $andWHERE = "";
                    }
                }

                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);

                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item . " " . $andWHERE . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            } else {
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item;
                $campos_comunes1 = $conn->ObtenerFilasBySqlSelect($sql);
                $sql = "SELECT * FROM item i WHERE cod_item_forma = " . $tipo_item . " limit $start,$limit";
                $campos_comunes = $conn->ObtenerFilasBySqlSelect($sql);
            }

            echo json_encode(array(
                "success" => true,
                "total" => count($campos_comunes1),
                "data" => $campos_comunes
            ));

            break;
        case "agregar_factura":
            /**
             * Procedimiento de registro de facturas sin generacion de inventario
             *
             * Realizado por:
             * Charli J. Vivenes Rengel
             *
             * Correo:
             *      cvivenes@asys.com.ve
             *      cjvrinf@gmail.com
             *
             */
#$compra = new Compra();
            $correlativos = new Correlativos();

#$compra->BeginTrans();
            $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "si");

            $sql = "INSERT INTO `compra` (
              `id_compra`, `cod_compra`, `id_proveedor`, `cod_vendedor`,
              `fechacompra`, `montoItemscompra`, `ivaTotalcompra`, `TotalTotalcompra`, `monto_excento`,
              `cantidad_items`, `cod_estatus`, `fecha_creacion`, `usuario_creacion`,
              `responsable`, `centrocosto`, `num_factura_compra`, `num_cont_factura`)
              VALUES (
              NULL , '" . $nro_compra . "', '" . $_GET["id_proveedor"] . "', '',
              '" . $_GET["fecha_emision"] . "', '" . $_GET["subtotal_factura"] . "', '" . $_GET["iva_factura"] . "', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["exento_factura"] . "',
              '0', '1', CURRENT_TIMESTAMP , '" . $_GET["usuario"] . "',
              '" . $_GET["responsable"] . "', '', '" . $_GET["num_factura"] . "', '" . $_GET["num_control"] . "');";

#$compra->ExecuteTrans($sql);
            $conn->ExecuteTrans($sql);

            $sql_cxp = "INSERT INTO cxp_edocuenta (
		`cod_edocuenta`, `id_proveedor`, `documento`,
		`numero`, `monto`, `fecha_emision`,
		`observacion`, `vencimiento_fecha`, `vencimiento_persona_contacto`,
		`vencimiento_telefono`, `vencimiento_descripcion`,
		`usuario_creacion`, `fecha_creacion`, `marca`)
                VALUES (
		NULL, '" . $_GET["id_proveedor"] . "', 'FACxCOM',
		'" . $nro_compra . "', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["fecha_emision"] . "',
		'Compra " . $nro_compra . "', '" . $_GET["fecha_vence"] . "', '',
		'', '' , '" . $_GET["usuario"] . "', '" . $_GET["fecha_emision"] . "', 'P');";

#$compra->ExecuteTrans($sql_cxp);
            $conn->ExecuteTrans($sql_cxp);
            $id_cxp = $conn->getInsertID();

            $SQL_cxp_DET = "INSERT INTO cxp_edocuenta_detalle (
		`cod_edocuenta_detalle`, `cod_edocuenta`, `documento`,
		`numero`, `descripcion`, `tipo`,
		`monto`, `usuario_creacion`, `fecha_creacion`,
		`fecha_emision_edodet`, `marca`)
                VALUES (
		NULL ,'" . $id_cxp . "','PAGOxCOM',
                '" . $nro_compra . "R','compra " . $nro_compra . "','c',
                '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "','" . $_GET["usuario"] . "', CURRENT_TIMESTAMP,
		'" . $_GET["fecha_emision"] . "','P');";
# Se inserta el detalle de la cxp en este caso el asiento del DEBITO.
#$compra->ExecuteTrans($SQL_cxp_DET);
            $conn->ExecuteTrans($SQL_cxp_DET);
            $nro_compra = $correlativos->getUltimoCorrelativo("cod_compra", 1, "no");
            $conn->ExecuteTrans("UPDATE correlativos SET contador = '" . $nro_compra . "' WHERE campo LIKE 'cod_compra'");

            $cod_impuesto = $alicuota = $monto_retenido = 0;
            if ($_GET["retencion_iva"]) {
                $cod_impuesto = $_GET["cod_impuesto"];
                $alicuota = $_GET["alicuota"];
                $monto_retenido = $_GET["iva_factura"] * $alicuota / 100;
            }
#$sql_tipo_impuesto;
//responsable='+responsable+'&num_factura='++'&='+num_control+'&='+exento_factura+'&subtotal_factura='+subtotal_factura+'&='+base_factura+'&iva_factura='+iva_factura+'&='+fecha_emision+'&fecha_vence='+fecha_vence+'&id_proveedor='+id_proveedor+'&usuario='+usuario,
            $sql_cxp_factura = "INSERT INTO cxp_factura (
                    id_factura, cod_factura, cod_cont_factura, id_cxp_edocta, fecha_factura, fecha_recepcion,
                    monto_base, monto_exento, anticipo, monto_total_con_iva, monto_total_sin_iva,
                    cod_impuesto, porcentaje_iva_mayor, monto_iva, porcentaje_iva_retenido, monto_retenido,
                    total_a_pagar, cod_estatus, fecha_pago, fecha_creacion, usuario_creacion,
                    tipo, factura_afectada, libro_compras, cod_correlativo_iva, cod_correlativo_islr)
                VALUES (
                    NULL, '" . $_GET["num_factura"] . "', '" . $_GET["num_control"] . "', '" . $id_cxp . "', '" . $_GET["fecha_emision"] . "', '" . $_GET["fecha_emision"] . "',
                    '" . $_GET["base_factura"] . "', '" . $_GET["exento_factura"] . "', '0', '" . ($_GET["iva_factura"] + $_GET["subtotal_factura"]) . "', '" . $_GET["subtotal_factura"] . "',
                    '" . $cod_impuesto . "', 12, '" . $_GET["iva_factura"] . "', " . $alicuota . ", " . $monto_retenido . ",
                    '" . ($_GET["subtotal_factura"] + $_GET["iva_factura"] - $monto_retenido) . "', '1', '', CURRENT_TIMESTAMP, '" . $_GET["usuario"] . "',
                    'FAC', '" . $_GET["num_factura"] . "', '{$_GET["libro_compras"]}', '', '')";
            $conn->ExecuteTrans($sql_cxp_factura);

            $id_cxp_factura = $conn->getInsertID();
            $sql_cxp_factura_det = "INSERT INTO cxp_factura_detalle (
                    id_factura, id_factura_fk, monto_base, porcentaje_retenido, cod_impuesto, monto_retenido, id_item)
                VALUES (
                    NULL, '" . $id_cxp_factura . "', '" . $_GET["base_factura"] . "', '" . $_GET["alicuota"] . "', '', '')";
            $conn->ExecuteTrans($sql_cxp_factura_det);
            break;


        case "cambioPrecio":

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *
							from item
							WHERE id_item between $_GET[itemini] and $_GET[itemfin]");
            if (count($campos) == 0) {
                exit;
            }
            echo '
            		<table >
                    <thead>
                        <th style="border-bottom: 1px solid #949494;width:110px;">Cod</th>
                        <th style="border-bottom: 1px solid #949494;width:200px;">Descripcion</th>
                        <th style="border-bottom: 1px solid #949494;width:200px;">Precio1&nbsp;<input type="checkbox" name="precio1" id="precio1" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio2&nbsp;<input type="checkbox" name="precio2" id="precio2" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio3&nbsp;<input type="checkbox" name="precio3" id="precio3" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio4&nbsp;<input type="checkbox" name="precio4" id="precio4" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio5&nbsp;<input type="checkbox" name="precio5" id="precio5" value="1"></th>
								<th style="border-bottom: 1px solid #949494;width:200px;">Precio6&nbsp;<input type="checkbox" name="precio6" id="precio6" value="1"></th>

                    </thead>
                    <tbody>';


            $acuDebitos = 0;
            $acuCreditos = 0;
            foreach ($campos as $key => $item) {
                echo '
                        <tr>
                            <td align="center" style="border-bottom: 1px solid #949494;width:110px;">' . $item["cod_item"] . '
									<input type="hidden" id="id_item[]" name="id_item[]" value='.$item["id_item"].'>
                            </td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:200px;">' . $item["descripcion1"] . '</td>
                            <td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva1" name="coniva1[]" value=' . $item["coniva1"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva2" name="coniva2[]" value=' . $item["coniva2"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva3" name="coniva3[]" value=' . $item["coniva3"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva4" name="coniva4[]" value=' . $item["coniva4"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva5" name="coniva5[]" value=' . $item["coniva5"] . '></td>
									<td style="text-align: left; border-bottom: 1px solid #949494;width:110px;"><input style="text-align: right;" size="7" type="text" id="coniva6" name="coniva6[]" value=' . $item["coniva6"] . '></td>

                        </tr>';
            }
            echo '</tbody></table>';
            break;
            case "ValidarSerial":
                //ajax para validar los seriales
            $serial=$_POST["serial"];
            $num=$_POST["num"];
            $idItem=$_POST["idItem"];
            if($serial==""){
                $campo[0]["serial"]="";
            }else{
               $campo = $conn->ObtenerFilasBySqlSelect("select * from item_serial where id_producto=".$idItem." and estado=1 and serial ='".$serial."'");
            }

            if($campo[0]["serial"]==""){
                echo "<div><img src='../../libs/imagenes/ico_est6.gif' width='16' height='16' /></div>";
                echo "<input class='oculto' type='hidden' name=h".$num." id=h".$num." value=1 />";
            }else{
                echo "<div><img src='../../libs/imagenes/ico_est2.gif' width='16' height='16' /></div>";
                echo "<input class='oculto' type='hidden' name=h".$num." id=h".$num." value=0 />";
            }




            break;
            case "BuscarSerial":
                $idItem=$_POST["idItem"];
                $campo = $conn->ObtenerFilasBySqlSelect("select * from item_serial where  estado=1 and id_producto=".$idItem);
                echo "<option value='0'>Seleccione un serial</option>";
                foreach ($campo as $key ) {
                  echo "<option value='".$key["serial"]."'>".$key["serial"]."</option>";
                }

            break;

            case "cantidadSeriales":
                $id=$_GET[cod];
	            $cant=$_GET[cant];
	            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *	from item WHERE id_item ='$id'");
	            if($campos[0][seriales]==1)
	            {

		            echo '<form id="seriales" name="seriales"><table>';
		            echo '<tr>
		                   <td colspan="2"><input type="hidden" name="item" id="item" value="'.$id.'"></td>
		                   </tr>';
                    echo "<div style='margin: 25px 0px 0px 40px;'><input type='text' id='iSerialGen' style='float:left' class='form-text'></div>";
                    echo "<div id='gSerial' style='background:#abcee4;px;padding:5px 5px;margin:3px 0px 10px 10px;float:left;color:white;cursor:pointer'>Generar</div>" ;
		            echo "<div style='clear:both'></div>";
                    for($i=0;$i<$cant;$i++)
		            {
		                   echo '<tr>
		                   <td>Serial '.($i+1).'</td>
		                   <td><input type="text" name="serial'.$i.'" id="serial'.$i.'" class="form-text serialSec "></td>
		                   </tr>';

		            }
		        		echo '</table></form>';
		        	}
		        	else
		        	{
		        		echo "-1";
		        	}
            break;

            case "cantidadRubros":
                $id=$_GET[cod];
                $prec=$_GET[prec];
                $campos = $conn->ObtenerFilasBySqlSelect("SELECT * from rubros_estudio_mercado WHERE id_rubro ='$id'");
                //if($campos[0][seriales]==1)
                /*if($campos[0])
                {

                    echo '<form id="seriales" name="seriales"><table>';
                    echo '<tr>
                           <td colspan="2"><input type="hidden" name="item" id="item" value="'.$id.'"></td>
                           </tr>';
                    echo "<div style='margin: 25px 0px 0px 40px;'><input type='text' id='iSerialGen' style='float:left' class='form-text'></div>";
                    echo "<div id='gSerial' style='background:#abcee4;px;padding:5px 5px;margin:3px 0px 10px 10px;float:left;color:white;cursor:pointer'>Generar</div>" ;
                    echo "<div style='clear:both'></div>";
                    for($i=0;$i<$cant;$i++)
                    {
                           echo '<tr>
                           <td>Serial '.($i+1).'</td>
                           <td><input type="text" name="serial'.$i.'" id="serial'.$i.'" class="form-text serialSec "></td>
                           </tr>';

                    }
                        echo '</table></form>';
                    }
                    else
                    {
                        echo "-1";
                    }*/
            break;

            case "agregarSeriales":

            	$form=array();
			     	//echo $_GET["formulario"];
			     	$form=json_decode($_GET["formulario"],true);
			     	$ii=0;
			     	foreach ($form as $i => $input)
					{
						if($input[name]=="item")
							$item=$input[value];

						$cad="serial".$ii;

						if($input[name]==$cad)
						{
							$ii++;
							//$tallas[]=$input[value];
							$ser=$input[value];
							$conn->ExecuteTrans("insert into item_serial_temp (id_producto, serial, estado) values ('$item', '$ser', '1' )");
						}
					}
		         /*$id=$_GET[cod];
	            $cant=$_GET[cant];

	            $campos = $conn->ObtenerFilasBySqlSelect("SELECT *	from item WHERE id_item ='$id'");
	            if($campos[0][seriales]==1)
	            {

		            echo '<form id="seriales" name="seriales"><table>';
		            for($i=0;$i<=$cant;$i++)
		            {
		                   echo '<tr>
		                   <td>Serial '.$i.'</td>
		                   <td><input type="text" name="serial[]" id="serial[]" class="form-text"></td>
		                   </tr>';

		            }
		        		echo '</table></form>';
		        	}
		        	else
		        	{
		        		echo "-1";
		        	}*/
            break;
            case"cargarAlmacen":
                  $comunes = new Comunes();
                  $id_localidad=$_POST["idLocalidad"];
                  $campo = $comunes->ObtenerFilasBySqlSelect("select * from almacen where id_localidad=".$id_localidad);
                  $filas=$comunes->getFilas();
                  $mensaje=$comunes->Notificacion();
                  $cabecera=array("Codigo","Descripción");
                  // boton agregar almacen
                  echo "<div style='margin-bottom: 10px;'>";
                     echo "<table style='cursor: pointer; 'class='btn_bg' onclick=\"javascript:window.location='?opt_menu=3&amp;opt_seccion=55&amp;opt_subseccion=add&amp;idLocalidad=".$id_localidad."&amp;loc=1';\">";
                         echo "<tr>";
                            echo " <td><img src='../../../includes/imagenes/bt_left.gif' style='border-width: 0px; width: 4px; height: 21px;' /></td>
                                   <td><img src='../../../includes/imagenes/add.gif' width='16' height='16' /></td>";
                            echo " <td style='padding: 0px 4px;'>Agregar</td>
                                   <td><img src='../../../includes/imagenes/bt_right.gif' style='border-width: 0px; width: 4px; height: 21px;' /></td>" ;
                        echo "</tr>";
                     echo "</table>";
                echo "</div>";
                  echo "</div style='clear_both'>";
                   //fin del boton agregar almacen
                  echo " <table class='seleccionLista'>";
                    echo "<tr class='tb-head'>";
                        foreach ( $cabecera as $key => $value) {
                           echo" <td><b>".$value."</b></td>";
                        }
                       echo"<td colspan='3' style='text-align:center;'><b>Opciones</b></td>" ;
                    echo "</tr >";
                    if ($filas==0) {
                      echo " <tr><td colspan='3'>".$mensaje."</td></tr>";
                    }else{
                        foreach ($campo as $key => $value) {
                        echo "<tr style='background:#cacacf;'>";
                           echo "<td >".$value["cod_almacen"]."</td>";
                           echo "<td >".$value["descripcion"]."</td>";
                           echo "<td style='cursor: pointer; width: 30px; text-align:center'>
                                <img class='editar' onclick=\"javascript: window.location.href='?opt_menu=3&amp;opt_seccion=55&amp;opt_subseccion=edit&amp;cod=".$value["cod_almacen"]."&amp;idLocalidad=".$id_localidad."&amp;loc=1'\" title='Editar' src='../../../includes/imagenes/edit.gif'/>
                                 </td>";
                           echo "<td style='cursor: pointer; width: 30px; text-align:center'>
                                <img class='eliminar' onclick=\"javascript: window.location.href='?opt_menu=3&amp;opt_seccion=55&amp;opt_subseccion=delete&amp;cod=".$value["cod_almacen"]."&amp;idLocalidad=".$id_localidad."&amp;loc=1'\" title='Eliminar' src='../../../includes/imagenes/delete.gif'/>
                                </td>";
                            echo "<td style='cursor: pointer; width: 30px; text-align:center'>
                                <img class='eliminar' onclick=\"javascript: window.location.href='?opt_menu=3&amp;opt_seccion=55&amp;opt_subseccion=ubicacion&amp;cod=".$value["cod_almacen"]."&amp;idLocalidad=".$id_localidad."&amp;loc=1'\" title='agregar ubicacion' src='../../../includes/imagenes/add.gif'/>
                            </td>";
                        echo "</tr>";
                        }
                    }
                  echo " </table>";
            break;
            case "cargaUbicacion":
                       $almacen=$_POST["idAlmacen"];
                       $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id_almacen='".$almacen."'");
                        if (count($campos) == 0) {
                            echo "[{band:'-1'}]";
                        } else {
                            echo json_encode($campos);
                        }
            break;
             case "cargaProductoCodigo":
                       $codigoBarra=$_POST["codigoBarra"];
                     $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM `item` WHERE `codigo_barras`='".$codigoBarra."'");
                       if (count($campos) == 0) {
                            echo "[{band:'-1'}]";
                        } else {
                            echo json_encode($campos);
                        }
            break;
            case "CambioPrecios1":
                    $comunes = new Comunes();
                    $mensaje=$comunes->Notificacion();
                    $id_producto=$_POST["id_producto"];
                   // $campo = $comunes->ObtenerFilasBySqlSelect("SELECT i.*, r.descripcion as region, t.descripcion as tipo FROM item_precio i , region r,tipo_precio_item t where i.id_producto =".$id_producto." and i.id_region=r.id and i.tipo_precio=t.id order by id_region,tipo_precio ;");
                    $campo=$comunes->ObtenerFilasBySqlSelect("select * from item where id_item='".$id_producto."'");
                    $pos=POS;
                    $campopos=$comunes->ObtenerFilasBySqlSelect("SELECT * FROM $pos.products WHERE id='".$campo[0]['itempos']."'");

                    $filas=$comunes->getFilas();
                    $reg=0;
                    $cont=0;
                    // $cabecera=array("REGION","TIPO DE PRECIO","PRECIO");
                    $cabecera=array("ID","PRODUCTO","CODIGO DE BARRAS","PRECIO PYME", "PRECIO POS");
                    //echo "<tr><td>".$campopos[0]["ID"]."SELECT * FROM $pos.products WHERE id='".$campo[0]['itempos']."'</td></tr>";
                        echo "<tr class='tb-head' >";
                        foreach ($cabecera as $key => $value) {
                           echo" <td width='20%'><b>".$value."</b></td>";
                        }
                        echo "</tr >";
                         if ($filas==0) {
                            echo " <tr><td colspan='1' width='10%' VALIGN='MIDDLE' ALIGN='CENTER'>".$mensaje."</td></tr>";
                        }else{
                            foreach ($campo as $key => $value) {
                                if($reg!=$value["id_item"]){
                                    if($cont!=3 && $cont!=0 ){
                                        while ($cont<=3) {
                                             echo "<td width='10%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";
                                             $cont++;
                                        }

                                    }
                                    $cont=1;
                                    echo "<tr style='background:#cacacf;'><td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>".$value["id_item"]."</td>";
                                    $reg=$value["id_item"];

                                }
                                    if($cont==1){
                                        if(!empty($value["descripcion1"])){
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>".$value["descripcion1"]."<input type=hidden readonly name='nombre_producto_c' id='nombre_producto_c' value='".$value["descripcion1"]."'/></td>";
                                        }else{
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";
                                             $cont++;
                                        }
                                    }
                                    if($cont>=1){
                                        if($value["codigo_barras"]!=""){
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>".$value["codigo_barras"]."<input type=hidden readonly name='codigo_barras_c' id='codigo_barras_c' value='".$value["codigo_barras"]."'/></td>";
                                        }else{
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";
                                             $cont++;
                                        }
                                    }
                                    if($cont>=1){
                                        if($value["coniva1"]!=""){
                                            echo "<td width='15%' VALIGN='MIDDLE' ALIGN='CENTER'>".$value["coniva1"]."</td>";
                                        }else{
                                            echo "<td width='15%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";
                                            echo " </tr>";
                                        }
                                    }
                                    $cont++;
                                    if($cont>=1 && !empty($campopos[0]['PRICESELL'])){
                                        if($campopos[0]['PRICESELL']!=""){
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>".number_format($campopos[0]['PRICESELL'],2,".",",")."<input type=hidden readonly name='pricesell' id='pricesell' value='".number_format($campopos[0]['PRICESELL'],2,".",",")."'/></td>";
                                        }else{
                                            echo "<td width='25%' VALIGN='MIDDLE' ALIGN='CENTER'>sin precio</td>";
                                            echo " </tr>";
                                        }




                                        }
                                    $cont++;



                            }

                        }
                    echo "</table>" ;


            break;
            case 'CambioPrecios2':
                $comunes = new Comunes();
                $i=0;
                $producto= $_POST["producto"];
                $region= $_POST["region"];
                $tipoPrecio= $_POST["tipoPrecio"];
                $tipoPrecio=explode(",", $tipoPrecio);
                echo "<div>";
                echo "<form id='formulario'>";
                foreach ($tipoPrecio as $value) {
                    $campo = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_precio , tipo_precio_item a where id_region=".$region." and id_producto=".$producto." and tipo_precio=".$tipoPrecio[$i]." and a.id=tipo_precio ;");
                    if($value==1)
                        $tipo="Red Comercial";
                     if($value==2)
                        $tipo="PAE";
                     if($value==3)
                        $tipo="Pdvalito";

                   echo "<div style='margin-left: 116px'><div style='float:left;width:100px'>".$tipo." </div><input class='form-text precioV' id='".$value."' type='text' value='".$campo[0]["precio"]."' onkeypress='if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;'><div style='clear:both'></div></div>";
                   echo "<input type='hidden' value='".$campo[0]["precio"]."' name='precioh".$value."' id='precioh".$value."' >" ;
                   echo "<input type='hidden' name='".$value."' value='".$value."'>";
                   $i++;

                }
                echo "</div>";
                echo "<input type='hidden' id='cantPrecio' value='".$i."'>";
                echo "<input type='hidden' id='producto' value='".$producto."'>";
                echo "<input type='hidden' id='region' value='".$region."'>";
                echo "<div id='agregarP' style='width: 90px;margin: 20px 0px 0px 186px;cursor:pointer ;padding: 10px; background: #bdd1dc;cursor:pointer'>Agregar precios</div>";
               echo "</form>";


            break;
            case 'CambioPrecios3':
               $comunes = new Comunes();
                $cantidad=$_POST["cantidad"];
                $producto=$_POST["producto"];
                $region=$_POST["region"];
                if(isset($_POST["1"])){
                    $campo = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_precio where id_region=".$region." and id_producto=".$producto." and tipo_precio=1");
                    $filas=$comunes->getFilas();
                    if( $filas!=0){
                         echo   $sql = "UPDATE item_precio SET precio =".$_POST["precioh1"]."  WHERE id = {$campo[0]['id']};";
                            $comunes->Execute2($sql);
                    }else{
                             $instruccion = "INSERT INTO item_precio (id_region, id_producto, tipo_precio, precio)
                             VALUES ( '$region', '$producto', '1', ".$_POST["precioh1"].");";
                            $comunes->ExecuteTrans($instruccion);
                    }
                }
                if(isset($_POST["2"])){
                    $campo = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_precio where id_region=".$region." and id_producto=".$producto." and tipo_precio=2");
                    $filas=$comunes->getFilas();
                    if( $filas!=0){
                         echo   $sql = "UPDATE item_precio SET precio =".$_POST["precioh2"]."  WHERE id = {$campo[0]['id']};";
                            $comunes->Execute2($sql);
                    }else{
                             $instruccion = "INSERT INTO item_precio (id_region, id_producto, tipo_precio, precio)
                             VALUES ( '$region', '$producto', '2', ".$_POST["precioh2"].");";
                            $comunes->ExecuteTrans($instruccion);
                    }
                }
                 if(isset($_POST["3"])){
                    $campo = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM item_precio where id_region=".$region." and id_producto=".$producto." and tipo_precio=3");
                    $filas=$comunes->getFilas();
                    if( $filas!=0){
                         echo   $sql = "UPDATE item_precio SET precio =".$_POST["precioh3"]."  WHERE id = {$campo[0]['id']};";
                            $comunes->Execute2($sql);
                    }else{
                             $instruccion = "INSERT INTO item_precio (id_region, id_producto, tipo_precio, precio)
                             VALUES ( '$region', '$producto', '3', ".$_POST["precioh3"].");";
                            $comunes->ExecuteTrans($instruccion);
                    }
                }




            break;

            case 'cargarCantidadPOS_1':
            $pos=POS;

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM item WHERE id_item = '".$_GET["v2"]."'");
            $campopos=$conn->ObtenerFilasBySqlSelect("SELECT * FROM $pos.stockcurrent WHERE PRODUCT='" . $campos[0]['itempos']."'");
           // echo "SELECT * FROM $pos.stockcurrent WHERE PRODUCT=" . $campos[0]['itempos'];
            //echo $campopos[0]["PRODUCT"];
            if (count($campopos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campopos);
            }
            break;

             case 'ver_ubicacion':

            $campos = $conn->ObtenerFilasBySqlSelect("SELECT * FROM ubicacion WHERE id = '".$_GET["ubicacion"]."'");

            if (count($campos) == 0) {
                echo "[{id:'-1'}]";
            } else {
                echo json_encode($campos);
            }
            break;
    }
}
?>
