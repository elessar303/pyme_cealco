<?php /* Smarty version 2.6.21, created on 2017-08-28 16:24:38
         compiled from variacion_precios.tpl */ ?>
<!DOCTYPE html>
<!--
Creado por: Lucas Sosa
Acción (es):

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
<script language="JavaScript">
function crearArchivo(id, correl)
{
    var c = confirm("Seguro desea Generar el Archivo?")
    if(c == true)
    {
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=crearArchivo&v1="+id+"&correl="+correl,
            beforeSend: function(){
                $("#notificacion").html(MensajeEspera("<b>Generando Archivo<b>"));
            },
            success: function(data){
                $("#notificacion").html("");
                $("#notificacion").html("<img align=\\"absmiddle\\" src=\\"../../libs/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Archivo Generado</b></span>");
                alert(\'Archivo Generado\');
                setTimeout(
                function() 
                {
                   location.reload();
                }, 1000);
            }
        });
    }
}

</script>
'; ?>

</head>
<body>
<div id="notificacion"></div>
    <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="" method="post">
        <div id="datosGral" class="x-hide-display">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_buscar_botones.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <br/>
            <table class="seleccionLista">
                <thead>
                    <tr class="tb-head">
                        <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                        <th><?php echo $this->_tpl_vars['campos']; ?>
</th>
                        <?php endforeach; endif; unset($_from); ?>
                        <th colspan="4">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
                    <tr><td colspan="10" style="text-align:center;"><?php echo $this->_tpl_vars['mensaje']; ?>
</td></tr>
                    <?php else: ?>
                    <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
                    <?php if ($this->_tpl_vars['i'] % 2 == 0): ?>
                    <?php $this->assign('bgcolor', ""); ?>
                    <?php else: ?>
                    <?php $this->assign('bgcolor', "#cacacf"); ?>
                    <?php endif; ?>
                <tr bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
                    <!--<td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['codigo_barras']; ?>
</td>-->
                    <td style="text-align:center; padding-right:20px;"><?php echo $this->_tpl_vars['campos']['correlativo']; ?>
</td>
                    <!--<td style="padding-left:10px;"><?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
</td>-->
                    <!--<td class="cantidades"><?php echo $this->_tpl_vars['campos']['precio_sin_iva']; ?>
</td>-->
                    <td  style="text-align:center;"><?php echo $this->_tpl_vars['campos']['fecha']; ?>
</td>
                    <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['usuario']; ?>
</td>
                    <!--<td style="cursor:pointer; width:30px; text-align:center">
                        <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;cod=<?php echo $this->_tpl_vars['campos']['id_item']; ?>
'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                    </td>-->
                    <?php if ($this->_tpl_vars['campos']['estatus'] == 'Activo'): ?>
                        <td style="cursor: pointer; width: 30px; text-align:center">
                            <img class="editar" onclick="javascript: window.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
&amp;opt_subseccion=edit&amp;cod=<?php echo $this->_tpl_vars['campos']['id_var_precio_cab']; ?>
'" title="Editar" src="../../../includes/imagenes/edit.gif"/>
                        </td>
                    <?php else: ?>
                        <td style="cursor: pointer; width: 30px; text-align:center">
                            
                        </td>
                    <?php endif; ?>
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="procesar" onclick="crearArchivo(<?php echo $this->_tpl_vars['campos']['id_var_precio_cab']; ?>
,<?php echo $this->_tpl_vars['campos']['correlativo']; ?>
)" width="24px" title="Generar Archivo" src="../../../includes/imagenes/3.png"/>
                    </td>
                    <!--Imprime el reporte de prductos actualizados/registrados HZ-->
                    <td style="cursor: pointer; width: 30px; text-align:center">
                        <img class="impresion" onclick="javascript:window.open('../../reportes/reporte_variacion_precio.php?id_var_precio_cab=<?php echo $this->_tpl_vars['campos']['correlativo']; ?>
', '');" title="Imprimir Detalle de la Variaci&oacute;n" src="../../../includes/imagenes/ico_print.gif"/>
                    </td>
                    
                        
                    <!-- boton donde se agregan los seriales -->
                      

                </tr>
                <?php $this->assign('ultimo_cod_valor', $this->_tpl_vars['campos']['id_item']); ?>
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