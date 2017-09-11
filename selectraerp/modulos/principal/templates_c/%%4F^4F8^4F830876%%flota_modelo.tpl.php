<?php /* Smarty version 2.6.21, created on 2017-08-24 11:45:10
         compiled from flota_modelo.tpl */ ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Flota De Vehiculos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <script type="text/javascript">
        <?php echo '
        function eliminar(objeto)
        {
            id=objeto.id;
            if(confirm(\'¿Esta Seguro De Eliminar Este Elemento?\'))
            {

                parametros=
                {
                    "opt": "eliminarModeloTransporte",
                    "id": id,
                };
                $.ajax({
                    type: \'POST\',
                    data: parametros,
                    url: \'../../libs/php/ajax/ajax.php\',
                    success: function(data) 
                    {
                        this.vcampos = eval(data);
                        if(data==1)
                        {
                            Ext.Msg.alert("¡Eliminacion Exitosa!");
                            location.reload();
                        }
                        else
                        {
                            Ext.Msg.alert("Error, Contacte al administrador del sistema");
                        }
                    }
                });

            }
            else
            {
                return false;
            }
        };
        '; ?>

        
        </script>

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
                        <tr class="tb-head" >
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td><b><?php echo $this->_tpl_vars['campos']; ?>
</b></td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="2" style="text-align:center;"><b>Opciones</b></td>
                        </tr>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="4"><?php echo $this->_tpl_vars['mensaje']; ?>
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
                            <td align="center"><?php echo $this->_tpl_vars['campos']['id']; ?>
</td>
                            <td align="center"><?php echo $this->_tpl_vars['campos']['descripcion_modelo']; ?>
</td>
                            <td style="cursor:pointer; width:30px; text-align:center;">
                                <img class="editar"  onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;cod=<?php echo $this->_tpl_vars['campos']['id']; ?>
&amp;pagina=<?php echo $this->_tpl_vars['pagina']; ?>
'" title="Editar" src="../../libs/imagenes/edit.gif"/>
                            </td>
                           <td style="cursor:pointer; width:30px; text-align:center;">
                                <img class="eliminar" onclick="eliminar(this)" id="<?php echo $this->_tpl_vars['campos']['id']; ?>
" title="Eliminar" src="../../libs/imagenes/delete.gif"/>
                            </td>
                        </tr>
    <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['cod_especialidad']); ?>
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