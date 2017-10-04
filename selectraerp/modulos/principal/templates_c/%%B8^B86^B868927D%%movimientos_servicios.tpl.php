<?php /* Smarty version 2.6.21, created on 2017-10-04 17:20:48
         compiled from movimientos_servicios.tpl */ ?>
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
            <script type="text/javascript">
                
                function eliminar(id)
                {
                    if(confirm("Seguro que desea eliminar este elemento"))
                    {
                        //se llama a la funcion del ajax para eliminar los servicios asociados a una ubicacion
                        $.ajax(
                        {
                            type: "GET",
                            url:  "../../libs/php/ajax/ajax.php",
                            data: "opt=eliminarServicioUbicacion&v1="+id,
                            success: function(data)
                            {
                                resultado = data
                                if(resultado==-1)
                                {
                                    alert(\'Erro Al Intentar Borrar Un Registro, Consulte Al Administrador\');
                                }
                                if(resultado==1)
                                {
                                    alert(\'Registro Borrado\');
                                    location.reload();
                                }
                            }
                        });
                    }
                };
                
            </script>
        '; ?>

        
    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="form-<?php echo $this->_tpl_vars['name_form']; ?>
" action="?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=ubicacion&amp;cod=<?php echo $_GET['cod']; ?>
" method="post">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_solo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/tb_head_sub.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <br/>
                <table class="seleccionLista">
                    <tbody>
                        <tr class="tb-head" >
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td align="center">
                                    <b><?php echo $this->_tpl_vars['campos']; ?>
</b>
                                </td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="2" style="text-align:center;">
                                <b>Opciones</b>
                            </td>
                        </tr>
                    <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                        <tr>
                            <td colspan="3" align="center">
                                <?php echo $this->_tpl_vars['mensaje']; ?>

                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                            <?php if ($this->_tpl_vars['i']%2 == 0): ?>
                                <?php $this->assign('bgcolor', ""); ?>
                            <?php else: ?>
                                <?php $this->assign('bgcolor', "#cacacf"); ?>
                            <?php endif; ?>
                            <tr bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
                                <td style="cursor: pointer; width:100px; text-align:center;">
                                    <?php echo $this->_tpl_vars['campos']['movimiento_servicio']; ?>

                                </td>
                                <td style="cursor: pointer; width:500px; text-align:left;">
                                    <?php echo $this->_tpl_vars['campos']['descripcion']; ?>

                                </td>
                                <td style="cursor: pointer; width:50px; text-align:center;" >
                                    <img class="Agregar Servicio" <?php if ($_GET['loc']): ?> onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=movimientoservicio&amp;cod=<?php echo $_GET['cod']; ?>
&amp;idmovimiento=<?php echo $this->_tpl_vars['campos']['id']; ?>
&amp;loc=1'"<?php else: ?> onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=movimientoservicio&amp;cod=<?php echo $_GET['cod']; ?>
&amp;idmovimiento=<?php echo $this->_tpl_vars['campos']['movimiento_servicio']; ?>
&amp;loc=1'" <?php endif; ?> title="Agregar Servicio" src="../../../includes/imagenes/ico_propiedades.gif"/> 
                                </td>
                            </tr>
                        
                            <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id']); ?>
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