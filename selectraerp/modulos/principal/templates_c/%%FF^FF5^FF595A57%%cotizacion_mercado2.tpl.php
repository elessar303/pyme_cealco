<?php /* Smarty version 2.6.21, created on 2017-08-24 11:43:38
         compiled from cotizacion_mercado2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'cotizacion_mercado2.tpl', 99, false),)), $this); ?>
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
        <!-- <script type="text/javascript" src="../../libs/js/entrada_cotizacion.js"></script> -->

        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
        
        function cambiar_estatus(id_estudio){
         
            if (!confirm(\'¿Está seguro de recepcionar la Cotización de Mercado?\')){ 
                return false;
            }
         
            parametros={
                "id_estudio": id_estudio,  "opt": "cambiar_estatus_cotizacion"
            };

            $.ajax({

                type: "POST",
                url: "../../libs/php/ajax/ajax.php",
                data: parametros,
                dataType: "html",
                asynchronous: false,
                beforeSend: function() {
                    // $("#resultado").empty();

                },
                error: function(){
                    // alert("error petición ajax");
                },
                success: function(data){


                    if(data==1){
                        alert("Cotización entregada a la Gerencia de Mercadeo");
                            location.reload();
                    }else{
                        if(data==-2){
                            alert("Error, Consulte Al Administrador");
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
                            <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <?php if ($this->_tpl_vars['i']%2 == 0): ?>
                                    <?php $this->assign('color', ""); ?>
                                <?php else: ?>
                                    <?php $this->assign('color', "#cacacf"); ?>
                                <?php endif; ?>
                                <tr bgcolor="<?php echo $this->_tpl_vars['color']; ?>
" style="cursor: pointer;" >
                                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['estudio_cotiza']; ?>
</td>
                                    <td style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td>
                                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['observacion']; ?>
</td>
                                    <td style="text-align:center"><?php echo $this->_tpl_vars['campos']['estatus_name']; ?>
</td>
                                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['fecha_recep_mercadeo']; ?>
</td>
                                    <!-- <td style="padding-left: 20px;"><?php echo $this->_tpl_vars['campos']['precio']; ?>
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
                                    <?php if ($this->_tpl_vars['campos']['retirado'] != '0'): ?>
                                    <td colspan="2" style="width:30px; text-align: center;">
                                        <img class="boton_detalle" src="../../../includes/imagenes/ico_ok.gif" title="Recibido por la Gerencia de Mercadeo" />
                                        <input type="hidden" name="id_estudio" value="<?php echo $this->_tpl_vars['campos']['id_estudio']; ?>
"/>
                                        <input type="hidden" name="id_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                                        <input type="hidden" name="id_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                                    </td>
                                    <?php else: ?>
                                    <td colspan="2" style="width:30px; text-align: center;" >
                                        <img class="boton_detalle2" src="../../../includes/imagenes/ico_note.gif" title="Por Recibir" onclick="cambiar_estatus(<?php echo $this->_tpl_vars['campos']['id_estudio']; ?>
);" />
                                        <input type="hidden" name="id_estudio" value="<?php echo $this->_tpl_vars['campos']['id_estudio']; ?>
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
                                    <!--<td style="cursor:pointer; width:30px; text-align:center">
                                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;cod=<?php echo $this->_tpl_vars['campos']['id_estudio']; ?>
'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                                    </td>-->
                                </tr>
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
    </body>
</html>