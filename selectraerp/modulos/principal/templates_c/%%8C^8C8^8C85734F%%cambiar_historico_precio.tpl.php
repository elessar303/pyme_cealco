<?php /* Smarty version 2.6.21, created on 2017-07-26 14:39:29
         compiled from cambiar_historico_precio.tpl */ ?>
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
            $(document).ready(function()
            {
                $(\'#cerrar\').click(function() {
                var ventana = document.getElementById(\'miVentana\');
                ventana.style.display=\'none\';
                });
            });
            function leerCodigo(prod, precio, trans, nombre, id_det_sincro, precio_old)
            {
                $("#codigo_seguridad").val("");
                $("#valores").val("");
                var ventana = document.getElementById(\'miVentana\');
                ventana.style.marginTop = \'100px\';
                ventana.style.left = ((document.body.clientWidth-350) / 2) +  \'px\';
                ventana.style.display = \'block\';
                $("#valores").val(prod+"_"+precio+"_"+trans+"_"+nombre+"_"+id_det_sincro+"_"+precio_old);
            }
             //función que verifica el codigo seleccionado
            function procesarCodigo()
            {   
                var codigo = $("#codigo_seguridad").val();
                var option = $("#combo_option").val();
                var array  = $("#valores").val().split("_"); 
                $.ajax(
                {
                    type: \'GET\',
                    data: \'opt=getverificacionCodigo&\'+\'codigo=\'+codigo,
                    url: \'../../libs/php/ajax/ajax.php\',
                    success: function(data) 
                    {
                        if(data==1)
                        {
                            
                            actualizar_precio(array[0],array[1],array[2],array[3],array[4], array[5], option, codigo);
                        }
                        else
                        {
                            if(data==-4)
                            {
                                alert("Clave Vencida");
                                return false;
                            }
                            if(data==-1)
                            {
                                alert("Codigo ya se uso");
                                return false;
                            }
                            if(data==-2)
                            {
                                alert("No puede dejar el codigo vacio");
                                return false;
                            }
                            if(data==-3)
                            {
                                alert("Este Codigo no Corresponde a la tienda");
                                return false;
                            }
                            if(data!=-4 && data!=-1 && data!=-2 && data!=-3)
                            {
                                alert("Error, Verifique el formato de la clave");
                                return false;
                            }
                        }
                        
                    }
                }); 

            }
            //fin del verificar codigo
            function actualizar_precio(prod, precio, trans, nombre, id_det_sincro, precio_old, option,codigo)
            {
                varr=confirm(\'¿Desea Actualizar El Precio del Producto: \'+prod+\'? \\n (\'+nombre+\') \\n Precio Actual: \'+precio_old+\' - Precio Nuevo:\'+precio);
                if(varr)
                {
                    if(prod.value!=\'\')
                    {
                        $.ajax(
                        {
                            type: "GET",
                            url:  "../../libs/php/ajax/ajax.php",
                            data: "opt=actualizar_precio_producto2_codigo&prod="+prod+"&precio="+precio+"&trans="+trans+"&codigo="+codigo+"&option="+option,
                            success: function() 
                            {
                                window.location.reload(true);
                            }
                        });
                    }
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
                                        <img class="editar" onclick="leerCodigo('<?php echo $this->_tpl_vars['campos']['codigo_barra']; ?>
',<?php echo $this->_tpl_vars['campos']['precio']; ?>
,<?php echo $this->_tpl_vars['campos']['id_sincro']; ?>
, '<?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
',<?php echo $this->_tpl_vars['campos']['id']; ?>
, <?php echo $this->_tpl_vars['campos']['coniva1']; ?>
);" title="Actualizar Precio Historico" src="../../../includes/imagenes/restore_f2.png" width=15px />
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
<div id='miVentana' style='position: fixed; width: 350px; height: 190px; top: 0; left: 0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; border: #333333 3px solid; background-color: #FAFAFA; color: #000000; display:none;  -moz-opacity:0.8; -webkit-opacity:0.8; -o-opacity:0.9; -ms-opacity:0.9; background-color: #808080; overflow: auto; width: 500px; background: #fff; padding: 30px; -moz-border-radius: 7px; border-radius: 7px; -webkit-box-shadow: 0 3px 20px rgba(0,0,0,1); -moz-box-shadow: 0 3px 20px rgba(0,0,0,1); box-shadow: 0 3px 20px rgba(0,0,0,1); background: -moz-linear-gradient(#fff, #ccc); background: -webkit-gradient(linear, right bottom, right top, color-stop(1, rgb(255,255,255)), color-stop(0.57, rgb(230,230,230)));  
'>
 <div class="modal-header" align="right" style="margin-left: 100px; ">
 <button type="button" class="close" data-dismiss="modal" id="cerrar" aria-hidden="true">×</button>
 </div>

    <h1>Agregue el Codigo de seguridad asignado para el cambio de precio</h1>
    <table border="0"  align="center" width="200px">
        <tr>
            <td align="center" >
                <b>Codigo</b>
            </td>
            <td>
                <input type="text" name="codigo_seguridad" id="codigo_seguridad" class='form-text' />
                <input type="hidden" name="valores" id="valores" class='form-text' />
            </td>
        </tr>
        <tr>
            <td align="center" >
                <b>Ubicación De Cambio de Precio</b>
            </td>
            <td>
                <select name="combo_option" id="combo_option">
                    <option value="1"> Pyme</option>
                    <option value="2"> POS</option>
                    <option value="3"> Ambas</option>

                </select>
            </td>
        </tr>
    <tr>
        <td colspan="2" align="center">
            <br><p>
                <input align="center" type="submit" value="Procesar" style=" " onclick="procesarCodigo()" />
            </p> 
        </td>
    </tr>
    </table>

</div>