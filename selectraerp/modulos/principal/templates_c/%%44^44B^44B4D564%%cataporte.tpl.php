<?php /* Smarty version 2.6.21, created on 2017-08-01 20:04:36
         compiled from cataporte.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'cataporte.tpl', 223, false),array('modifier', 'number_format', 'cataporte.tpl', 225, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" />
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
            //variable Global
            var acumulado=0;
            function generardeposito() 
            {
                var checkboxValues = "";
                $(\'input[id="id_depositos[]"]:checked\').each(function() 
                {
                    checkboxValues += "\'"+$(this).val() + "\',";
                });
                if(checkboxValues=="")
                {
                    $("#resultado").empty();
                    return;
                }
                //eliminamos la última coma.
                checkboxValues = checkboxValues.substring(0, checkboxValues.length-1);
                parametros = {"arreglo" : checkboxValues, "opt" : "calcular_monto_cataporte"}
                $.ajax(
                {
                    type: "POST",
                    url: "../../libs/php/ajax/ajax.php",
                    data: parametros,
                    dataType: "html",
                    asynchronous: false,
                    beforeSend: function() 
                    {
                        $("#resultado").empty();
                        $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
                    },
                    error: function()
                    {
                        alert("error peticion ajax");
                    },
                    success: function(data)
                    {
                        $("#resultado").html(data);
                        ///// verificamos su estado
                    }
                });
            }
            
            function modificar()
            {
              document.getElementById(\'monto_usuario_tr\').style.visibility=\'visible\';
              document.getElementById(\'monto_usuario_tr1\').style.visibility=\'visible\';
            }

            function enviar()
            {
                nro = $("#cantidad").val();
                
                var siga = '; ?>
<?php echo $this->_tpl_vars['parametros'][0]['codigo_siga']; ?>
<?php echo ';
                $.ajax(
                {
                    type: "POST",
                    url: "../../libs/php/ajax/ajax.php",
                    data: {"opt": "MontoAcumulado", "tipo": 0} ,
                    dataType: "html",
                    asynchronous: false,
                   
                    error: function()
                    {
                        alert("error peticion ajax");
                    },
                    success: function(data)
                    {
                        acumulado=parseFloat(data);
                    }
                });
                if(document.form1.monto_usuario.value==\'\' )
                {
                    alert(\'Debe Ingresar el Monto\');
                    document.form1.monto_usuario.focus();
                    return false;
                }
                if(document.form1.monto_usuario.value > acumulado)
                {
                    alert(\'El monto ingresado es mayor al acumulado\');
                    document.form1.monto_usuario.focus();
                     return false;
                }
               
                if(document.form1.nro_cataporte.value==\'\' )
                {
                    alert(\'Debe Ingresar el Nro del Cataporte\');
                    document.form1.nro_cataporte.focus();
                    exit;
                }
                if(document.form1.cantidad.value==\'\' )
                {
                    alert(\'Debe Ingresar la Cantidad de Bolsas\');
                    document.form1.cantidad.focus();
                    exit;
                }
                if(document.form1.cat_val.value==1 )
                {
                    alert(\'Debe Ingresar un Nro de Cataporte No Usado\');
                    document.form1.nro_cataporte.focus();
                    exit;
                }
                for(var i = 1; i<=nro; i++)
                {
                    if(document.getElementById(\'depositos\'+i).value==\'\' )
                    {
                        alert(\'Debe Ingresar el Nro de Bolsa \'+i);
                        document.getElementById(\'depositos\'+i).focus();
                        exit;
                    }
                }
                if (confirm(\'¿Generar Cataporte: \'+siga+\'?\\n OJO: Verificar que este Codigo SIGA corresponda a este punto de venta\'))
                {
                    document.form1.submit();
                }
            }

            $(document).ready(function ()
            {
                $.ajax(
                {
                    url: \'verificar_cataporte.php\',
                    type: "post",
                    data: "val=limpiar",
                    dataType: \'html\',
                    beforeSend: function() 
                    {
                        $("#resultado").empty();
                        $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
                    },
                    success: function(data)
                    {
                        $("#resultado").html(data);
                    }
                });
            });

            function crearCampos(cantidad)
            {
                var div = document.getElementById("campos_dinamicos");
                while(div.firstChild)div.removeChild(div.firstChild); // remover elementos;
                for(var i = 1, cantidad = Number(cantidad); i <= cantidad; i++)
                {
                    var salto = document.createElement("P");
                    var input = document.createElement("input");
                    var text = document.createTextNode("Bolsa Cataporte " + i + ": ");
                    input.setAttribute("name", "depositos[]");
                    input.setAttribute("id", "depositos"+i);
                    input.setAttribute("size", "20");
                    input.setAttribute("onkeyup", "aMays(event, this)");
                    input.setAttribute("onblur", "aMays(event, this)");
                    input.className = "input";
                    salto.appendChild(text);
                    salto.appendChild(input);
                    div.appendChild(salto);
                }
            }
            
            function aMays(e, elemento) 
            {
                tecla=(document.all) ? e.keyCode : e.which; 
                elemento.value = elemento.value.toUpperCase();
            }

            function comprobar() 
            {
                consulta = $("#nro_cataporte").val();    
                parametros={\'opt\': \'verificar_cataporte\', \'val\': consulta}
                $("#resultado2").delay(1000).queue(function(n) 
                {
                    $("#resultado2").html(\'<img src="imagenes/ajax-loader.gif" />\');
                            $.ajax(
                            {
                                type: "POST",
                                url: "../../libs/php/ajax/ajax.php",
                                data: parametros,
                                dataType: "html",
                                error: function()
                                {
                                    alert("error petici�n ajax");
                                },
                                success: function(data)
                                {
                                    $("#resultado2").html(data);
                                    n();
                                }
                            });
                });
            }

        </script>       
        '; ?>
        
    <title>Cataporte</title>
    </head>

    <body>
        <form name="formulario" id="formulario">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <table style="width:50%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista" align="center">
                    <thead>
                        <tr>
                            <th class="tb-head" style="text-align:center;" colspan="5">Generar Cataporte para Punto de Venta: <?php echo $this->_tpl_vars['parametros'][0]['codigo_siga']; ?>
</th>
                        </tr>
                        <tr>
                        	<td align="left">N°</td>
                        	<td align="center">Fecha de Transferencia</td>
                        	<td align="center">N° de Transferencia</td>
                        	<td align="center">Monto Acumulado</td>
                        	<td align="center">Acci&oacute;n</td>
        				</tr>
                        <?php $this->assign('counter', '1'); ?>
                        <?php $this->assign('nro', '1'); ?>
                		<?php $_from = $this->_tpl_vars['consulta']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['dato']):
        $this->_foreach['outer']['iteration']++;
?>
                		<tr>
        	                <td align="left"><?php echo $this->_tpl_vars['nro']; ?>
</td>
        	                <td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['dato']['fecha_deposito'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td>
        	                <td align="center"><?php echo $this->_tpl_vars['dato']['nro_deposito']; ?>
</td>
                            <td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['dato']['monto_acumulado'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", "") : number_format($_tmp, 2, ".", "")); ?>
</td>
        	                <td align="center">
                                <?php if ($this->_tpl_vars['dato']['monto_acumulado'] == '0.00'): ?>
                                    <p>Sin Efectivo <p>
                                <?php else: ?>
                                    <input type="checkbox" value="<?php echo $this->_tpl_vars['dato']['nro_deposito']; ?>
" id="id_depositos[]" onclick="generardeposito();" />
                                <?php endif; ?>

                            </td>
                        
                		</tr>
                   		<?php $this->assign('counter', $this->_tpl_vars['counter']+1); ?>
                        <?php $this->assign('nro', $this->_tpl_vars['nro']+1); ?>
                        <?php endforeach; endif; unset($_from); ?>
                    </thead>
                    <tbody>
                        <tr class="tb-head" align="center">
                            <td align='center' colspan="5">
                            &nbsp;
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <br>
        <form method='POST' action='generar_cataporte_fin.php' name='form1' >
            <div id="resultado" width='50%' align="center"></div>
            <input type="text" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
" hidden="hidden">
            <input type="text" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
" hidden="hidden">
        </form>  
    </body>
</html>    