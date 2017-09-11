<?php /* Smarty version 2.6.21, created on 2017-07-26 14:20:16
         compiled from precios_productos.tpl */ ?>
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
            function actualizar_precio(prod, precio, trans, nombre, id_det_sincro, precio_old)
            {

            //verficar si existen pendientes con el mismo producto
            if(id_det_sincro!=""){
                parametros = {
                "id_det_sincro" : id_det_sincro, "opt" : "pendiente_producto", "codigo_barra" : prod,
            }
                bandera=0;
                 $.ajax({
                    type: "POST",
                    url:  "../../libs/php/ajax/ajax.php",
                    data: parametros,
                    success: function(data) {

                    if(data!=1){
                        alert("Error, Tiene Actualizaciones Pendientes De Precios Anteriores.");
                        return false;
                    }else{

                        varr=confirm(\'¿Desea Actualizar El Precio del Producto: \'+prod+\'? \\n (\'+nombre+\') \\n Precio Actual: \'+precio_old+\' - Precio Nuevo:\'+precio)
                        if(varr){
                        if(prod.value!=\'\'){
                        $.ajax({
                        type: "GET",
                        url:  "../../libs/php/ajax/ajax.php",
                        data: "opt=actualizar_precio_producto2&prod="+prod+"&precio="+precio+"&trans="+trans,
                        success: function() {
                        window.location.reload(true);
                        }
                        });
                        }
                        }
                    }
                    }
                    });
               

            }else{
                return false;
            }

            
            

        
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
                        <td colspan="6">
                        Productos Pendiente por Actualizacion de Precios
                        </td>
                        </tr>
                        <tr class="tb-head">
                            <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                                <td><strong><?php echo $this->_tpl_vars['campos']; ?>
</strong></td>
                            <?php endforeach; endif; unset($_from); ?>
                            <td colspan="2"><strong>Opciones</strong></td> 
                        </tr>
                        <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                            <tr><td colspan="6" align="center"><?php echo $this->_tpl_vars['mensaje']; ?>
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
                                		<!--<td style="text-align:center;">
				                        <?php if ($this->_tpl_vars['campos']['foto'] == ""): ?>                                		
				                        &nbsp;
				                        <?php else: ?>
				                        <img src="../../imagenes/<?php echo $this->_tpl_vars['campos']['foto']; ?>
" width="100" align="absmiddle" height="100"/>
				                        <?php endif; ?>                                		
				                    		</td>-->
                                    <td style="text-align: center; width: 200px;"><?php echo $this->_tpl_vars['campos']['codigo_barra']; ?>
</td>
                                    <td style="text-align: center; width: 400px;"><?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
</td>
                                    <td style="text-align: center;"><?php echo $this->_tpl_vars['campos']['precio']; ?>
</td>
                                    <td style="text-align: center;"><?php echo $this->_tpl_vars['campos']['nombre_archivo']; ?>
</td>
                                    <td style="text-align: center;"><?php echo $this->_tpl_vars['campos']['fecha']; ?>
</td>
                                    <td style="cursor: pointer; width: 30px; text-align: center;" colspan="2">
                                        <img class="editar" onclick="actualizar_precio('<?php echo $this->_tpl_vars['campos']['codigo_barra']; ?>
',<?php echo $this->_tpl_vars['campos']['precio']; ?>
,<?php echo $this->_tpl_vars['campos']['id_sincro']; ?>
, '<?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
',<?php echo $this->_tpl_vars['campos']['id']; ?>
, <?php echo $this->_tpl_vars['campos']['coniva1']; ?>
);" title="Actualizar" src="../../../includes/imagenes/back.gif"/>
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
    </body>
</html>