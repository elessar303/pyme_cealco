<?php /* Smarty version 2.6.21, created on 2017-08-24 11:43:45
         compiled from cotizacion_mercado.tpl */ ?>
<!DOCTYPE html>
<!--
Modificado por: Charli Vivenes
Acción:
1._ Trasladar el código JS a un nuevo archivo (header_form.tpl) que funje como
    nueva plantilla que contiene el código común a todos los formularios.
Objetivos:
1._ Hacer que la cofiguración del formulario sea dinámica. Esto apunta también a
    factorizar dicho snippet de código para obtener las bondades de la reutilización.
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
        <script type="text/javascript" src="../../libs/js/entrada_cotizacion_agregar.js"></script>
        
        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
        
        //ventanna emergente y ajax de insertar
        function editarItem(objeto)
        {

            //ajax para precargar datos

            // id_estudio=$("#id_estudio").val();
            // alert(id_estudio);

            $.ajax(
            {
                type: \'POST\',
                data: \'opt=getProductoCotizacion&iddetalle=\'+objeto.id,
                url: \'../../libs/php/ajax/ajax.php\',
                success: function(data) 
                {
                    vcampos = eval(data);
                    
                    for (i = 0; i < vcampos.length; i++) 
                    {
                        
                        $("#codigoBarra").val(vcampos[i].codigo_barras);
                        $("#items_descripcion").val(vcampos[i].descripcion1);
                        $("#totalsiniva").val(vcampos[i].costo_sin_iva);
                        $("#ivaproduct").val(vcampos[i]._ivaproduct);
                        $("#preciosugerido").val(vcampos[i]._precio_sugerido);
                        $("#margenganancia").val(vcampos[i]._margen_ganancia);
                        $("#pventapublico").val(vcampos[i]._pvp);
                        // $("#estatusproducto").val(vcampos[i]._estatus_producto);

                        // $("#estatusproducto").append("<option value=\'" + vcampos[i].id_estatus+ "\'>" + vcampos[i].estatus_name + "</option>");

                    }

                }
            });
            win = new Ext.Window({
            title:\'Editar Estatus del Producto\',
            height:360,
            width:459,
            autoScroll:true,
            
            modal:true,
            bodyStyle:\'padding-right:10px;padding-left:10px;padding-top:5px;\',
            closeAction:\'hide\',
            contentEl:\'editarEstatus\',
            buttons:[
            {
                text:\'Incluir\',
                icon: \'../../libs/imagenes/drop-add.gif\',
                handler:function()
                {
                    codigoBarra=$("#codigoBarra").val();
                    items_descripcion=$("#items_descripcion").val();
                    totalsiniva=$("#totalsiniva").val();
                    ivaproduct=$("#ivaproduct").val();
                    preciosugerido=$("#preciosugerido").val();
                    margenganancia=$("#margenganancia").val();
                    pventapublico=$("#pventapublico").val();
                    estatusproducto=$("#estatusproducto").val();
                    
                    // alert(objeto.id); exit();

                    // if($("#instalacion").val()==""||$("#input_fechaplanificacion_inicio").val()==""||$("#input_fechaplanificacion_fin").val()==""||$("#observacion_detalle").val()==""||$("#cantidadunitaria").val()==""||$("#cantidadunitaria").val()<0)
                    //     {
                    //         Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                    //         return false;
                    //     }

                        //verificar que cantida no sobrepase el limite
                        // id_estudio=$("#id_estudio").val();
                        // alert(id_estudio);
                        $.ajax(
                        {
                            type: \'POST\',
                            data: \'opt=updateEstatusProducto&iddetalle=\'+objeto.id+\'&id_estudio=\'+id_estudio+\'&estatusproducto=\'+estatusproducto,
                            url: \'../../libs/php/ajax/ajax.php\',
                            success: function(data) 
                            {
                                   
                                this.vcampos = eval(data);
                                if(data==1)
                                {
                                    Ext.Msg.alert("Modificación exitosa");
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

         function cerrarCotizacion(id_estudio){
         
            if (!confirm(\'¿Está seguro de cerrar la Cotización?\')){ 
                return false;
            }

            id_estudio=$("#id_estudio").val();
            //alert(id_estudio);

            $.ajax({

                type: "POST",
                data: \'opt=cambiar_estatus_cotizacion2&id_estudio=\'+id_estudio,
                url: \'../../libs/php/ajax/ajax.php\',
                asynchronous: false,
                success: function(data){

                    if(data==1){
                        Ext.Msg.alert("Cotización cerrada correctamente");
                        location.reload();
                    }else{
                        if(data==-2){
                            Ext.Msg.alert("Error, Consulte Al Administrador");
                            location.reload();
                        }
                        // $(\'#boton\').css("visibility", "visible");

                        //$bruto=res[\'1\'].toFixed(2);  
                               
                        //$("#resultado").html(data);
                        ///// verificamos su estado
                    }
                }
            });

        }
        
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
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <!-- <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/tb_head_mercadeo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> -->
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head">
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td><?php echo $this->_tpl_vars['campos']; ?>
</td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="3" style="text-align:center;">Opciones</td>
                        </tr>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="3"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                        <?php else: ?>
                            <?php if ($this->_tpl_vars['num_paginas'] == 0): ?>
                                <tr bgcolor="<?php echo $this->_tpl_vars['color']; ?>
" style="cursor: pointer;">
                                    <td colspan="5">No se han entregado las cotizaciones a la Gerencia de Mercadeo</td>
                                </tr>
                            <?php else: ?>
                            <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <?php if ($this->_tpl_vars['i']%2 == 0): ?>
                                    <?php $this->assign('color', ""); ?>
                                <?php else: ?>
                                    <?php $this->assign('color', "#cacacf"); ?>
                                <?php endif; ?>
                                <?php if ($this->_tpl_vars['campos']['retirado'] != 0): ?>
                                <tr bgcolor="<?php echo $this->_tpl_vars['color']; ?>
" style="cursor: pointer;" class="detalle">
                                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['estudio_cotiza']; ?>
</td>
                                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['fecha_recep_mercadeo']; ?>
</td>
                                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['observacion']; ?>
</td>
                                    <td style="text-align:center"><?php echo $this->_tpl_vars['campos']['estatus_name']; ?>
</td>
                                    <!-- <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['producto']; ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['precio']; ?>
</td> -->
                                    <!--<td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['empresa_transporte']; ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['nombre_conductor']; ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['cedula_conductor']; ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['placa']; ?>
</td>
                                    <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['guia_sunagro']; ?>
</td>-->
                                    <?php if ($this->_tpl_vars['campos']['id_detalle_producto'] != ''): ?>
                                    <td style="width:30px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/drop-add.gif" title="Ver detalle" id="target"/>
                                        <input type="hidden" id="id_estudio" name="id_estudio" value="<?php echo $this->_tpl_vars['campos']['id_estudio']; ?>
"/>
                                        <input type="hidden" id="estatus_cotizacion" name="estatus_cotizacion" value="<?php echo $this->_tpl_vars['campos']['estatus_cotizacion']; ?>
"/>
                                        <input type="hidden" name="id_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                                        <input type="hidden" name="id_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                                    </td>
                                    <?php else: ?>
                                    <td style="width:30px; text-align: center;">
                                        <img class="boton_detalle2" src="../../../includes/imagenes/delete.png" title="Sin productos asignados"/>
                                        <input type="hidden" name="id_estudio" value="<?php echo $this->_tpl_vars['campos']['id_estudio']; ?>
"/>
                                        <input type="hidden" name="estatus_cotizacion" value="<?php echo $this->_tpl_vars['campos']['estatus_cotizacion']; ?>
"/>
                                        <input type="hidden" name="id_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                                        <input type="hidden" name="id_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                                    </td>
                                    <?php endif; ?>

                                    <!-- <td style="width: 30px; text-align:center;">
                                        <?php if ($this->_tpl_vars['campos']['estado'] == 'Entregado'): ?>
                                            <img title="Entregado" src="../../../includes/imagenes/ico_ok.gif"/>
                                        <?php elseif ($this->_tpl_vars['campos']['estado'] == 'Pendiente'): ?>
                                            <img title="Pendiente" src="../../../includes/imagenes/ico_note.gif"/>
                                        <?php else: ?>
                                            <img title="Cancelado" src="../../../includes/imagenes/delete.png"/>
                                        <?php endif; ?>
                                    </td> -->
                                    <!-- <td style="cursor: pointer; width: 30px; text-align:center">
                                        <img class="impresion" onclick="javascript:window.open('../../reportes/entrada_cotizacion.php?id_estudio=<?php echo $this->_tpl_vars['campos']['id_estudio']; ?>
', '');" title="Imprimir Detalle de la Cotización" src="../../../includes/imagenes/ico_print.gif"/>
                                    </td> -->
                                    <?php if ($this->_tpl_vars['campos']['id_detalle_producto'] != ''): ?>
                                        <?php if ($this->_tpl_vars['campos']['cerrado'] == '0'): ?>
                                            <td style="cursor:pointer; width:30px; text-align:center">
                                                <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;cod=<?php echo $this->_tpl_vars['campos']['id_estudio']; ?>
'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                                            </td>
                                        <?php else: ?>
                                            <td style="cursor:pointer; width:30px; text-align:center">
                                                <img class="editar" title="Cotización Cerrada" src="../../../includes/imagenes/ico_ok.gif"/>
                                            </td>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?>
                            <?php endif; ?>
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

        <!--Modal para editar el producto registrado a la cotización-->
        <div id="editarEstatus" class="x-hide-display">
            
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra" readonly class="form-text"/>
            </p>

            <p>
                <label><b>Descripcion del Producto</b></label><br/>
                 <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly class="form-text" style="width:205px" />
               
            </p>
           
            <p>
                <label><b>Costo Total sin IVA</b></label><br/>
                <input type="text" name="totalsiniva" id="totalsiniva" class="form-text" readonly style="width:205px"/>
            </p>

            <p>
                <label><b>IVA</b></label><br/>
                <input type="text" name="ivaproduct" id="ivaproduct" class="form-text" readonly style="width:205px"/>
            </p>

            <p>
                <label><b>Precio Sugerido</b></label><br/>
                <input type="text" name="preciosugerido" id="preciosugerido" class="form-text" readonly style="width:205px"/>
            </p>

            <p>
                <label><b>Margen de Ganancia</b></label><br/>
                <input type="text" name="margenganancia" id="margenganancia" class="form-text" readonly style="width:205px"/>
            </p>

            <p>
                <label><b>P.V.P</b></label><br/>
                <input type="text" name="pventapublico" id="pventapublico" class="form-text" readonly style="width:205px"/>
            </p>

            <p>
                <label><b>Estatus del Producto</b></label><br/>
                <select name="estatusproducto" id="estatusproducto" class="form-text" style="width:205px">
                    <option value="3">En Estudio</option>
                    <option value="1">Aprobado</option>
                    <option value="2">No Aprobado</option>
                </select>
            </p>
            
        </div>
        <!--Fin del modal-->

    </body>
</html>