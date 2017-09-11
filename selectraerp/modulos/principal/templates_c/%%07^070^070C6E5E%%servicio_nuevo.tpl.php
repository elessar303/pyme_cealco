<?php /* Smarty version 2.6.21, created on 2017-07-26 14:53:46
         compiled from servicio_nuevo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'servicio_nuevo.tpl', 245, false),)), $this); ?>
<?php echo '
    <script src="../../libs/js/config_items_tabs.js"></script>
    <script>
        $(document).ready(function(){
            $("#fila_precio1_iva, #fila_precio2_iva, #fila_precio3_iva, #utilidad1, #utilidad2, #utilidad3").attr("readonly","readonly");

        $("#cod_item").blur(function(){
            return false;
            vcoditem = $(this).val();
            if(vcoditem!=\'\'){
                $.ajax({
                    type: "GET",
                    url:  "../../libs/php/ajax/ajax.php",
                    data: "opt=ValidarCodigoitem&v1="+vcoditem,
                    beforeSend: function(){
                        $("#notificacionVCoditem").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                    },
                    success: function(data){
                        resultado = data
                        if(resultado=="-1"){
                            $("#cod_item").val("").focus();
                            $("#notificacionVCoditem").html("<img align=\\"absmiddle\\"  src=\\"../../libs/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, este codigo ya existe.</b></span>");
                        }
                        if(resultado=="1"){//cod de item disponble
                            $("#notificacionVCoditem").html("<img align=\\"absmiddle\\"  src=\\"../../libs/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Codigo Disponible</b></span>");
                        }
                    }
                });
                }
            });

            $("#monto_exento").change(function(){
                ifmontoExento = $("#monto_exento :selected").val();
                v_iva = $("input[name=\'iva\']").val();
                if(ifmontoExento==1){ // si es uno, entons tiene monto exento
                            $("#utilidad1, #utilidad2, #utilidad3").val("0.00");
                                            $("#fila_precio1_iva, #fila_precio2_iva, #fila_precio3_iva, input[name=\'iva\']").val("0.00");
                }else{
                                    monto = $("#fila_precio1").val();
                        total_iva = ((parseFloat(monto) * parseFloat(v_iva)) /100) + parseFloat(monto);

                                                    if($("input[name=\'precio1\']").val()==0){
                                                            $("#utilidad1").val("0.00");
                                                    }else{
                                                            $("#utilidad1").val("100");
                                                    }

                                    $("#fila_precio1_iva").val(parseFloat(total_iva));

                                    monto = $("#fila_precio2").val();
                        total_iva = ((parseFloat(monto) * parseFloat(v_iva)) /100) + parseFloat(monto);
                                    if($("input[name=\'precio2\']").val()==0){
                                                            $("#utilidad2").val("0.00");
                                                    }else{
                                                            $("#utilidad2").val("100");
                                                    }
                                    $("#fila_precio2_iva").val(parseFloat(total_iva));

                                    monto = $("#fila_precio3").val();
                        total_iva = ((parseFloat(monto) * parseFloat(v_iva)) /100) + parseFloat(monto);
                                                    if($("input[name=\'precio3\']").val()==0){
                                                            $("#utilidad3").val("0.00");
                                                    }else{
                                                            $("#utilidad3").val("100");
                                                    }
                    $("#fila_precio3_iva").val(parseFloat(total_iva));
                }
              });

                            $("input[name=\'iva\']").blur(function(){

                                    v_iva = $("input[name=\'iva\']").val();
                                    monto = $("#fila_precio1").val();
                                    total_iva = ((parseFloat(monto) * parseFloat(v_iva)) /100) + parseFloat(monto);
                                    $("#utilidad1").val("100");
                                    $("#fila_precio1_iva").val(total_iva);

                                    monto = $("#fila_precio2").val();
                                    total_iva = ((parseFloat(monto) * parseFloat(v_iva)) /100) + parseFloat(monto);
                                    $("#utilidad2").val("100");
                                    $("#fila_precio2_iva").val(total_iva);

                                    monto = $("#fila_precio3").val();
                                    total_iva = ((parseFloat(monto) * parseFloat(v_iva)) /100) + parseFloat(monto);
                                    $("#utilidad3").val("100");
                                    $("#fila_precio3_iva").val(total_iva);
                            });

                $("#fila_precio1, #fila_precio2, #fila_precio3").blur(function(){
                            monto = $(this).val();
                            campo = $(this).get(0).name;

                ifmontoExento = $("#monto_exento :selected").val();

                v_iva = $("input[name=\'iva\']").val();
                if(ifmontoExento==1){ // si es uno, entons tiene monto exento
                    total_iva = monto;
                            $("#utilidad1, #utilidad2, #utilidad3").val("0.00");
                                            $("#fila_precio1_iva, #fila_precio2_iva, #fila_precio3_iva").val("0.00");

                }else{
                    total_iva = ((parseFloat(monto) * parseFloat(v_iva)) /100) + parseFloat(monto);

                                    switch(campo)
                                    {
                                            case "precio1":
                                                    if($("input[name=\'precio1\']").val()==0){
                                                            $("#utilidad1").val("0.00");
                                                    }else{
                                                            $("#utilidad1").val("100");
                                                    }
                                                    $("#fila_precio1_iva").val(parseFloat(total_iva));
                                            break;
                                            case "precio2":
                                                    if($("input[name=\'precio2\']").val()==0){
                                                            $("#utilidad2").val("0.00");
                                                    }else{
                                                            $("#utilidad2").val("100");
                                                    }
                                                    $("#fila_precio2_iva").val(parseFloat(total_iva));
                                            break;
                                            case "precio3":
                                                    if($("input[name=\'precio3\']").val()==0){
                                                            $("#utilidad3").val("0.00");
                                                    }else{
                                                            $("#utilidad3").val("100");
                                                    }
                                                    $("#fila_precio3_iva").val(parseFloat(total_iva));
                                            break;
                                    }
                }
            });

              $("#formulario").submit(function(){
                    if($("#cod_item").val()==""||$("#descripcion1").val()==""){
                        alert("Debe Ingresar los campos obligatorios!.");
                        $("#descripcion1").focus();
                        return false;
                    }
            });
        });
    </script>
    <style>
        .tab{
            text-align:left;
            background-color:#d0d0d0;
            padding-left:10px;
            padding-right:10px;
            font-size:11px;
            font-family: arial;
            color:#a0a0a0;
            cursor: pointer;
            width:auto;
            border-left: 1px solid #8d8f91;
            border-right: 1px solid #8d8f91;border-top: 1px solid #8d8f91;
        }
        .sobreboton{
            background-color:#bec0c1;
        }
        .click{
            background: url(\'../../libs/imagenes/azul/tb_tit.jpg\') repeat-x;
            color:black;
            border-left: 1px solid #8d8f91;
            border-right: 1px solid #8d8f91;
            border-top: 1px solid #8d8f91;
        }
        #contenedorTAB {
            background-color: #e3ebf1;
            -moz-border-radius: 5px; padding: 2px;
            -webkit-border-radius: 5px;
            border: 1px solid #adafb0;

        }
        #tabs {
            margin-top:15px;
        }
    </style>
'; ?>

<form name="formulario" id="formulario" method="POST" action="">
    <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
    <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
    <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">

    <table  width="100%" border="0">
        <tbody>
            <tr>
                <td  class="tb-tit">
                    <img src="<?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
" width="20" align="absmiddle" height="20">  <strong><?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>
</strong>
                </td>
            </tr>
        </tbody>
    </table>
    <div id="tabs">
        <table style="margin-left:20px;" >
            <tr style="height:25px;">
                <td id="tab1" class="tab">
                    <img src="../../libs/imagenes/1.png" width="20" align="absmiddle" height="20">  <b>Datos Generales</b>
                </td>
                <td>&nbsp;&nbsp;</td>
                <td id="tab3" class="tab">
                    <img src="../../libs/imagenes/64.png" width="20" align="absmiddle" height="20">  <b>Existencia - Impuestos</b>
                </td>
            </tr>

        </table>
    </div>
    <div id="contenedorTAB">
        <!-- TAB1 -->
        <div id="div_tab1">
            <table   width="100%" border="0" height="100">
                <tr>
                    <td colspan="4" class="tb-head" align="center">
                        COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
                    </td>
                </tr>
                <tr>
                    <td  colspan="3" width="30%" class="tb-head" >
                        Ultimo Codigo
                    </td>
                    <td >
                        <input type="text" disabled="true" name="ultimo_codigo" id="ultimo_codigo" size="60"  value="<?php echo $this->_tpl_vars['nro_servicioOLD']; ?>
">
                        <div id="notificacionVCoditem"></div>
                    </td>
                </tr>
                <tr>
                    <td  colspan="3" width="30%" class="tb-head" >
                        Codigo **
                    </td>
                    <td >
                        <input type="text" name="cod_item" readonly id="cod_item" size="60"   value="<?php echo $this->_tpl_vars['nro_servicioNEW']; ?>
">
                        <div id="notificacionVCoditem"></div>
                    </td>
                </tr>
                <tr>
                    <td class="tb-head" colspan="4" align="center" width="180">
                        DATOS DEL item
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head">
                        <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario1']; ?>

                    </td>
                    <td>
                        <select name="cod_departamento" id="cod_departamento">
                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_output_departamentos'],'output' => $this->_tpl_vars['option_values_departamentos']), $this);?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head">
                        <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario2']; ?>

                    </td>
                    <td>
                        <select name="cod_grupo" id="cod_grupo">
                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_output_grupo'],'output' => $this->_tpl_vars['option_values_grupo']), $this);?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head">
                        <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario3']; ?>

                    </td>
                    <td>
                        <select name="cod_linea" id="cod_linea">
                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_output_linea'],'output' => $this->_tpl_vars['option_values_linea']), $this);?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" class="tb-head">
                        Descripción 1 **
                    </td>
                    <td>
                        <input type="text" name="descripcion1" id="descripcion1" size="60" value=''>
                    </td>
                </tr>
                <tr>
                    <td class="tb-head" colspan="3"  >
                        Descripción 2
                    <td>
                        <input type="text" name="descripcion2" id="descripcion2" size="60" value=''>
                    </td>
                </tr>
                <tr>
                    <td class="tb-head" colspan="3">
                        Descripción 3
                    </td>
                    <td>
                        <input type="text" name="descripcion3" id="descripcion3" size="60" value=''>
                    </td>
                </tr>
                <tr>
                    <td class="tb-head" colspan="3">
                        Referencia
                    </td>
                    <td>
                        <input type="text" name="referencia" id="referencia" size="60" value=''>
                    </td>
                </tr>
                <tr>
                    <td class="tb-head" colspan="3">
                        Estatus
                    </td>
                    <td>
                        <select name="estatus" id="estatus">
                            <option value="A">Activo</option>
                            <option value="I">Inactivo</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                        Cuenta Contable 1
                    </td>
                    <td >
                        <select name="cuenta_contable1" id="cuenta_contable1">
                            <option values="0">Seleccione la Cuenta</option>
                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta'],'output' => $this->_tpl_vars['option_output_cuenta']), $this);?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top"  colspan="3" width="30%" class="tb-head" >
                        Cuenta Contable 2
                    </td>
                    <td >
                        <select name="cuenta_contable2" id="cuenta_contable2">
                            <option values="0">Seleccione la Cuenta</option>
                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta'],'output' => $this->_tpl_vars['option_output_cuenta']), $this);?>

                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
            </td>
            </tr>
            </tbody>
            </table>
        </div>
        <!-- /TAB1 -->
        <!--
        ***************************************************************************************************************************
        ***************************************************************************************************************************
        -->
        <!-- TAB2 -->
        <div id="div_tab2">
        </div>

        <!-- /TAB2 -->

        <!-- TAB3 -->
        <div id="div_tab3">
            <table   width="100%" border="0">
                <tr>
                    <td colspan="4" class="tb-head" align="center">

                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="tb-head" align="center">
                        PRECIOS
                    </td>
                </tr>
                <tr>
                    <td colspan="3" valign="top" class="tb-head" align="left">

                    </td>
                    <td >
                        <table id="tabla_total" style="border: 1px solid #507e95;" bgcolor="white">
                            <thead>
                                <tr>
                                    <th align="left">Precios</th>
                                    <th align="left">Utilidad</th>
                                    <th align="left">Con Iva</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input class="campo_decimal" title="<?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio1']; ?>
" id="fila_precio1" name="precio1" value="0.00" size="10" type="text"></td>
                                    <td><input class="campo_decimal"  value="0.00" size="10" id="utilidad1" name="utilidad1" type="text">%</td>
                                    <td><input class="campo_decimal" id="fila_precio1_iva" value="0.00" name="coniva1" size="10" type="text"></td>
                                </tr>
                                <tr>
                                    <td><input class="campo_decimal" title="<?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio2']; ?>
" id="fila_precio2" name="precio2" value="0.00" size="10" type="text"></td>
                                    <td><input class="campo_decimal" value="0.00" size="10" id="utilidad2" name="utilidad2" type="text">%</td>
                                    <td><input class="campo_decimal" id="fila_precio2_iva" value="0.00" name="coniva2" size="10" type="text"></td>
                                </tr>
                                <tr>
                                    <td><input class="campo_decimal" title="<?php echo $this->_tpl_vars['DatosGenerales'][0]['titulo_precio3']; ?>
" id="fila_precio3" name="precio3" value="0.00" size="10" type="text"></td>
                                    <td><input class="campo_decimal" value="0.00" size="10" id="utilidad3"  name="utilidad3" type="text">%</td>
                                    <td><input class="campo_decimal" id="fila_precio3_iva"  value="0.00" name="coniva3" size="10" type="text"></td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td colspan="4" class="tb-head" align="center">
                        IMPUESTOS
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="tb-head" align="left">
                        Monto Exento
                    </td>
                    <td>
                        <select name="monto_exento" id="monto_exento">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </td>
                </tr>
                <tr class="monto_iva">
                    <td colspan="3" class="tb-head" align="left">
                        I.V.A
                    </td>
                    <td>
                        <input class="campo_decimal" type="text"  value="<?php echo $this->_tpl_vars['parametros_generales'][0]['porcentaje_impuesto_principal']; ?>
" name="iva">
                    </td>
                </tr>

                <?php $_from = $this->_tpl_vars['islr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                    <tr>
                        <TD colspan="3" width="150px" class="tb-head" align="left">
                            <STRONG><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</STRONG>
                        </TD>
                        <td>
                            <input class="campo_decimal" type="checkbox"  value="<?php echo $this->_tpl_vars['campos']['cod_impuesto']; ?>
" name="<?php echo $this->_tpl_vars['campos']['cod_impuesto']; ?>
">
                        </td>
                    </tr>
                <?php endforeach; endif; unset($_from); ?>

            </table>
        </div>
        <!-- /TAB3 -->
    </div>
    <table width="100%" border="0">
        <tbody>
            <tr class="tb-tit" align="right">
                <td>
                    <input type="submit" id="aceptar" name="aceptar" value="Guardar">
                    <input type="button" name="cancelar" onclick="javascript: document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'" value="Cancelar">

                </td>
            </tr>
        </tbody>
    </table>


    <input type="hidden" name="pg_iva" id="pg_iva" value="<?php echo $this->_tpl_vars['parametros_generales'][0]['porcentaje_impuesto_principal']; ?>
">

</form>