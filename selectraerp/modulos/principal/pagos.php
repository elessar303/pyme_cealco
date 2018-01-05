<?php
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/factura.php");
$factura = new Factura();
$almacen = new Almacen();
$login = new Login();

$ruta_server=$_SERVER['DOCUMENT_ROOT'];

$findme='var';
$loc_aper = strpos($ruta_server, $findme);
$ipserver=DB_HOST;
$impresora_serial=impresora_serial;

$id_usuario=$login->getIdUsuario();
$pos = POS;
$despacho=$_GET['despacho'];

$bancos=$almacen->ObtenerFilasBySqlSelect("SELECT * from banco");

$sql="SELECT *,kardex_almacen.estado as estatus from kardex_almacen, clientes WHERE kardex_almacen.id_cliente=clientes.id_cliente and kardex_almacen.facturado=0 and clientes.id_cliente=".$despacho."";

$datos_cliente=$almacen->ObtenerFilasBySqlSelect($sql);

$money=$almacen->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");

if (empty($money)) 
{

    $sql="INSERT INTO closedcash_pyme(serial_caja, money, fecha_inicio, fecha_fin) VALUES ('".impresora_serial."', '".$_POST['serial'].date('Y-m-d_H:i:s')."',  now(), null)";
    $insert_money=$almacen->Execute2($sql);
}

$money=$almacen->ObtenerFilasBySqlSelect("select money from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by secuencia desc limit 1");

if(isset($_POST['guardar1']))
{
    //pedidos pendiente del cliente
    $sql="SELECT *,kardex_almacen.estado as estatus from kardex_almacen, clientes WHERE kardex_almacen.id_cliente=clientes.id_cliente and kardex_almacen.facturado=0  and clientes.id_cliente=".$despacho."";

    $datos_cliente=$almacen->ObtenerFilasBySqlSelect($sql);
    $facturas="";
    
    foreach ($datos_cliente as $key => $value) 
    {
        $facturas.="'".$value['nro_factura']."', ";
    }
    $facturas=substr($facturas, 0, -2);
    $sql="SELECT coalesce((a.total_monto),0) as monto, a.id FROM pedido_facturas_consolidadas_maestra as a, pedidos_pagos as b, despacho_new as c
    where a.id=b.id_pagos_consolidados and a.id=c.id_pagos_consolidados and cod_factura in (".$facturas.") group by a.id";

    $rs4=$almacen->ObtenerFilasBySqlSelect($sql);
    $rs=0;
    foreach ($rs4 as $key => $value) 
    {
        $rs+=$value['monto'];
    }
    $rs4[0]['monto']=$rs;
    
    $sql="SELECT * from despacho_new WHERE cod_factura in (".$facturas.")";
    $datos_factura=$almacen->ObtenerFilasBySqlSelect($sql);
    $sql="
    select sum(a.total1) as total1, sum(a.total) as total from (
    SELECT (`_item_cantidad`*`_item_totalconiva`) as total1, (totaltotalfactura) as total FROM despacho_new_detalle,despacho_new
    WHERE cod_factura in (".$facturas.")
    AND despacho_new_detalle.id_factura=despacho_new.id_factura group by despacho_new_detalle.id_factura) as a ";
    $total_factura=$almacen->ObtenerFilasBySqlSelect($sql);
    $datos_factura[0]['totalizar_total_general']=$total_factura[0]['total'];
    $monto=$datos_factura[0]['totalizar_total_general']-$rs4[0]['monto'];
    
    if($_POST['monto2']>$monto)
    {
        $_POST['monto2']=$monto;
    }
    //comprobando las retenciones
    if($_POST['forma_pago']=="RetencionIva")
    {
        if($_POST['nro_irpf'] !="")
        {
            $consult=$almacen->ObtenerFilasBySqlSelect("select id from pedidos_pagos where nro_retencion=".$_POST['nro_irpf']);
            
            if(isset($consult[0]['id']))
            {
                echo "<script type='text/javascript'>alert('¡Error!, El numero de IRPF ya fue utilizado');</script>"; exit();
            }
        }
        else
        {
            echo "<script type='text/javascript'>alert('!Error, Nro. irpf esta vacio');</script>"; exit();
        }
        
    }
    else
    {
        $_POST['nro_irpf']=0;
    }
    
    if($_POST['forma_pago']=="Retencion1x1000")
    {
        if($_POST['nro_1x1000'] !="")
        {
            $consult=$almacen->ObtenerFilasBySqlSelect("select id from pedidos_pagos where nro_1x1000=".$_POST['nro_1x1000']);
            if(isset($consult[0]['id']))
            {
                echo "<script type='text/javascript'>alert('¡Error!, El numero de 1x1000 ya fue utilizado');</script>";    exit();
            }
        }
        else
        {
            echo "<script type='text/javascript'>alert('!Error, nro. 1x1000 esta vacio');</script>";exit();
        }

    }
    else
    {
       $_POST['nro_1x1000']=0; 
    }

    if($_POST['forma_pago']=="Credito")
    {
        
        if($rs4[0]['monto']>0)
        {
            
            echo "<script type='text/javascript'>alert('!Error, El monto credito no puede ser parcial');</script>";exit();
        }
        else
        {
            if($datos_factura[0]['totalizar_total_general']-$_POST['monto2']!=0)
            {
                echo "<script type='text/javascript'>alert('!Error, El monto credito debe ser igual al total de la factura');</script>";exit();
            }
        }
    }

    
    //quiere decir que existe un pago maestro
    if($rs>0)
    {
        $sql="INSERT INTO pedido_facturas_consolidadas (id_maestro, total_monto, id_cliente) values (".$rs4[0]['id'].", ".$_POST['monto2'].", ".$_GET['despacho'].")";
        $insert_pago=$almacen->Execute2($sql);
        $sql="update pedido_facturas_consolidadas_maestra set total_monto=(total_monto+".$_POST['monto2'].") where id=".$rs4[0]['id'];
        $insert_pago=$almacen->Execute2($sql);
        $id_facturaTrans = $rs4[0]['id'];
    }
    else
    {
        //se inserta en maestra primero
        $sql="INSERT INTO pedido_facturas_consolidadas_maestra (total_monto) values (".$_POST['monto2'].")";
        $insert_pago=$almacen->Execute2($sql);
        $id_facturaTrans = $almacen->getInsertID();
        $sql="INSERT INTO pedido_facturas_consolidadas (id_maestro, total_monto, id_cliente) values (".$id_facturaTrans.", ".$_POST['monto2'].", ".$_GET['despacho'].")";
        $insert_pago=$almacen->Execute2($sql);
        
    }
    
    $sql="update despacho_new set id_pagos_consolidados=".$id_facturaTrans." where cod_factura in (".$facturas.")";
    $update=$almacen->Execute2($sql);
    //fin retenciones
    $sql="INSERT INTO `pedidos_pagos`(`nro_pedido`, `forma_pago`, `nro_tarjeta`, `tipo_tarjeta`, `id_banco`, `monto_pagado`, `nro_retencion`, `nro_1x1000`, `usuario`, `id_pagos_consolidados` ) VALUES (".$_GET['despacho'].",'".$_POST['forma_pago']."','".$_POST['nro_tarjeta']."','".$_POST['tipo_tarjeta']."','".$_POST['id_banco']."',".$_POST['monto2'].",".$_POST['nro_irpf'].",".$_POST['nro_1x1000'].", ".$id_usuario.", ".$id_facturaTrans.")";
    $insert_pago=$almacen->Execute2($sql);
}

if(isset($_POST['imprimir']))
{
    $nro_pedido=$_GET['despacho'];
    $cedula='18677970';
    $id_fiscal="RIF";
    include ("../../libs/php/clases/tfhka/TfhkaPHP.php");
    $itObj = new Tfhka();

    $sql="SELECT *,kardex_almacen.estado as estatus from kardex_almacen, clientes WHERE kardex_almacen.id_cliente=clientes.id_cliente and kardex_almacen.facturado=0  and clientes.id_cliente=".$despacho."";

    $datos_cliente=$almacen->ObtenerFilasBySqlSelect($sql);
    $facturas="";
    
    foreach ($datos_cliente as $key => $value) 
    {
        $facturas.="'".$value['nro_factura']."', ";
    }
    $facturas=substr($facturas, 0, -2);

    $sql="SELECT * FROM kardex_almacen a, despacho_new b,  despacho_new_detalle c where a.nro_factura=b.cod_factura and b.id_factura=c.id_factura and b.cod_factura in (".$facturas.")";
    
    //pedidos 
    $regs_pedido=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT clientes.nombre, clientes.rif, clientes.direccion , clientes.telefonos  FROM  clientes where id_cliente=".$nro_pedido;
    //cliente
    $array_cliente=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT sum(b.totalizar_pdescuento_global) as totalizar_pdescuento_global FROM kardex_almacen a, despacho_new b,  despacho_new_detalle c where a.nro_factura=b.cod_factura  and b.id_factura=c.id_factura b.cod_factura in (".$facturas.")";
    //descuento
    $array_descuento=$almacen->ObtenerFilasBySqlSelect($sql);

    
    $sql="SELECT sum(monto_pagado) as total_efectivo FROM pedidos_pagos where id_pagos_consolidados=".$regs_pedido[0]['id_pagos_consolidados']." and forma_pago='Efectivo'";
    //total pago
    $array_pago_efectivo=$almacen->ObtenerFilasBySqlSelect($sql);
    
    $sql="SELECT sum(monto_pagado) as total_efectivo2 FROM pedidos_pagos where id_pagos_consolidados=".$regs_pedido[0]['id_pagos_consolidados']." and forma_pago='Nota Credito'";
    
    $array_pago_efectivo2=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT sum(monto_pagado) as total_tarjeta FROM pedidos_pagos where id_pagos_consolidados=".$regs_pedido[0]['id_pagos_consolidados']." and forma_pago='Tarjeta'";
    $array_pago_tarjeta=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT sum(monto_pagado) as total_ticket FROM pedidos_pagos where id_pagos_consolidados=".$regs_pedido[0]['id_pagos_consolidados']." and forma_pago='Ticket'";
    $array_pago_ticket=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT sum(monto_pagado) as total_retencioniva FROM pedidos_pagos where id_pagos_consolidados=".$regs_pedido[0]['id_pagos_consolidados']." and forma_pago='RetencionIva'";
    $array_pago_retencioniva=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT sum(monto_pagado) as total_retencioniva1x1000 FROM pedidos_pagos where id_pagos_consolidados=".$regs_pedido[0]['id_pagos_consolidados']." and forma_pago='Retencion1x1000'";

    $array_pago_retencioniva1x1000=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT sum(monto_pagado) as total_credito FROM pedidos_pagos where id_pagos_consolidados=".$regs_pedido[0]['id_pagos_consolidados']." and forma_pago='Credito'";
    $array_pago_credito=$almacen->ObtenerFilasBySqlSelect($sql);

    $sql="SELECT sum(monto_pagado) as total_deposito, nro_tarjeta, id_banco FROM pedidos_pagos where id_pagos_consolidados=".$regs_pedido[0]['id_pagos_consolidados']." and forma_pago='Deposito'";
    $array_pago_deposito=$almacen->ObtenerFilasBySqlSelect($sql);



    $efectivo=$array_pago_efectivo[0]['total_efectivo'];
    $tarjeta=$array_pago_tarjeta[0]['total_tarjeta'];
    $efectivo2=$array_pago_efectivo2[0]['total_efectivo2'];
    $ticket=$array_pago_ticket[0]['total_ticket'];
    $retencioniva1=$array_pago_retencioniva[0]['total_retencioniva'];
    $retencioniva11x1000=$array_pago_retencioniva1x1000[0]['total_retencioniva1x1000'];
    $credito3=$array_pago_credito[0]['total_credito'];
    $deposito=$array_pago_deposito[0]['total_deposito'];
    $nro_deposito=$array_pago_deposito[0]['nro_tarjeta'];
    $banco_deposito=$array_pago_deposito[0]['id_banco'];

    $efectivo_factura=$array_pago_efectivo[0]['total_efectivo'];
    $tarjeta_factura=$array_pago_tarjeta[0]['total_tarjeta'];
    $efectivo2_factura=$array_pago_efectivo2[0]['total_efectivo2'];
    $ticket_factura=$array_pago_ticket[0]['total_ticket'];
    $retencioniva1_factura=$array_pago_retencioniva[0]['total_retencioniva'];
    $retencioniva11x1000_factura=$array_pago_retencioniva1x1000[0]['total_retencioniva1x1000'];
    $credito3_factura=$array_pago_credito[0]['total_credito'];
    $deposito_factura=$array_pago_deposito[0]['total_deposito'];


    $cheque=0;
    $otro=0;
    if ($nro_deposito=='') {
        $nro_deposito=0;
    }
    if ($banco_deposito=='') {
        $banco_deposito=0;
    }
    if ($deposito=='') {
        $deposito=0;
        $deposito_factura=0;

    }
    $total=$efectivo+$tarjeta+$efectivo2+$ticket+$retencioniva1+$retencioniva11x1000+$credito3+$deposito;

    $archivo = "C:\IntTFHKA\ArchivoFactura.txt";
    $fp = fopen($archivo, "w");

    $string = "";
    $write = fputs($fp, $string);

    //Cabecera de la Factura
    $nombreyapellido=$array_cliente[0]['nombre'];
    $direccion=$array_cliente[0]['direccion'];
    $telefono=$array_cliente[0]['telefonos'];
    $nombre = strlen(trim($nombreyapellido)) <= 40 ? trim($nombreyapellido) : substr(trim($nombreyapellido), 0, 40);
    $direccion = strlen(trim($direccion)) <= 40 ? trim($direccion) : substr(trim($direccion), 0, 40);
    $telefono = strlen(trim($telefono)) <= 40 ? trim($telefono) : substr(trim($telefono), 0, 40);
    $string = "iS*{$nombre}\n";
    #$string .= "i00" . "" . "\n";
    $string .= "iR*{$array_cliente[0]['rif']}\n";
    $string .= $direccion != "" ? "i01DIRECCION: {$direccion}\n" : "";
    $string .= $telefono != "" ? "i02TELEFONO: {$telefono}\n" : "";
    $string .= $nro_pedido != "" ? "i03PEDIDO: {$nro_pedido}\n" : "";
    $write = fputs($fp, utf8_encode($string));

    //Productos en la factura
    foreach ($regs_pedido as $value) 
    {
        if($value['_item_piva']==0){
            $iva=" ";
        }elseif ($value['_item_piva']==12) {
            $iva="!";
        }elseif ($value['_item_piva']==8) {
            $iva='"';
        }

        //echo $value["_item_preciosiniva"]; 
        $p = explode(".", $value["_item_preciosiniva"]);
        $precio = (string) $p[0] . $p[1];
        $precio = str_pad($precio, 10, "0", STR_PAD_LEFT);

        $cantidad = explode(".", $value["_item_cantidad"]);
        $cantidad = str_pad((string) $cantidad[0], 5, "0", STR_PAD_LEFT) . str_pad((string) $cantidad[1], 3, "0", STR_PAD_RIGHT);
        $cantidad = str_pad($cantidad, 8 - strlen($cantidad), "0", STR_PAD_LEFT);

        $descripcion = trim($value["_item_descripcion"]);

        $string = $iva . $precio . $cantidad . $descripcion . "\n";
        $write = fputs($fp, utf8_encode($string));
    }
    

//Metodos de Pago

if ($total <= $efectivo || $total <= $cheque || $total <= $tarjeta || $total <= $deposito || $total <= $efectivo2 || $total <= $ticket || $total <= $retencioniva1 || $total <= $retencioniva11x1000 || $total <= $credito3) {
        $medio_pago = "1"; /* Pago directo */
        if ($total <= $efectivo) {
            $medio_pago .= "01" . "\n";
        } else if ($total <= $retencioniva11x1000 ) {
            $medio_pago .= "03" . "\n";
        } else if ($total <= $retencioniva1) {
            $medio_pago .= "04" . "\n";
        } else if ($total <= $credito3) {
            $medio_pago .= "06" . "\n";
        } else if ($total <= $cheque) {
            $medio_pago .= "05" . "\n";
        } else if ($total <= $tarjeta) {
            $medio_pago .= "10" . "\n";
        } else if ($total <= $deposito) {
            $medio_pago .= "08" . "\n";
          } else if ($total <= $efectivo2) {
            $medio_pago .= "02" . "\n";
          }else if ($total <= $ticket) {
            $medio_pago .= "13" . "\n";
          }
        $string = $medio_pago;
    } else {
        /* Pago parcial */
        $medio_pago = "";
        if ($efectivo > 0) {
            $efectivo = number_format($efectivo, 2, ",", "");
            $efectivo = explode(",", $efectivo);
            $efectivo = (string) $efectivo[0] . $efectivo[1];
            $efectivo = str_pad($efectivo, 12, "0", STR_PAD_LEFT);
            $medio_pago .= "2" . "01" . $efectivo . "\n";
        }
        if ($efectivo2 > 0) {
            $efectivo2 = number_format($efectivo2, 2, ",", "");
            $efectivo2 = explode(",", $efectivo2);
            $efectivo2 = (string) $efectivo2[0] . $efectivo2[1];
            $efectivo2 = str_pad($efectivo2, 12, "0", STR_PAD_LEFT);
            $medio_pago .= "2" . "02" . $efectivo2 . "\n";
        }
        //cambios walter
        if ($retencioniva11x1000 > 0) {
            $retencioniva11x1000 = number_format($retencioniva11x1000, 2, ",", "");
            $retencioniva11x1000 = explode(",", $retencioniva11x1000);
            $retencioniva11x1000 = (string) $retencioniva11x1000[0] . $retencioniva11x1000[1];
            $retencioniva11x1000 = str_pad($retencioniva11x1000, 12, "0", STR_PAD_LEFT);
            $medio_pago .= "2" . "03" . $retencioniva11x1000 . "\n";
        }
        if ($retencioniva1 > 0) {
            $retencioniva1 = number_format($retencioniva1, 2, ",", "");
            $retencioniva1 = explode(",", $retencioniva1);
            $retencioniva1 = (string) $retencioniva1[0] . $retencioniva1[1];
            $retencioniva1 = str_pad($retencioniva1, 12, "0", STR_PAD_LEFT);
            $medio_pago .= "2" . "04" . $retencioniva1 . "\n";
        }
        
        if ($ticket > 0) {
            $ticket = number_format($ticket, 2, ",", "");
            $ticket = explode(",", $ticket);
            $ticket = (string) $ticket[0] . $ticket[1];
            $ticket = str_pad($ticket, 12, "0", STR_PAD_LEFT);
            $medio_pago .= "2" . "13" . $ticket . "\n";
        }

        if ($cheque > 0) {
            $cheque = number_format($_POST["input_totalizar_monto_cheque"], 2, ",", "");
            $cheque = explode(",", $cheque);
            $cheque = (string) $cheque[0] . $cheque[1];
            $cheque = str_pad($cheque, 12, "0", STR_PAD_LEFT);
            $medio_pago .= "2" . "05" . $cheque . "\n";
        }
        if ($tarjeta > 0) {
            $tarjeta = number_format($tarjeta, 2, ",", "");
            /* $deposito = $_POST["input_totalizar_monto_deposito"];#number_format($_POST["input_totalizar_monto_deposito"], 2, ",", "");
              $otro = $_POST["opt_otrodocumento"];#number_format($_POST["opt_otrodocumento"], 2, ",", ""); */
            $tarjeta = explode(",", $tarjeta);
            $tarjeta = (string) $tarjeta[0] . $tarjeta[1];
            $tarjeta = str_pad($tarjeta, 12, "0", STR_PAD_LEFT);
            $medio_pago .= "2" . "10" . $tarjeta . "\n";
        }
        if ($deposito > 0) {
            //$tarjeta = number_format($_POST["input_totalizar_monto_tarjeta"], 2, ",", "");
            $deposito = $_POST["input_totalizar_monto_deposito"];#number_format($_POST["input_totalizar_monto_deposito"], 2, ",", "");
            /*  $otro = $_POST["opt_otrodocumento"];#number_format($_POST["opt_otrodocumento"], 2, ",", ""); */
            $deposito = explode(",", $deposito);
            $deposito = (string) $deposito[0] . $deposito[1];
            $deposito = str_pad($deposito, 12, "0", STR_PAD_LEFT);
            $medio_pago .= "2" . "08" . $deposito . "\n";
        }
        $string = $medio_pago;

}
    //echo $medio_pago; exit();
$descuento = number_format($array_descuento[0]['totalizar_pdescuento_global'], 2, ",", "");
$descuento = explode(",", $descuento);
$descuento = (string) $descuento[0] . $descuento[1];
$descuento = str_pad($descuento, 4, "0", STR_PAD_LEFT);
    $nro_pedido = str_pad($nro_pedido, 12, "0", STR_PAD_LEFT);
    $string = "3\np-".$descuento."\ny".$nro_pedido."\n".$string;
    $write = fputs($fp, utf8_encode($string));
    fclose($fp);

    /*
    $lineas_escritas = $itObj->SendFileCmd($archivo);
    $imprimio=$itObj->ImpresoraEstado();
    if($imprimio!=0){
        $erroImpresora=1;
    }
    */

    //$itObj->UploadStatusCmd('S1');
    //$status=$itObj->CheckFprinter1();
    //echo $status; exit();
    $status=1;
    if($status==0)
    {
        echo "<script type='text/javascript'>alert('!Impresora Desconectada o Apagada, Favor encender y Resetear!');</script>";
        echo "<script>window.close();</script>";
    }
    elseif($status==1)
    {

        //$itObj->UploadStatusCmd('S1');
        //include_once("../../libs/php/clases/tfhka/implementacion_tfhka.php");
        //$contenido_s1 = getStatusInformativo();
        //$nro_factura_fiscal = substr($contenido_s1, 21, 8);
        //$lineas_escritas = $itObj->SendFileCmd($archivo);

        $sql="UPDATE kardex_almacen SET estado='Facturado', facturado=1  where nro_factura in (".$facturas.")";
        $array_pago_ticket=$almacen->Execute2($sql);
        $id_usuario=$login->getIdUsuario();

        $sql="INSERT INTO `factura`(`cod_factura`, `cod_factura_fiscal`, `nroz`, `impresora_serial`, `id_cliente`, `cod_vendedor`, `fechaFactura`, `subtotal`, `descuentosItemFactura`, `montoItemsFactura`, `ivaTotalFactura`, `TotalTotalFactura`, `cantidad_items`, `totalizar_sub_total`, `totalizar_descuento_parcial`, `totalizar_total_operacion`, `totalizar_pdescuento_global`, `totalizar_descuento_global`, `totalizar_base_imponible`, `totalizar_monto_iva`, `totalizar_total_general`, `totalizar_total_retencion`, `formapago`, `cod_estatus`, `fecha_pago`, `fecha_creacion`, `usuario_creacion`, `money`, `cesta_clap`, `facturacion`) 
        SELECT b.id, '".$nro_factura_fiscal."', a.nroz, '".$impresora_serial."', a.id_cliente, ".$id_usuario.", b.fecha_creacion, sum(a.subtotal), sum(descuentosItemFactura), sum(montoItemsFactura), sum(ivaTotalFactura), sum(TotalTotalFactura), sum(cantidad_items), sum(totalizar_sub_total), sum(totalizar_descuento_parcial), sum(totalizar_total_operacion), sum(totalizar_pdescuento_global), sum(totalizar_descuento_global), sum(totalizar_base_imponible), sum(totalizar_monto_iva), sum(totalizar_total_general), sum(totalizar_total_retencion), `formapago`, `cod_estatus`,CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, `usuario_creacion`, '".$money[0]['money']."', `cesta_clap`, `facturacion`  FROM `despacho_new` as a inner join pedido_facturas_consolidadas_maestra as b on a.id_pagos_consolidados=b.id WHERE b.id='".$regs_pedido[0]['id_pagos_consolidados']."';
        ";
        
        $insertar_factura=$almacen->Execute2($sql);
        $id_facturaTrans = $almacen->getInsertID();
        $sql=
        "
            SELECT ".$id_facturaTrans.", `id_item`, `_item_almacen`, `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva`, `_item_descuento`, `_item_montodescuento`, `_item_piva`, `_item_totalsiniva`, `_item_totalconiva`, ".$id_usuario.", c.fecha_creacion FROM `despacho_new_detalle` c, `despacho_new` b,  pedido_facturas_consolidadas_maestra as d
            WHERE b.id_factura=c.id_factura
            AND b.id_pagos_consolidados=d.id and
            d.id='".$regs_pedido[0]['id_pagos_consolidados']."'
        ";

        $detalles=$almacen->ObtenerFilasBySqlSelect($sql);
        foreach ($detalles as $key => $value) 
        {
            $sql=
            "
                INSERT INTO `factura_detalle`(`id_factura`, `id_item`, `_item_almacen`, `_item_descripcion`, `_item_cantidad`, `_item_preciosiniva`, `_item_descuento`, `_item_montodescuento`, `_item_piva`, `_item_totalsiniva`, `_item_totalconiva`, `usuario_creacion`, `fecha_creacion`) 
                values ( ".$id_facturaTrans.", ".$value['id_item'].", ".$value['_item_almacen'].", '".$value['_item_descripcion']."', ".$value['_item_cantidad'].", ".$value['_item_preciosiniva'].", ".$value['_item_descuento'].", ".$value['_item_montodescuento'].", ".$value['_item_piva'].", ".$value['_item_totalsiniva'].", ".$value['_item_totalconiva'].", ".$id_usuario.", '".$value['fecha_creacion']."')";
            $insertar_factura_detalle=$almacen->Execute2($sql);    
        }

        

if($efectivo_factura==''){
$efectivo_factura=0;
}
if($tarjeta_factura==''){
$tarjeta_factura=0;
}
if($ticket_factura==''){
$ticket_factura=0;
}
if($retencioniva1_factura=='')
{
    $retencioniva1_factura=0;
}
if($retencioniva11x1000_factura=='')
{
    $retencioniva11x1000_factura=0;
}
if($credito3_factura=='')
{
    $credito3_factura=0;
}
    $sql="
    INSERT INTO `factura_detalle_formapago`(`id_factura`, `totalizar_monto_cancelar`, `totalizar_monto_efectivo`, `totalizar_monto_tarjeta`, `totalizar_tipo_otrodocumento`, `fecha_creacion`, `usuario_creacion`, `totalizar_monto_retencion_iva`, `totalizar_monto_retencion_iva1x1000`, `totalizar_monto_credito2`,`totalizar_monto_deposito`, `totalizar_nro_deposito`, `totalizar_banco_deposito`) 
    VALUES (".$id_facturaTrans.",".$total.",".$efectivo_factura.",".$tarjeta_factura.",".$ticket_factura.", CURRENT_TIMESTAMP, ".$id_usuario.", ".$retencioniva1_factura.", ".$retencioniva11x1000_factura.", ".$credito3_factura.", ".$deposito_factura.", ".$nro_deposito.", ".$banco_deposito.")";
    $array_pago_ticket=$almacen->Execute2($sql);
    //$rs5=$conn->DB_Consulta($sql);  
    echo "
    <script type='text/javascript'>
    alert('!Factura Impresa!');
    window.open('../../reportes/rpt_factura.php?codigo=".$regs_pedido[0]['id_pagos_consolidados']."');
    </script>";

    }
    $itObj->UploadStatusCmd('S1');
   // echo "<script>window.close();</script>";
    //obtener la factura de la impreso fiscal
    $archivo=fopen("C:\IntTFHKA\Status.txt", "r");
    $linea=fgets($archivo);
    fclose($archivo);
    $nrofactura = substr($linea, 21, 8);
    $sql="update factura set cod_factura_fiscal='".$nrofactura."' where id_factura=".$id_facturaTrans;
    $updatefacturafiscal=$almacen->Execute2($sql);
    //fin obtener factura

}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
        <title><? echo $titulo_admin; ?> </title>
        <link rel="shortcut icon" href="images/favicon.ico" />                
        <link href="css/global_admin.css" rel="stylesheet" type="text/css" />
        
        <script language="JavaScript" type="text/javascript" src="../../libs/js/funciones.js"></script>
        
        <script language="javascript" src="include/zhock.js"></script>
        <script type="text/javascript" src="include/prototype.js"></script>        
        
        <script>
            
            function guardar2() {
                var coma=document.getElementById('monto2').value;
                var coma2=document.getElementById('monto4').value;
                
                var valor=coma.replace(",","");
                
                var valor2=coma2.replace(".","");
                valor2=valor2.replace(",",".")*1;
                
                if(document.getElementById('forma_pago').value=="Tarjeta") {
                    if(valor<=valor2) {
                        if(valor>0) {
                            if(document.getElementById('nro_tarjeta').value!="") {
                                if(document.getElementById('tipo_tarjeta').value!="x999") {
                                    if(document.getElementById('id_banco').value!="x999") {
                                        document.getElementById('buscar').value ="1";
                                        document.forms[0].submit();
                                    }else alert('Debe Seleccionar el Banco');     
                                }else alert('Debe Seleccionar el Tipo de Tarjeta');     
                            }else alert('Debe Introducir el Número de Tarjeta');   
                        }else alert('Debe Introducir el Monto a Pagar'); 
                        
                    }else{
                        alert('El Monto a Pagar No Debe Ser Mayor que el Monto Restante');    
                    }                        
                        
                }else{ //Si Forma Pago != 'Tarjeta' --> Efectivo, Nota Crédito y Ticket
                  if(valor>0) {
                    document.getElementById('buscar').value ="1";
                    document.forms[0].submit();
                  }else alert('Debe Introducir el Monto a Pagar');
                }   
               
            }   

            function guardar3() {
                var coma=document.getElementById('monto4').value;
                var valor=coma.replace(".","");
                valor=valor.replace(",",".")*1;
                
                if(valor==0) {   
                    if (confirm('Esta Seguro de Cerrar el Pagado?')){
                        document.getElementById('buscar').value ="2";
                        document.forms[0].submit();
                    }else{
                        return false;
                    }                     
                }else{
                    alert('Error: No ha Culminado de Pagar');
                    return false;
                }    
            }

            function ocultar1(){
                if(document.getElementById('forma_pago').value=='Tarjeta'){
                    document.getElementById('tarjet1').style.visibility='visible';
                    document.getElementById('tarjet2').style.visibility='visible';
                    document.getElementById('tarjet3').style.visibility='visible';
                }else if(document.getElementById('forma_pago').value=='Deposito'){
                    document.getElementById('tarjet4').style.visibility='visible';
                    document.getElementById('tarjet5').style.visibility='visible';              
                }else{
                    document.getElementById('tarjet1').style.visibility='hidden';
                    document.getElementById('tarjet2').style.visibility='hidden';
                    document.getElementById('tarjet3').style.visibility='hidden';
                    document.getElementById('tarjet4').style.visibility='hidden';
                    document.getElementById('tarjet5').style.visibility='hidden';  
                }
            }
        
            function format(input){
                var num = input.value.replace(/\./g,'');
                
                if(!isNaN(num)){
                    
                    if(num.length>2) {
                        num = num.substr(0,num.length-2)+"."+num.substr(num.length-2,2);
                    }else{
                        num = num.toString();
                    }    
                    input.value = num;
                }else{ 
                    alert('Solo se permiten numeros');
                    input.value = input.value.replace(/[^\d\.]*/g,'');
                }
            }      
        
        </script>         
        
    </head>

    <body style="color: #000000;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;background-color: #FFF;margin: 2px;">
        <form name="recibo" id="recibo" method="post">
        <table width="100%" border="0" cellpadding="1" cellspacing="1" align="center" valign="top" class="fondo_tabla01_2">
                    <tr>
                        <th>
                            Pedidos a Facturar
                        </th>
                    </tr>
            </table>
            <?php
            //se coloca aqui los datos del cliente, los movimientos que no han sido facturado
            $sql="SELECT id_transaccion, nro_factura from kardex_almacen, clientes WHERE kardex_almacen.id_cliente=clientes.id_cliente and kardex_almacen.facturado=0  and kardex_almacen.nro_factura<>'' and clientes.id_cliente=".$despacho."";
            $id_transaccion=$almacen->ObtenerFilasBySqlSelect($sql);
            $facturas="";
            foreach ($id_transaccion as $key => $value) 
            {
                $facturas.="'".$value['nro_factura']."', ";
            }
            $facturas=substr($facturas, 0, -2);
            $sql="SELECT coalesce((a.total_monto),0) as monto FROM pedido_facturas_consolidadas_maestra as a, pedidos_pagos as b, despacho_new as c
            where a.id=b.id_pagos_consolidados and a.id=c.id_pagos_consolidados and cod_factura in (".$facturas.") group by a.id";

            $rs2=$almacen->ObtenerFilasBySqlSelect($sql);
            $rs=0;
            foreach ($rs2 as $key => $value) 
            {
                $rs+=$value['monto'];
            }
            $monto=0;
            foreach ($id_transaccion as $key => $value)
            {
                $sql="SELECT coalesce(sum(monto_pagado),0) as monto FROM pedidos_pagos
                where nro_pedido=".$value['id_trasaccion']."";
                $rs4=$almacen->ObtenerFilasBySqlSelect($sql);

                //nr pedido
                $sql="SELECT *,kardex_almacen.estado as estatus from kardex_almacen, clientes WHERE kardex_almacen.id_cliente=clientes.id_cliente and kardex_almacen.facturado=0 and id_transaccion=".$value['id_transaccion']." and kardex_almacen.nro_factura<>''";
                $datos_cliente=$almacen->ObtenerFilasBySqlSelect($sql);

                $sql="SELECT * from despacho_new WHERE cod_factura=".$datos_cliente[0]['nro_factura']."";
                $datos_factura=$almacen->ObtenerFilasBySqlSelect($sql);
                
                $sql="SELECT totalizar_total_general as total FROM despacho_new_detalle,despacho_new
                WHERE cod_factura=".$datos_cliente[0]['nro_factura']."
                AND despacho_new_detalle.id_factura=despacho_new.id_factura";
                $total_factura=$almacen->ObtenerFilasBySqlSelect($sql);
                $datos_factura[0]['totalizar_total_general']=$total_factura[0]['total'];

                $monto+=$datos_factura[0]['totalizar_total_general']-$rs4[0]['monto'];
                
                ?>
                <table width="100%" border="0" cellpadding="1" cellspacing="1" align="center" valign="top" class="fondo_tabla01_2">
                    <tr>
                        <td width="16%" align="right" >Nro. Pedido:&nbsp;</td>
                        <td width="84%" align="left" >
                            <input name="nro_pedido2[]" id="nro_pedido2" type="text" size="10" title="Nro. Pedido" value="<?php echo $datos_cliente[0]['id_transaccion']; ?>" readonly="readonly"/>
                        </td>                                
                    </tr>   
                    <!-- <tr>
                        <td width="16%" align="right" >Beneficiario:&nbsp;</td>
                        <td width="84%" align="left" >
                        <input name="cedula2" id="cedula2" type="text" size="10" title="Cédula" value="<?php echo $datos_cliente[0]['rif']; ?>" readonly="readonly"/>
                            <input name="nombre2" id="nombre2" type="text" size="50" title="Nombre" value="<?php echo $datos_cliente[0]['nombre']; ?>" readonly="readonly"/>
                        </td>                                 
                    </tr>-->   
                    <tr>
                        <td width="16%" align="right" >Monto Pedido:&nbsp;</td>
                        <td width="84%" align="left" >
                        <input name="monto3[]" id="monto3" type="text" size="10" title="Monto" value="<?php echo number_format($datos_factura[0]['totalizar_total_general'],2,",","."); ?>" readonly="readonly"/>&nbsp;Bs.
                        </td>
                    </tr>   
                   
                    <?php if($monto<0){ ?>
                    <tr>
                        <td width="16%" align="right" >Vuelto:&nbsp;</td>
                        <td width="84%" align="left" >
                        <input name="monto3[]" id="monto3" type="text" size="10" title="Monto" value="<?php echo number_format($monto,2,",","."); ?>" readonly="readonly"/>&nbsp;Bs.
                        </td>
                    </tr>
                    <?php   } ?>
                    
            </table>
            <br>
            <?php } ?>
            <?php 
            $epsilon=0.001;
            if(abs($monto - $rs) < $epsilon)
            $monto=0;
            else
            $monto=($monto - $rs);
            if($monto>0){ ?>
            <table width="100%" border="0" cellpadding="1" cellspacing="1" align="center" valign="top" class="fondo_tabla01_2">
                    <tr>
                        <td width="16%" align="right" ><b>Total :</b>&nbsp;</td>
                        <td width="84%" align="left" >
                            <input name="monto3[]" id="monto3" type="text" size="10" title="Monto" value="<?php echo number_format($monto+$rs,2,",","."); ?>" readonly="readonly"/>&nbsp;Bs.
                        </td>
                    </tr>
                    <tr>
                        <td width="16%" align="right" ><b>Total pagado:</b>&nbsp;</td>
                        <td width="84%" align="left" >
                            <input name="monto3[]" id="monto3" type="text" size="10" title="Monto" value="<?php echo number_format($rs,2,",","."); ?>" readonly="readonly"/>&nbsp;Bs.
                        </td>
                    </tr>
                    <tr>
                        <td width="16%" align="right" ><b>Total a pagar:</b>&nbsp;</td>
                        <td width="84%" align="left" >
                            <input name="monto3[]" id="monto3" type="text" size="10" title="Monto" value="<?php echo number_format($monto,2,",","."); ?>" readonly="readonly"/>&nbsp;Bs.
                        </td>
                    </tr>
            </table>
            <?php } ?>  


<table width="100%" border="0" cellpadding="1" cellspacing="1" align="center" valign="top" class="fondo_tabla01_2">
<?php if($monto>0){ ?>
                                        <tr>
                                            <td width="15%" align="right">Forma de Pago:&nbsp;</td>
                                            <td width="40%" align="left">
                                                <select name="forma_pago" id="forma_pago" title="Forma de Pago" style="width:195px" onchange="javascript:ocultar1()">
                                                  <option value="Tarjeta" selected="selected">Tarjeta</option>
                                                  <option value="Efectivo">Efectivo</option>
                                                  <option value="Nota Credito" >Nota Crédito</option>
                                                  <option value="Ticket" >Ticket</option>
                                                  <option value="Deposito" >Deposito</option>
                                                  <!--<option value="RetencionIva" <?php if ($forma_pago=='RetencionIva'){echo "selected=\"selected\"";} ?>>Retencion IVA</option>-->
                                                  <!--<option value="Retencion1x1000" <?php if ($forma_pago=='Retencion1x1000'){echo "selected=\"selected\"";} ?>>Retención 1x1000</option>-->
                                                  <option value="Credito" >Credito</option>
                                                </select>                                                    
                                            </td>
                                            <?php if($monto>0){ ?>
                                            <td width="45%" align="left" rowspan="2">
                                                      <input name="guardar1" type="submit" id="guardar1" style="width:125px; height:40px" value="Agregar Pago"/>
                                            </td> 
                                            <?php }else{ ?>
                                                <td width="45%" align="left" rowspan="2">
                                                      <input name="guardar1" type="button" id="guardar1" style="width:125px; height:40px" onclick="javascript:guardar3()" value="Cerrar Pagado"/>
                                                </td> 
                                            <?php } ?>                                                                           
                                        </tr>

                                        <tr>
                                            <td width="15%" align="right">Monto a Pagar:&nbsp;</td>
                                            <td width="40%" align="left">
                                                <input name="monto2" id="monto2" type="text" size="22" title="Monto a Pagar" onkeyup="format(this)" onchange="format(this)"/>&nbsp;Bs.
                                            </td>  
                                        </tr>
                                        <!--agregando retenciones-->
                                        <tr id="retener" style="display:none; margin-left: 20px;" >
                                            <td width="15%" align="right">Nro. Retencion IVA:&nbsp;</td>
                                            
                                            <td width="40%" align="left">
                                                <input name="nro_irpf" id="nro_irpf" type="text" size="22" title="Nro. de Retencion"/>
                                            </td>  
                                        </tr>
                                        <tr id="retener1000" style="display:none;">
                                            <td width="15%" align="right">Nro. Retención 1x1000:&nbsp;</td>
                                            
                                            <td width="40%" align="left">
                                                <input name="nro_1x1000" id="nro_1x1000" type="text" size="22" title="Nro. de 1x1000"/>
                                            </td>  
                                        </tr>
                                        
                                        <tr id="tarjet1" name="tarjet1">
                                            <td width="15%" align="right" >Nro. Tarjeta:&nbsp;</td>
                                            <td width="40%" align="left" >
                                                <input name="nro_tarjeta" id="nro_tarjeta" type="text" size="22" title="Nro. Trjeta" onKeyPress="return(formato_campo(this,event,1))"/>
                                            </td>                                
                                        </tr>                                        

                                        <tr id="tarjet2" name="tarjet2">
                                            <td width="15%" align="right">Tipo Tarjeta:&nbsp;</td>
                                            <td width="40%" align="left">
                                                <select name="tipo_tarjeta" id="tipo_tarjeta" title="Tipo Tarjeta" style="width:195px" >
                                                  <option value="x999" >Seleccione...</option>
                                                  <option value="Debito" >Debito</option>
                                                  <option value="Credito" >Credito</option>
                                                  <option value="Alimentación" >Alimentación</option>
                                                </select>                                                    
                                            </td>                                
                                        </tr>

                                        <tr id="tarjet3" name="tarjet3">
                                            <td width="15%" align="right">Banco:&nbsp;</td>
                                            <td width="40%" align="left">
                                                <select name="id_banco" id="id_banco" title="Banco" style="width:195px" >
                                                   <option value="x999">Seleccione...</option>
                                                   <?php
                                                   foreach ($bancos as $value) 
                                                   {
                                                        echo "<option value=".$value['cod_banco'].">".$value['descripcion']."</option>";
                                                    }
                                                   ?>
                                                </select>   
                                            </td>                                
                                        </tr>
                                        <tr id="tarjet4" name="tarjet4">
                                            <td width="15%" align="right" >Nro. Deposito:&nbsp;</td>
                                            <td width="40%" align="left" >
                                                <input name="nro_tarjeta" id="nro_tarjeta" type="text" size="22" title="Nro. Trjeta" onKeyPress="return(formato_campo(this,event,1))"/>
                                            </td>                                
                                        </tr> 
                                        <tr id="tarjet5" name="tarjet5">
                                            <td width="15%" align="right">Banco:&nbsp;</td>
                                            <td width="40%" align="left">
                                                <select name="id_banco" id="id_banco" title="Banco" style="width:195px" >
                                                   <option value="x999">Seleccione...</option>
                                                   <?php
                                                   foreach ($bancos as $value) 
                                                   {
                                                        echo "<option value=".$value['cod_banco'].">".$value['descripcion']."</option>";
                                                    }
                                                   ?>
                                                </select>   
                                            </td>                                
                                        </tr>
                                        

                                        
                                        <tr>
                                            <td width="100%" colspan="2">&nbsp;</td>
                                        </tr>  


                                        <?php 
                                        }
                                        else
                                        { ?>
                                        <tr>
                                        <?php if ($datos_cliente[0]['facturado']==0){?>


                                        <td colspan="2" align="center">
                                            
                                               <input name="imprimir" type="submit" id="imprimir" style="width:125px; height:40px" value="Imprimir Factura"/>
                                        </td>
                                            <?php
                                            }else{
                                            ?>
                                            <td colspan="2" align="center">
                                            Cliente Facturado
                                        </td>
                                        
                                        </tr>
                                        <?php 
                                        }

                                        }?>
                                    </table>
                                    
                                    </form>
<script>
            
            function guardar2() {
                var coma=document.getElementById('monto2').value;
                var coma2=document.getElementById('monto4').value;
                
                var valor=coma.replace(",","");
                
                var valor2=coma2.replace(".","");
                valor2=valor2.replace(",",".")*1;
                
                if(document.getElementById('forma_pago').value=="Tarjeta") {
                    if(valor<=valor2) {
                        if(valor>0) {
                            if(document.getElementById('nro_tarjeta').value!="") {
                                if(document.getElementById('tipo_tarjeta').value!="x999") {
                                    if(document.getElementById('id_banco').value!="x999") {
                                        document.getElementById('buscar').value ="1";
                                        document.forms[0].submit();
                                    }else alert('Debe Seleccionar el Banco');     
                                }else alert('Debe Seleccionar el Tipo de Tarjeta');     
                            }else alert('Debe Introducir el Número de Tarjeta');   
                        }else alert('Debe Introducir el Monto a Pagar'); 
                        
                    }else{
                        alert('El Monto a Pagar No Debe Ser Mayor que el Monto Restante');    
                    }                        
                        
                }else{ //Si Forma Pago != 'Tarjeta' --> Efectivo, Nota Crédito y Ticket
                  if(valor>0) {
                    document.getElementById('buscar').value ="1";
                    document.forms[0].submit();
                  }else alert('Debe Introducir el Monto a Pagar');
                }   
               
            }   

            function guardar3() {
                var coma=document.getElementById('monto4').value;
                var valor=coma.replace(".","");
                valor=valor.replace(",",".")*1;
                
                if(valor==0) {   
                    if (confirm('Esta Seguro de Cerrar el Pagado?')){
                        document.getElementById('buscar').value ="2";
                        document.forms[0].submit();
                    }else{
                        return false;
                    }                     
                }else{
                    alert('Error: No ha Culminado de Pagar');
                    return false;
                }    
            }

            function ocultar1(){
                if(document.getElementById('forma_pago').value=='Tarjeta'){
                    document.getElementById('tarjet1').style.visibility='visible';
                    document.getElementById('tarjet2').style.visibility='visible';
                    document.getElementById('tarjet3').style.visibility='visible';
                    document.getElementById('tarjet4').style.visibility='hidden';
                    document.getElementById('tarjet5').style.visibility='hidden';
                }else if(document.getElementById('forma_pago').value=='Deposito'){
                    document.getElementById('tarjet4').style.visibility='visible';
                    document.getElementById('tarjet5').style.visibility='visible';
                    document.getElementById('tarjet1').style.visibility='hidden';
                    document.getElementById('tarjet2').style.visibility='hidden';
                    document.getElementById('tarjet3').style.visibility='hidden';          
                }else{
                    document.getElementById('tarjet1').style.visibility='hidden';
                    document.getElementById('tarjet2').style.visibility='hidden';
                    document.getElementById('tarjet3').style.visibility='hidden';
                    document.getElementById('tarjet4').style.visibility='hidden';
                    document.getElementById('tarjet5').style.visibility='hidden';  
                }
                //agregando campos de retenciones
                if(document.getElementById('forma_pago').value=='Retencion1x1000')
                {
                    document.getElementById('retener1000').style.display='table-row';
                }
                else
                {
                    document.getElementById('retener1000').style.display='none';
                }
                if(document.getElementById('forma_pago').value=='RetencionIva')
                {
                    document.getElementById('retener').style.display='table-row';
                }
                else
                {
                    document.getElementById('retener').style.display='none';
                }

            }
        
            function format(input){
                var num = input.value.replace(/\./g,'');
                
                if(!isNaN(num)){
                    
                    if(num.length>2) {
                        num = num.substr(0,num.length-2)+"."+num.substr(num.length-2,2);
                    }else{
                        num = num.toString();
                    }    
                    input.value = num;
                }else{ 
                    alert('Solo se permiten numeros');
                    input.value = input.value.replace(/[^\d\.]*/g,'');
                }
            }    

                window.onload = function() {
                    //funciones a ejecutar

                if(document.getElementById('forma_pago').value=='Tarjeta'){
                    document.getElementById('tarjet1').style.visibility='visible';
                    document.getElementById('tarjet2').style.visibility='visible';
                    document.getElementById('tarjet3').style.visibility='visible';
                    document.getElementById('tarjet4').style.visibility='hidden';
                    document.getElementById('tarjet5').style.visibility='hidden';  
                }else if(document.getElementById('forma_pago').value=='Deposito'){
                    document.getElementById('tarjet4').style.visibility='visible';
                    document.getElementById('tarjet5').style.visibility='visible';              
                }else{
                    document.getElementById('tarjet1').style.visibility='hidden';
                    document.getElementById('tarjet2').style.visibility='hidden';
                    document.getElementById('tarjet3').style.visibility='hidden';
                    document.getElementById('tarjet4').style.visibility='hidden';
                    document.getElementById('tarjet5').style.visibility='hidden';  
                }

                };

        
        </script>    