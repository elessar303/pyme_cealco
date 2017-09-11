<?php
require_once "../../libs/php/clases/transferencia.php";
$comunes = new Comunes();

if(isset($_POST["opt"])==true){

$opt_ = $_POST["opt"]; // guardarCheque: Guardar un cheque por una cuenta por pagar, guardarChequeTer: guardar un cheque por proveedor
if($_POST["cam_id_proveedor"]==0){
	echo json_encode(array(
		"success" => false,
		"msg"     => "Error.",
		"sql"     => " Debe cargar el beneficiario"
	));
	exit;
}


if($opt_=="guardarTransferencia"){
	$transf = new Transferencia();
	$transf->BeginTrans();
	$monto_debito  = 0;
	$monto_credito = 0;
	$tipo=$_POST["tipo_transac"];
	$monto        = $_POST["cam_monto_pago"];
	$ref          = $_POST["cam_numero_compra"];
	$id_proveedor = $_POST["cam_id_proveedor"];
	$concepto     = $_POST["cam_concepto_cheque"];
	$cod_banco=$_POST["codigo_banco"];
	$transferencia_numero=$_POST["cam_transferencia_numero"];
	$ref_formulario=$_POST["cam_referencia"];
	$cod_edocuenta=$_POST["cam_cod_edocuenta"];

	if($tipo=="Transferencia Proveedores")
		$tipo=1;
	elseif($tipo=="Cheque de Gerencia")
		$tipo=2;
		

	list($dia,$mes,$anio) = explode("/", $_POST["cam_fechapago"]);
	$fecha_pago = $anio."-".$mes."-".$dia;
	
	$detalle_banco = $transf->ObtenerFilasBySqlSelect("
	SELECT 
	banco.*,
	terbandet.cod_tesor_bandodet,
	terbandet.nro_cuenta,
	terbandet.cuenta_contable
	FROM tesor_bancodet terbandet
	inner join banco on
	banco.cod_banco = terbandet.cod_banco   
	where banco.cod_banco=".$cod_banco);

	$sql = "
	INSERT INTO transferencia (
	transferencia_pk,
	transferencia_numero,
	tesor_bancodet_fk,
	ref,
	proveedor_fk,
	estatus,
	monto,
	concepto,
	fecha,
	cxp_edocta_det_fk,
	fecha_creacion,
	usuario_creacion,
	tipo_transaccion)
 	VALUES (NULL,'".$transferencia_numero."','".$detalle_banco[0]["cod_tesor_bandodet"]."','".$ref."','".$id_proveedor."','2', '".$monto."','".$concepto ."','".$fecha_pago."', '".$cod_edocuenta."', CURRENT_TIMESTAMP, '".$login->getUsuario()."','$tipo');";
	$transf->ExecuteTrans($sql);

	$cod_transferencia = $transf->getInsertID();

	$sql = "
	INSERT INTO transf_bauche_det (
	transf_bauchedet_pk,
	transferencia_fk,
	transferencia_numero,
	monto,
	tipo,
	fecha,
	descripcion,
	fecha_creacion,
	usuario_creacion,
	cuenta_contable)
	VALUES (NULL,'$cod_transferencia','".$transferencia_numero."', '".$monto."', 'c', '".$fecha_pago."', '".$detalle_banco[0]["descripcion"]." Cta. ".$detalle_banco[0]["nro_cuenta"]."', CURRENT_TIMESTAMP, '".$login->getUsuario()."','".$detalle_banco[0]["cuenta_contable"]."');";
	$transf->ExecuteTrans($sql);
	$monto_debito +=$monto;

	/*
	* </Asiento de monto cheque contra banco.>
	*/

	$prov = $transf->ObtenerFilasBySqlSelect("
	SELECT descripcion as beneficiario, cuenta_contable
	FROM  proveedores 
	WHERE id_proveedor = '".$id_proveedor."'");
	$cuenta_contableProv  = $prov[0]["cuenta_contable"];
	$proveedor = $prov[0]["beneficiario"];

	$sql = "
	INSERT INTO transf_bauche_det (
	transf_bauchedet_pk,
	transferencia_fk,
	transferencia_numero,
	monto,
	tipo,
	fecha,
	descripcion,
	fecha_creacion,
	usuario_creacion,
	cuenta_contable)
	VALUES (NULL,'$cod_transferencia','".$transferencia_numero."', '".$monto."', 'd', '".$fecha_pago."', '".$proveedor."', CURRENT_TIMESTAMP, '".$login->getUsuario()."','".$cuenta_contableProv."');";
	$transf->ExecuteTrans($sql);

	/*
	* <CAMBIAMOS EL ESTADO DE LA CUENTA POR COBRAR Y LA DEL ASIENTO DEL CHEQUE A LA LETRA <<X>> >
	*/
	$update  = "
	update cxp_edocuenta inner join cxp_edocuenta_detalle on
	cxp_edocuenta_detalle.cod_edocuenta = cxp_edocuenta.cod_edocuenta
	and cxp_edocuenta_detalle.marca = 'P'
	set
	cxp_edocuenta_detalle.marca = 'X'
	where cxp_edocuenta_detalle.numero = '".$ref."'
	";
	$transf->ExecuteTrans($update);

// 	$update  = "
// 	update cxp_edocuenta inner join cxp_edocuenta_detalle on
// 	cxp_edocuenta_detalle.cod_edocuenta = cxp_edocuenta.cod_edocuenta
// 	and cxp_edocuenta_detalle.marca = 'P'
// 	set
// 	cxp_edocuenta.marca = 'X',
// 	cxp_edocuenta_detalle.marca = 'X'
// 	where cxp_edocuenta_detalle.numero = '".$ref."'
// 	";
// 	$transf->ExecuteTrans($update);

	/*
	* </CAMBIAMOS EL ESTADO DE LA CUENTA POR COBRAR Y LA DEL ASIENTO DEL CHEQUE A LA LETRA <<X>> >
	*/
// 
// 
// 
// 	/*
// 	* <grabamos en movimiento bancario>
// 	*/
	//9 = Transferencia Proveedores
	$codigo_tipo_movimiento=9;
	$sql = "
	INSERT INTO `movimientos_bancarios` (
	`cod_tesor_bancodet` ,
	`fecha_movimiento` ,
	`tipo_movimiento` ,
	`numero_movimiento` ,
	`monto` ,
	`concepto` ,
	`contab` ,
	`estado` ,
	`cod_conciliacion` ,
	`fecha_creacion` ,
	`usuario_creacion`
	)
	VALUES (
		'".$detalle_banco[0]["cod_tesor_bandodet"]."', '".$fecha_pago."', '".$codigo_tipo_movimiento."', '".$transferencia_numero."', '".$monto."', '".$proveedor."', 'no', NULL , NULL ,
	CURRENT_TIMESTAMP , '".$login->getUsuario()."'
	);
	";
	$transf->ExecuteTrans($sql);

	/*
	* </grabamos en movimiento bancario>
	*/
	
	if($transf->errorTransaccion==0){
		echo json_encode(array(
		"success" => false,
		"msg"     => "¡Error al tratar de Registrar la Transacción!.",
		"sql"     => $update
		));
	}

	if($transf->errorTransaccion==1){
		echo json_encode(array(
		"success" => true,
		"msg"     => "¡Transacción Registrada Exitosamente!"
		));
	}
	$transf->CommitTrans($transf->errorTransaccion);
	//$cheque->Com mitTrans(0);
	exit;
}//fin if($opt_=="guardarCheque"){


if($opt_=="guardarChequeTer"){
   $cheque = new Cheque();
    $cheque->BeginTrans();
    $monto_debito  = 0;
    $monto_credito = 0;
    $cod_cheque   = $_POST["codigo_cheque"];
    $monto        = $_POST["cam_monto_pago"];
    $num_cheque   = $_POST["cheque"];
    $ref          = 0;
    $id_proveedor = $_POST["cam_id_proveedor"];
    $concepto     = $_POST["cam_concepto_cheque"];
    list($dia,$mes,$anio) = explode("/", $_POST["cam_fechapago"]);
    $fecha_pago = $anio."-".$mes."-".$dia;

    /*
     * UPDATE A LA TABLA CHEQUE:
     * se asigna proveedor a cheque junto con monto por el cual se emite el cheque.
     */
    $update  = "update cheque set";
    $update .= " monto = '".$monto."',";
    $update .= " ref = '".$ref."',";
    $update .= " id_proveedor = '".$id_proveedor."',";
    $update .= " concepto = '".$concepto."',";
    $update .= " situacion = 'Ac',";
    $update .= " fecha = '".$fecha_pago."'";
    $update .= " where cod_cheque = ".$cod_cheque;
    $cheque->ExecuteTrans($update);
    /*
     * <verificamos si existen impuestos para cargarlos en bauche det_>
     */


    /*
     * <Asiento de monto cheque contra banco.>
     */

     $detalle_banco = $cheque->ObtenerFilasBySqlSelect("
        SELECT
        banco.*,
        terbandet.cod_tesor_bandodet,
        terbandet.nro_cuenta,
        terbandet.cuenta_contable
         FROM  cheque che
         inner join chequera on
           chequera.cod_chequera = che.cod_chequera
         inner join tesor_bancodet terbandet on
           terbandet.cod_tesor_bandodet = chequera.cod_tesor_bandodet
         inner join banco on
           banco.cod_banco = terbandet.cod_banco
           where che.cod_cheque = ".$cod_cheque);

    $sql = "
    INSERT INTO `cheque_bache_det` (
        `cod_cheque`,
        `monto`,
        `tipo`,
        `fecha`,
        `descripcion`,
        `fecha_creacion`,
        `usuario_creacion`,cuenta_contable)
        VALUES (".$cod_cheque.", ".$monto.", 'd', '".$fecha_pago."', '".$detalle_banco[0]["descripcion"]." Cta. ".$detalle_banco[0]["nro_cuenta"]."', CURRENT_TIMESTAMP, '".$login->getUsuario()."','".$detalle_banco[0]["cuenta_contable"]."');";
    $cheque->ExecuteTrans($sql);
    $monto_debito +=$monto;
    /*
     * </Asiento de monto cheque contra banco.>
     */


    $cabEdoCuenta = $cheque->ObtenerFilasBySqlSelect("
 SELECT * FROM proveedores where id_proveedor = '".$id_proveedor."'");

    $cuenta_contableCliente  = $cabEdoCuenta[0]["cuenta_contable"];
    $beneficiario = $cabEdoCuenta[0]["descripcion"];

    $sql = "
    INSERT INTO `cheque_bache_det` (
        `cod_cheque`,
        `monto`,
        `tipo`,
        `fecha`,
        `descripcion`,
        `fecha_creacion`,
        `usuario_creacion`,cuenta_contable)
        VALUES (".$cod_cheque.", ".$monto.", 'c', '".$fecha_pago."', '".$beneficiario."', CURRENT_TIMESTAMP, '".$login->getUsuario()."','".$cuenta_contableCliente."');";
    $cheque->ExecuteTrans($sql);

    /*
     * <grabamos en movimiento bancario>
     */
    // 1 = cheque
    $codigo_tipo_movimiento=1;
    $sql = "
            INSERT INTO `movimientos_bancarios` (
                `cod_tesor_bancodet` ,
                `fecha_movimiento` ,
                `tipo_movimiento` ,
                `numero_movimiento` ,
                `monto` ,
                `concepto` ,
                `contab` ,
                `estado` ,
                `cod_conciliacion` ,
                `fecha_creacion` ,
                `usuario_creacion`
            )
            VALUES (
                 '".$detalle_banco[0]["cod_tesor_bandodet"]."', '".$fecha_pago."', '".$codigo_tipo_movimiento."', '".$num_cheque."', '".$monto."', '".$beneficiario."', 'no', NULL , NULL ,
            CURRENT_TIMESTAMP , '".$login->getUsuario()."'
            );
        ";
     $cheque->ExecuteTrans($sql);
    /*
     * </grabamos en movimiento bancario>
     */


    if($cheque->errorTransaccion==0){
        echo json_encode(array(
           "success" => false,
           "msg"     => "¡Error al tratar de generar el Cheque!.",
           "sql"     => $update
        ));
    }

    if($cheque->errorTransaccion==1){
        echo json_encode(array(
           "success" => true,
           "msg"     => "¡Cheque Generado Exitosamente!"

        ));
    }
    $cheque->CommitTrans($cheque->errorTransaccion);
    //$cheque->Com mitTrans(0);
    exit;
}//fin if($opt_=="guardarChequeTer")

if($opt_=="guardarChequeVariasCxP"){//Si es un cheque para multiples cxp.
    $cheque = new Cheque();
    $cheque->BeginTrans();
    $monto_debito  = 0;
    $monto_credito = 0;
    $cod_cheque   = $_POST["codigo_cheque"];
    $monto        = $_POST["cam_monto_pago"];
    $num_cheque   = $_POST["cheque"];
    $ref          = explode("/",$_POST["cam_numero_compra"]);
    $id_proveedor = $_POST["cam_id_proveedor"];
    $concepto     = $_POST["cam_concepto_cheque"];
    list($dia,$mes,$anio) = explode("/", $_POST["cam_fechapago"]);
    $fecha_pago = $anio."-".$mes."-".$dia;
    
    /*
     * UPDATE A LA TABLA CHEQUE:
     * se asigna proveedor a cheque junto con monto por el cual se emite el cheque.
     */
    $update  = "update cheque set";
    $update .= " monto = '".$monto."',";
    $update .= " ref = '".$_POST["cam_numero_compra"]."',";
    $update .= " id_proveedor = '".$id_proveedor."',";
    $update .= " concepto = '".$concepto."',";
    $update .= " situacion = 'Ac',";
    $update .= " fecha = '".$fecha_pago."'";
    $update .= " where cod_cheque = ".$cod_cheque;
    $cheque->ExecuteTrans($update);

    foreach($ref as $Array_num_compra){
    /*
     * <verificamos si existen impuestos para cargarlos en bauche det_>
     */
    $cxpDet = $cheque->ObtenerFilasBySqlSelect("
    SELECT limp.descripcion, tipoimp.descripcion AS descr_tipo_impuesto,tipoimp.cuenta_contable, timp . *
    FROM `tabla_impuestos` timp
    INNER JOIN lista_impuestos limp ON limp.cod_impuesto = timp.cod_impuesto
    INNER JOIN tipo_impuesto tipoimp ON tipoimp.cod_tipo_impuesto = timp.cod_tipo_impuesto
    WHERE timp.numero_control_factura = '".$Array_num_compra."'
    AND timp.tipo_documento = 'c'");
    foreach ($cxpDet as $registro){
        $sql = "
            INSERT INTO `cheque_bache_det` (
                        `cod_cheque`,
                        `monto`,
                        `tipo`,
                        `fecha`,
                        `descripcion`,
                        `fecha_creacion`,
                        `usuario_creacion`,cuenta_contable)
                        VALUES (".$cod_cheque.", ".$registro["totalizar_monto_retencion"].", 'd', '".$fecha_pago."', '".$registro["descr_tipo_impuesto"]."', CURRENT_TIMESTAMP, '".$login->getUsuario()."','".$registro["cuenta_contable"]."');";
         $cheque->ExecuteTrans($sql);
         $monto_debito +=$registro["totalizar_monto_retencion"];
    }
    /*
     * </verificamos si existen impuestos para cargarlos en bauche det_>
     */
    }//foreach($ref as $Array_num_compra){

    /*
     * <Asiento de monto cheque contra banco.>
     */
     $detalle_banco = $cheque->ObtenerFilasBySqlSelect("
        SELECT
        banco.*,
        terbandet.cod_tesor_bandodet,
        terbandet.nro_cuenta,
        terbandet.cuenta_contable
         FROM `cheque_bache_det` chbdet
         inner join cheque che
           on che.cod_cheque = chbdet.cod_cheque
         inner join chequera on
           chequera.cod_chequera = che.cod_chequera
         inner join tesor_bancodet terbandet on
           terbandet.cod_tesor_bandodet = chequera.cod_tesor_bandodet
         inner join banco on
           banco.cod_banco = terbandet.cod_banco
           where chbdet.cod_cheque = ".$cod_cheque);

    $sql = "
    INSERT INTO `cheque_bache_det` (
        `cod_cheque`,
        `monto`,
        `tipo`,
        `fecha`,
        `descripcion`,
        `fecha_creacion`,
        `usuario_creacion`,cuenta_contable)
        VALUES (".$cod_cheque.", ".$monto.", 'd', '".$fecha_pago."', '".$detalle_banco[0]["descripcion"]." Cta. ".$detalle_banco[0]["nro_cuenta"]."', CURRENT_TIMESTAMP, '".$login->getUsuario()."','".$detalle_banco[0]["cuenta_contable"]."');";
    $cheque->ExecuteTrans($sql);
    $monto_debito +=$monto;
    /*
     * </Asiento de monto cheque contra banco.>
     */

    $SUM_montoCompra = 0;
    foreach($ref as $Array_num_compra){
            $cabEdoCuenta = $cheque->ObtenerFilasBySqlSelect("
         SELECT provee.descripcion as beneficiario,provee.cuenta_contable, cxp.*
        FROM `cxp_edocuenta` cxp
        inner join proveedores provee on
           provee.id_proveedor = cxp.id_proveedor
        WHERE cxp.numero = '".$Array_num_compra."'");
            $montoCompra =              $cabEdoCuenta[0]["monto"];
            $SUM_montoCompra += $montoCompra;
            $cuenta_contableCliente  =  $cabEdoCuenta[0]["cuenta_contable"];
            $beneficiario =             $cabEdoCuenta[0]["beneficiario"];
             /*
              * <CAMBIAMOS EL ESTADO DE LA CUENTA POR COBRAR Y LA DEL ASIENTO DEL CHEQUE A LA LETRA <<X>> >
              */
            $update  = "
            update cxp_edocuenta inner join cxp_edocuenta_detalle on
              cxp_edocuenta_detalle.cod_edocuenta = cxp_edocuenta.cod_edocuenta
             and cxp_edocuenta_detalle.marca = 'P'
             set
             cxp_edocuenta.marca = 'X',
             cxp_edocuenta_detalle.marca = 'X'
            where cxp_edocuenta.numero = '".$Array_num_compra."'
            ";
            $cheque->ExecuteTrans($update);
             /*
              * </CAMBIAMOS EL ESTADO DE LA CUENTA POR COBRAR Y LA DEL ASIENTO DEL CHEQUE A LA LETRA <<X>> >
              */

    }//foreach($ref as $Array_num_compra){


                $sql = "
    INSERT INTO `cheque_bache_det` (
        `cod_cheque`,
        `monto`,
        `tipo`,
        `fecha`,
        `descripcion`,
        `fecha_creacion`,
        `usuario_creacion`,cuenta_contable)
        VALUES (".$cod_cheque.", ".$SUM_montoCompra.", 'c', '".$fecha_pago."', '".$beneficiario."', CURRENT_TIMESTAMP, '".$login->getUsuario()."','".$cuenta_contableCliente."');";
    $cheque->ExecuteTrans($sql);


     /*
     * <grabamos en movimiento bancario>
     */
    // 1 = cheque
    $codigo_tipo_movimiento=1;
    $sql = "
            INSERT INTO `movimientos_bancarios` (
                `cod_tesor_bancodet` ,
                `fecha_movimiento` ,
                `tipo_movimiento` ,
                `numero_movimiento` ,
                `monto` ,
                `concepto` ,
                `contab` ,
                `estado` ,
                `cod_conciliacion` ,
                `fecha_creacion` ,
                `usuario_creacion`
            )
            VALUES (
                 '".$detalle_banco[0]["cod_tesor_bandodet"]."', '".$fecha_pago."', '".$codigo_tipo_movimiento."', '".$num_cheque."', '".$monto."', '".$beneficiario."', 'no', NULL , NULL ,
            CURRENT_TIMESTAMP , '".$login->getUsuario()."'
            );
        ";
     $cheque->ExecuteTrans($sql);
    /*
     * </grabamos en movimiento bancario>
     */


        if($cheque->errorTransaccion==0){
        echo json_encode(array(
           "success" => false,
           "msg"     => "¡Error al tratar de generar el Cheque!.",
           "sql"     => $update
        ));
    }

    if($cheque->errorTransaccion==1){
        echo json_encode(array(
           "success" => true,
           "msg"     => "¡Cheque Generado Exitosamente!"

        ));
    }
    $cheque->CommitTrans($cheque->errorTransaccion);
    //$cheque->Com mitTrans(0);
    exit;
    

}//if($opt_=="guardarChequeVariasCxP"){

}


if(isset($_GET["cod_cuenta"])){
	$campos = $comunes->ObtenerFilasBySqlSelect("
	select
	tb.cod_tesor_bandodet,
	tb.cod_banco,
	tb.nro_cuenta,
	b.descripcion as descripcion_banco,
	tb.descripcion as descripcion_cuenta
	from tesor_bancodet tb
	inner join banco b on b.cod_banco = tb.cod_banco
	where tb.cod_tesor_bandodet = ".$_GET["cod_cuenta"]);
	$smarty->assign("datos_banco",$campos);

// 	$campos = $comunes->ObtenerFilasBySqlSelect("
// 	SELECT
// 	cher.*,
// 	(select min(cheque) from
// 	cheque where
// 	cod_chequera = cher.cod_chequera and
// 	situacion = 'A' ) as num_proximo_cheque,
// 	
// 	(
// 	select c.cod_cheque from cheque c where
// 	c.cod_chequera = cher.cod_chequera and c.situacion = 'A' and
// 	cheque = (
// 	(select min(cheque) from
// 	cheque where
// 	cod_chequera = cher.cod_chequera and
// 	situacion = 'A' )
// 	)
// 	
// 	)  as cod_cheque
// 	
// 	FROM `chequera` cher
// 		where cher.cod_chequera = ".$_GET["cod_chequera"]);
// 	
// 	$smarty->assign("datos_chequera",$campos);

}
$smarty->assign("query_string",$_SERVER["QUERY_STRING"]);
?>