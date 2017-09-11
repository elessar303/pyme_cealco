<?php /* Smarty version 2.6.21, created on 2017-08-08 10:58:58
         compiled from editar_transporte_pda.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'editar_transporte_pda.tpl', 276, false),)), $this); ?>
<!DOCTYPE html>
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
        function activar(objeto, update=null){
            
            //get id
            id=objeto[\'id\'];
            if(objeto[\'name\']==\'camion\')
            {
                tipo=1;
                titulo="Agregar Camión";
                formulario="incluircamion";
                //verificar si es update, en caso afirmativo cargar el valor para realizar el selected
                if(update==1)
                {
                    parametros=
                    {
                        "opt": "obtenerIdCamion",
                        "id": id
                    };
                    $.ajax(
                    {
                        type: \'POST\',
                        data: parametros,
                        url: \'../../libs/php/ajax/ajax.php\',
                        success: function(data) 
                        {
                            if(data=="")
                            {
                                
                                Ext.Msg.alert("¡Error al cargar distribución, consulte al administrador!");
                            }
                            else
                            {
                                
                                 var objeto_json = JSON.parse(data);
                                var option = $(\'#camion\').children(\'option[value="\'+ objeto_json[0].id_transporte_camion +\'"]\');
                                option.attr(\'selected\',true);
                                $(\'#fecha\').val(objeto_json[0].fecha_ejecucion_transporte);
                            }
                        }
                    });
                }
            }
            else
            {
                tipo=2;
                titulo="Agregar Conductor";
                formulario="incluirconductor";

                if(update==1)
                {
                    parametros=
                    {
                        "opt": "obtenerIdConductor",
                        "id": id
                    };
                    $.ajax(
                    {
                        type: \'POST\',
                        data: parametros,
                        url: \'../../libs/php/ajax/ajax.php\',
                        success: function(data) 
                        {
                            if(data==-1)
                            {
                                
                                Ext.Msg.alert("¡Error al cargar distribución, consulte al administrador!");
                            }
                            else
                            {
                                
                                var option = $(\'#conductor\').children(\'option[value="\'+ data +\'"]\');
                                option.attr(\'selected\',true);
                            }
                        }
                    });
                }
            }
            win = new Ext.Window({
            title: titulo,
            height:200,
            width:400,
            autoScroll:true,
            modal:true,
            bodyStyle:\'padding-right:10px;padding-left:10px;padding-top:5px;\',
            closeAction:\'hide\',
            contentEl: formulario,
            buttons:[
                    {
                        text:\'Incluir\',
                        icon: \'../../libs/imagenes/drop-add.gif\',
                        handler:function()
                        {
                        //formulario de camion
                        if(tipo==1)
                        {
                            camion=$("#camion").val();
                            fecha=$("#fecha").val();
                            if(camion=="")
                            {
                                Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                                return false;
                            }
                            else
                            {
                                parametros={
                                                "opt": "pdaCamion",
                                                "camion": camion,
                                                "fecha": fecha,
                                                "id_distribucion": id
                                            };
                            }
                        }
                        else // formulario Conductor
                        {
                            conductor=$("#conductor").val();
                            if(conductor=="")
                            {
                                Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                                    return false;
                            }
                            else
                            {
                                parametros={
                                                "opt": "pdaConductor",
                                                "conductor": conductor,
                                                "id_distribucion": id
                                            };
                            }
                        }
                            
                            $.ajax({
                                type: \'POST\',
                                data: parametros,
                                url: \'../../libs/php/ajax/ajax.php\',
                                beforeSend: function() {
                                },
                                success: function(data) {
                                    this.vcampos = eval(data);
                                        if(data==1)
                                        {
                                            Ext.Msg.alert("¡Registro Exitoso!");
                                            location.reload();
                                        }
                                        else
                                        {
                                            Ext.Msg.alert("Error, Contacte al administrador del sistema");
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
         
        $(document).ready(function() {
            $(\'td.detalle1\').click(function() {
            objeto = $(this);
             tr=objeto.closest(\'tr\');
            tr.parents("tbody").find(".detalle").attr("bgcolor", "#ececec");
            tr.attr("bgcolor", "#b6ceff");
            tr.parents("tbody").find(".detalle_items").remove();
            tr.parents("tbody").find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add.gif");
            tr.find(".boton_detalle").attr("src", "../../libs/imagenes/drop-add2.gif");
            id_transaccion = objeto.find("input[name=\'id_transaccion\']").val();
            $.ajax({
                type: \'POST\',
                data: \'opt=det_pda_transporte&id_transaccion=\' + id_transaccion,
                url: \'../../libs/php/ajax/ajax.php\',
                beforeSend: function() {
                },
                success: function(data) {
                   tr.after(data);
                }
            });
        });

            $("#camion_estado").change(function() {
                        estados = $("#camion_estado").val();
                        $.ajax({
                            type: \'GET\',
                            data: \'opt=getPdaCamionEstado&\'+\'camion_estado=\'+estados,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() 
                            {
                                $("#camion").find("option").remove();
                                $("#camion").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) 
                            {
                                $("#camion").find("option").remove();
                                this.vcampos = eval(data);
                                if(this.vcampos[0].id==-1)
                                {
                                     $("#camion").append("<option value=\'\'>No Posee Flota En Ese Estado</option>");
                                }
                                else
                                {
                                    for (i = 0; i < this.vcampos.length; i++) 
                                    {
                                        $("#camion").append("<option value=\'" + this.vcampos[i].id+ "\'>" + this.vcampos[i].nombre + "</option>");
                                    }
                                }
                                
                                
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
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <br/>
                <table class="seleccionLista">
                    <thead>
                        <tr class="tb-head" >
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td><?php echo $this->_tpl_vars['campos']; ?>
</td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="2" style="text-align:center;">Opciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="5"><?php echo $this->_tpl_vars['mensaje']; ?>
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
" class="detalle">
                                    <td><?php echo $this->_tpl_vars['campos']['orden_compra']; ?>
</td>
                                    <td><?php echo $this->_tpl_vars['campos']['fecha_planificacion']; ?>
</td>
                                    <td><?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
</td>
                                    <td><?php echo $this->_tpl_vars['campos']['descripcion']; ?>
</td>
                                    <td style="width:50px; text-align: center;" class="detalle1">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif"/>
                                        <input type="hidden" name="id_transaccion" value="<?php echo $this->_tpl_vars['campos']['id']; ?>
"/>
                                    </td>
                                </tr>
                                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['cod_usuario']); ?>
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
        <!--Formulario de Camion -->
        <div id="incluircamion" class="x-hide-display">
            <p>
                <label for="almacen"><b>Seleccione El Estado</b></label><br/>
                <select name="camion_estado" id="camion_estado" class="form-text" style="width:205px">
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_estado'],'output' => $this->_tpl_vars['option_output_estado'],'selected' => $this->_tpl_vars['estado']), $this);?>

                </select>
            </p>
            <p>
                <label for="almacen"><b>Seleccione El Camión</b></label><br/>
                <select name="camion" id="camion" class="form-text" style="width:205px">
                    <option value=""> Seleccione Un Estado </option>
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_camion'],'output' => $this->_tpl_vars['option_output_camion'],'selected' => $this->_tpl_vars['camion']), $this);?>

                </select>
            </p>
            <P>
                <label for="almacen"><b>Fecha Despacho</b></label><br/>
                <input type="text" name="fecha" placeholder="Fecha Despacho" size="30px" id="fecha" class="form-text"/>
                 <?php echo '
                    <script type="text/javascript">//<![CDATA[
                        var cal = Calendar.setup({
                            onSelect: function(cal) 
                            {
                                cal.hide();
                            }
                        });
                        cal.manageFields("fecha", "fecha", "%d-%m-%Y");
                        //]]>
                    </script>
                '; ?>

            </P>
                                
        </div>
        <!--Formulario de Conductor-->
        <div id="incluirconductor" class="x-hide-display">
            <p>
                <label for="almacen"><b>Seleccione El Conductor Encargado</b></label><br/>
                <select name="conductor" id="conductor" class="form-text" style="width:205px" class="form-text">
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_conductor'],'output' => $this->_tpl_vars['option_output_conductor'],'selected' => $this->_tpl_vars['conductor']), $this);?>

                </select>
            </p>
           
        </div>
    </body>
</html>