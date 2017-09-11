<?php /* Smarty version 2.6.21, created on 2017-08-24 11:26:43
         compiled from pda_compras.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'pda_compras.tpl', 275, false),array('modifier', 'date_format', 'pda_compras.tpl', 288, false),)), $this); ?>
<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snipper de código para obtener las bondades de la reutilización.
-->
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
        //ventanna emergente y ajax de insertar
         
 
        function editarPDA(objeto)
        {

            //ajax para precargar datos
            $.ajax(
            {
                type: \'POST\',
                data: \'opt=getPDACompras&iddetalle=\'+objeto.id,
                url: \'../../libs/php/ajax/ajax.php\',
                success: function(data) 
                {
                    vcampos = eval(data);
                    
                    $("#id_proveedor").find("option").remove();
                    $("#id_proveedor").append("<option value=\'\'>Seleccione..</option>");
                    for (i = 0; i < vcampos.length; i++) 
                    {
                        
                        $("#id_proveedor").append("<option value=\'" + vcampos[i].id_proveedor+ "\'>" + vcampos[i].descripcion_proveedor + "</option>");
                        var dateAr = vcampos[i].fecha_inicio.split(\'-\');
                        vcampos[i].fecha_inicio = dateAr[2].slice(0,2) + \'/\' + dateAr[1] + \'/\' + dateAr[0];
                        date_fin = vcampos[i].fecha_fin.split(\'-\');
                        vcampos[i].fecha_fin = date_fin[2].slice(0,2) + \'/\' + date_fin[1] + \'/\' + date_fin[0];
                        $("#input_fechaplanificacion_inicio").val(vcampos[i].fecha_inicio);
                        $("#input_fechaplanificacion_fin").val(vcampos[i].fecha_fin);
                        $("#observacion_detalle").val(vcampos[i].observaciones);
                        $("#codigoBarra").val(vcampos[i].codigo_barras);
                        $("#items_descripcion").val(vcampos[i].producto);
                        $("#cantidadunitaria").val(vcampos[i].cantidad);

                    }

                }
            });
            win = new Ext.Window({
            title:\'Editar PDA\',
            height:360,
            width:459,
            autoScroll:true,
            
            modal:true,
            bodyStyle:\'padding-right:10px;padding-left:10px;padding-top:5px;\',
            closeAction:\'hide\',
            contentEl:\'incluirproducto\',
            buttons:[
            {
                text:\'Incluir\',
                icon: \'../../libs/imagenes/drop-add.gif\',
                handler:function()
                {
                    destino=$("#instalacion").val();
                    fecha_inicio=new $("#input_fechaplanificacion_inicio").val();
                    fecha_fin=$("#input_fechaplanificacion_fin").val();
                    cantidad=$("#cantidadunitaria").val();
                    observacion=$("#observacion_detalle").val();
                    if($("#instalacion").val()==""||$("#input_fechaplanificacion_inicio").val()==""||$("#input_fechaplanificacion_fin").val()==""||$("#observacion_detalle").val()==""||$("#cantidadunitaria").val()==""||$("#cantidadunitaria").val()<0)
                        {
                            Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                            return false;
                        }
                        var x = fecha_inicio.split("-");
                        var z = fecha_fin.split("-");
                        fecha1 = x[1] + "/" + x[0] + "/" + x[2];
                        fecha2 = z[1] + "/" + z[0] + "/" + z[2];
                        if(Date.parse(fecha1) > Date.parse(fecha2))
                        {
                            $("#input_fechaplanificacion_inicio").css("border-color",$(this).val()===""?"red":"");
                            $("#input_fechaplanificacion_inicio").css("border-width",$(this).val()===""?"1px":"");
                            Ext.Msg.alert("Alerta!", "Fecha Inicial No puede ser mayor a Fecha Final");
                            return false;
                        }

                        //verificar que cantida no sobrepase el limite
                        $.ajax(
                        {
                            type: \'POST\',
                            data: \'opt=updatePDACompras&iddetalle=\'+objeto.id+\'&destino=\'+destino+\'&fecha_inicio=\'+fecha_inicio+\'&fecha_fin=\'+fecha_fin+\'&cantidad=\'+cantidad+\'&observacion=\'+observacion,
                            url: \'../../libs/php/ajax/ajax.php\',
                            success: function(data) 
                            {
                                   
                                this.vcampos = eval(data);
                                if(data==1)
                                {
                                    Ext.Msg.alert("Exitosa La Modificación");
                                    location.reload();
                                }
                                else
                                {
                                    Ext.Msg.alert("Error, consulte al administrador");
                                    location.reload();
                                }
                                
                            }
                        });
                }
            },
            {
                text:\'Cerrar\',
                icon: \'../../libs/imagenes/cancel.gif\',
                handler:function()
                {
                    win.hide();
                }
            },
            ]
            });

             win.show();
        }
         //fin de ventana emergente e insertar

        $(document).ready(function() 
        {
            $("#id_proveedor").change(function() 
            {
                proveedor = $("#id_proveedor").val();
                $.ajax({
                    type: \'GET\',
                    data: \'opt=getInstalaciones&\'+\'idProveedor=\'+proveedor,
                    url: \'../../libs/php/ajax/ajax.php\',
                    beforeSend: function() 
                    {
                        $("#instalacion").find("option").remove();
                        $("#instalacion").append("<option value=\'\'>Cargando..</option>");
                    },
                    success: function(data) 
                    {
                        $("#instalacion").find("option").remove();
                        this.vcampos = eval(data);
                        $("#instalacion").append("<option value=\'0\'>Instalacion Principal</option>");
                        for (i = 0; i < this.vcampos.length; i++) 
                        {
                            $("#instalacion").append("<option value=\'" + this.vcampos[i].codigo_sica+ "\'>" + this.vcampos[i].instalacion + "</option>");
                        }
                    }
                }); 
                $("#instalacion").val(0);
            });
            
            $(\'td.detalle1\').click(function() 
            {
                objeto = $(this);
                 tr=objeto.closest(\'tr\');
                //Deseleccionamos cualquier fila cambiandole el color del tr
                tr.parents("tbody").find(".detalle").attr("bgcolor", "#ececec");
                //Seleccionamos la fila a la cual se dio click para conocer detalles
                tr.attr("bgcolor", "#b6ceff");
                //Removemos cualquier detalle que este cargado en la tabla de estado de cuenta
                tr.parents("tbody").find(".detalle_items").remove();
                //Le colocamos la imagen que indica que puede hacer click para desplegar informacion
                tr.parents("tbody").find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add.gif");
                //Le coloca la imagenes a la fila tr que disparo el evento click.
                tr.find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add2.gif");
                id_transaccion = objeto.find("input[name=\'id_transaccion\']").val();
                //Cargamos los debitos y creditos
                $.ajax(
                {
                    type: \'POST\',
                    data: \'opt=det_pda_compras&id_transaccion=\' + id_transaccion,
                    url: \'../../libs/php/ajax/ajax.php\',
                    beforeSend: function() {
                    },
                    success: function(data) 
                    {
                       tr.after(data);
                    }
                });
            });
        });
        </script>
        '; ?>

    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="form-<?php echo $this->_tpl_vars['name_form']; ?>
" action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
" method="post">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_buscar_botones.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/tb_head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td><strong><?php echo $this->_tpl_vars['campos']; ?>
</strong></td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="2" style="text-align:center;"><strong>Opciones</strong></td>
                        </tr>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="6" style="text-align:center;"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                        <?php else: ?>
                            <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <?php if ($this->_tpl_vars['i']%2 == 0): ?>
                                    <?php $this->assign('color', ""); ?>
                                <?php else: ?>
                                    <?php $this->assign('color', "#cacacf"); ?>
                                <?php endif; ?>
                                <tr bgcolor="<?php echo $this->_tpl_vars['color']; ?>
">
                                    <td style="text-align:center; width: 100px;"><?php echo $this->_tpl_vars['campos']['orden_compra']; ?>
</td>
                                    
                                    <td style="text-align:center; padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                                    <td style="cursor: pointer; width: 30px; text-align:center;">
                                    <?php if ($this->_tpl_vars['campos']['orden_compra'] == ""): ?>
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;cod=<?php echo $this->_tpl_vars['campos']['id']; ?>
'" title="Falta Orden De Compra" src="../../../includes/imagenes/ico_note.gif"/>
                                        <?php else: ?>
                                        <img class="editar"  title="Orden Generada" src="../../../includes/imagenes/ico_ok.gif"/>
                                        <?php endif; ?>
                                    </td>
                                    <!--<td style="cursor: pointer; width: 30px; text-align:center;">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=newCompra&amp;cod=<?php echo $this->_tpl_vars['campos']['id_proveedor']; ?>
'" width="17" height="17" title="Generar Nueva Compra" src="../../../includes/imagenes/40.png"/>
                                    </td>-->
                                    <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/pda_compras.php?id_transaccion=<?php echo $this->_tpl_vars['campos']['id']; ?>
', '');" title="Imprimir Detalle de Movimiento" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td>
                                    <td style="width:50px; text-align: center;" class="detalle1">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif"/>
                                        <input type="hidden" name="id_transaccion" value="<?php echo $this->_tpl_vars['campos']['id']; ?>
"/>
                                    </td>
                                     <!--<td style="cursor:pointer; width:30px; text-align:center;">
                                        <img class="editar" id="<?php echo $this->_tpl_vars['campos']['id']; ?>
" width="17" height="17" onclick="activar(this)" title="Distribuir"src="../../../includes/imagenes/add.gif" />-->
                                    </td>
                                </tr>
                                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id_proevedor']); ?>
                            <?php endforeach; endif; unset($_from); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/navegacion_paginas.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </div>
        </form>
        <div id="incluirproducto" class="x-hide-display">
            
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra" class="form-text"/>
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>

            <p>
                <label><b>Productos</b></label><br/>
                <input type="hidden" name="items" id="items" class="form-text" />
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly class="form-text" style="width:205px" />
               
            </p>
           
            <p>
                <label><b>Cantidad Unitaria</b></label><br/>
                <input type="text" name="cantidadunitaria" id="cantidadunitaria" class="form-text" style="width:205px"/>
            </p>
             <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Proveedor (*)</b></span>
            </p>
            <p>
                <select name="id_proveedor" id="id_proveedor" class="form-text" style="width:205px">
                    <option value="">Seleccione...</option>
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_proveedor'],'output' => $this->_tpl_vars['option_output_proveedor']), $this);?>

                </select>
            </p>

            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Instalación</b></span><br>
          
                <select name='instalacion' id='instalacion' class="form-text" style="width:205px">
                    <option value=''>Seleccione Un Proveedor...</option>
                </select>
            </p>
            <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Inicio</b></span><br>
                <input type="text" name="input_fechaplanificacion_inicio" id="input_fechaplanificacion_inicio" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>
' size="30" maxlength="70" class="form-text" readonly="readonly" style="width:205px"  />
                <div id="notificacionVUsuariofecha1"></div>
                    <?php echo '
                    <script type="text/javascript">
                    //<![CDATA[
                    var cal = Calendar.setup(
                    {
                        onSelect: function(cal) 
                        {
                            cal.hide();
                        }
                    });
                    cal.manageFields("input_fechaplanificacion_inicio", "input_fechaplanificacion_inicio", "%d/%m/%Y");
                    //]]>
                    </script>
                    '; ?>

                <span style="font-family:'Verdana';font-weight:bold;"><b>Fecha Planificación Fin</b></span><br>
                <input type="text" name="input_fechaplanificacion_fin" id="input_fechaplanificacion_fin" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d/%m/%Y") : smarty_modifier_date_format($_tmp, "%d/%m/%Y")); ?>
' size="30" maxlength="70" class="form-text" readonly="readonly" style="width:205px" />
                <div id="notificacionVUsuariofecha2"></div>
                    <?php echo '
                    <script type="text/javascript">
                    //<![CDATA[
                    var cal = Calendar.setup(
                    {
                        onSelect: function(cal) 
                        {
                            cal.hide();
                        }
                    });
                    cal.manageFields("input_fechaplanificacion_fin", "input_fechaplanificacion_fin", "%d/%m/%Y");
                    //]]>
                    </script>
                    '; ?>

            </p>
             <p>
                <span style="font-family:'Verdana';font-weight:bold;"><b>Observación</b></span><br>
                 <input type="text" name="observacion_detalle" id="observacion_detalle" class="form-text" style="width:205px"/>
            </p>
            
        </div>
    </body>
</html>