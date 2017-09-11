<?php
ini_set("memory_limit","512M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

//Includes
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
$conn= new Producto();
$path_productos="/var/www/pyme/selectraerp/uploads/productos_temporal";
$path_productos_final="/var/www/pyme/selectraerp/uploads/productos_central";
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");
$cadena="52";
$nomb=$cadena."_".$dia.$mes.$ano.$hora.$min.".csv";
$bd='selectrapyme_central';


/*$bd_pdval=$conn->ObtenerFilasBySqlSelect("SELECT
       id_item,
       cod_item,
       codigo_barras,
       descripcion1,
       descripcion2,
       descripcion3,
       referencia, 
       codigo_fabricante,
       unidad_empaque,
       cantidad,
       seriales,
       garantia,
       tipo_item,
       factor_cambio,
       ultimo_costo,
       precio_x_escala,
       comision_x_item,
       tipo_comision_x_item,
       desdeA1,
       desdeA2,
       desdeB1,
       comisiones1,
       comisiones2,
       comisiones3,
       desdeB2,
       desdeC1,
       desdeC2,
       precio1,
       utilidad1,
       coniva1,
       precio2,
       utilidad2,
       coniva2,
       precio3,
       utilidad3,
       coniva3,
       precio_referencial1,
       precio_referencial2,
       precio_referencial3,
       costo_actual,
       costo_promedio,
       costo_anterior,
       existencia_total,
       existencia_min,
       existencia_max,
       monto_exento,
       iva,
       ubicacion1,
       ubicacion2,
       ubicacion3,
       ubicacion4,
       ubicacion5,
       cod_departamento,
       cod_grupo,
       cod_linea,
       estatus,
       usuario_creacion,
       fecha_creacion,
       cod_item_forma,
       tipo_prod,
       cuenta_contable1,
       cuenta_contable2,
       codigo_cuenta,
       serial1,
       foto,
       cantidad_bulto,
       kilos_bulto,
       proveedor,
       fecha_ingreso,
       origen,
       costo_cif,
       costo_origen,
       temporada,
       mate_compo_clase,
       punto_pedido,
       tejido,
       reg_sanit,
       cod_barra_bulto,
       observacion,
       foto1,
       foto2,
       foto3,
       foto4,
       cont_licen_nro,
       precio_cont,
       aprob_arte,
       propiedad,
       regulado,
       itempos 
       FROM $bd.productos limit 10");*/
$bd_pdval=$conn->ObtenerFilasBySqlSelect("SELECT cod_item,
       codigo_barras,itempos from $bd.productos");

//OBTENEMOS DE LA BD Y GENERAMOS EL ARCHIVO EN VENTAS CONSOLIDADOS
$resp_bd=$conn->getFilas(); 
//print_r($bd_pdval);
//exit();
if($resp_bd>0){
    foreach ($bd_pdval as $key => $fila) 
{
$contenido_inventario.=$fila['itempos'].",".$fila['cod_item'].",".$fila['codigo_barras']."\n";//$fila['id_item'].";".$fila['cod_item'].";".$fila['codigo_barras'].";".str_replace(";","",utf8_decode($fila['descripcion1'])).";".$fila['descripcion2'].";".$fila['descripcion3'].";".$fila['referencia,'].";".$fila['codigo_fabricante'].";".$fila['unidad_empaque'].";".$fila['cantidad'].";".$fila['seriales'].";".$fila['garantia'].";".$fila['tipo_item'].";".$fila['factor_cambio'].";".$fila['ultimo_costo'].";".$fila['precio_x_escala'].";".$fila['comision_x_item'].";".$fila['tipo_comision_x_item'].";".$fila['desdeA1'].";".$fila['desdeA2'].";".$fila['desdeB1'].";".$fila['comisiones1'].";".$fila['comisiones2'].";".$fila['comisiones3'].";".$fila['desdeB2'].";".$fila['desdeC1'].";".$fila['desdeC2'].";".$fila['precio1'].";".$fila['utilidad1'].";".$fila['coniva1'].";".$fila['precio2'].";".$fila['utilidad2'].";".$fila['coniva2'].";".$fila['precio3'].";".$fila['utilidad3'].";".$fila['coniva3'].";".$fila['precio_referencial1'].";".$fila['precio_referencial2'].";".$fila['precio_referencial3'].";".$fila['costo_actual'].";".$fila['costo_promedio'].";".$fila['costo_anterior'].";".$fila['existencia_total'].";".$fila['existencia_min'].";".$fila['existencia_max'].";".$fila['monto_exento'].";".$fila['iva'].";".$fila['ubicacion1'].";".$fila['ubicacion2'].";".$fila['ubicacion3'].";".$fila['ubicacion4'].";".$fila['ubicacion5'].";".$fila['cod_departamento'].";".$fila['cod_grupo'].";".$fila['cod_linea'].";".$fila['estatus'].";".$fila['usuario_creacion'].";".$fila['fecha_creacion'].";".$fila['cod_item_forma'].";".$fila['tipo_prod'].";".$fila['cuenta_contable1'].";".$fila['cuenta_contable2'].";".$fila['codigo_cuenta'].";".$fila['serial1'].";".$fila['foto'].";".$fila['cantidad_bulto'].";".$fila['kilos_bulto'].";".$fila['proveedor'].";".$fila['fecha_ingreso'].";".$fila['origen'].";".$fila['costo_cif'].";".$fila['costo_origen'].";".$fila['temporada'].";".$fila['mate_compo_clase'].";".$fila['punto_pedido'].";".$fila['tejido'].";".$fila['reg_sanit'].";".$fila['cod_barra_bulto'].";".trim($fila['observacion']).";".$fila['foto1'].";".$fila['foto2'].";".$fila['foto3'].";".$fila['foto4'].";".$fila['cont_licen_nro'].";".$fila['precio_cont'].";".$fila['aprob_arte'].";".$fila['propiedad'].";".$fila['regulado'].";".$fila['itempos'].";"."\n"; 

}
        //bloque para comprimir el archivo y generar el zip
                $archivo_consolidado="productos_central.csv";
                $pf_ext=fopen($path_productos.'/'.$archivo_consolidado,"w");
                        //GENERO EL ARCHIVO QUE LLENARA LA TABLA EXTERNAL SALE
                fwrite($pf_ext, utf8_decode($contenido_inventario));
                fclose($pf_ext);

                chmod($path_productos.'/'.$archivo_consolidado,  0777);
                            //comprimiendo el archivo
                                $zip = new ZipArchive;
                                    //nombre sin .csv
                                $nombre_final=  substr($archivo_consolidado, 0,-4);
                                        //nombre del zip
                                $nombrezip=$nombre_final.".zip";
                                if($zip->open($path_productos_final.'/'.$nombrezip,ZIPARCHIVE::CREATE)===true){
                                $zip->addFile($path_productos.'/'.$archivo_consolidado);
                                //$nombre_final=  substr($archivo_consolidado, 0,-4);
                                //  $zip->addFile($archivo_consolidado);
                                $zip->close();
                                chmod($path_productos_final.'/'.$nombrezip,  0777);
                                     //echo 'comprimio';
                                                 } else {
                                                     //echo 'no comprimio';
                                                     }








}//fin del if
