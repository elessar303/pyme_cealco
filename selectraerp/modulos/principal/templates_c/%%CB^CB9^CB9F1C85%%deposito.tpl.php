<?php /* Smarty version 2.6.21, created on 2017-07-21 15:41:07
         compiled from deposito.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'deposito.tpl', 110, false),)), $this); ?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="autor" /> 
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
    <?php echo '
        <script language="JavaScript" type="text/JavaScript">

            function generardeposito() 
            {

                venta_pyme=$("#venta_pyme").val(); var checkboxValues = ""; 
                $(\'input[id="id_cajeros[]"]:checked\').each(function() 
                { 
                    checkboxValues += $(this).val() + ","; 
                }); 

                if(checkboxValues=="")
                { 
                    $("#resultado").empty(); return; 
                } 
                //eliminamos la última coma. 
                checkboxValues = checkboxValues.substring(0, checkboxValues.length-1); 
                parametros = { "arreglo" : checkboxValues, "opt" : "calcular_monto_deposito", "venta_pyme" : venta_pyme } 
                $.ajax(
                { 
                    type: "POST", url: "../../libs/php/ajax/ajax.php",
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
                        $("#resultado").html(data); ///// verificamos su estado 
                    }
                }); 
            }

            function enviar()
            { 
                var siga = '; ?>
<?php echo $this->_tpl_vars['parametros'][0]['codigo_siga']; ?>
<?php echo ';
                if(document.form1.banco.value==\'000\')
                { 
                    alert(\'Debe Seleccionar un Banco\'); return false; 
                } 
                if (confirm(\'¿Generar Deposito para Punto de Venta: \'+siga+\'?\\n OJO: Verificar que este Codigo SIGA corresponda a este punto de venta\'))
                {
                    document.form1.submit(); 
                } 
            }

            $(document).ready(function ()
                { 
                    $.ajax
                    ({
                        url: \'verificar_deposito.php\', type: "post", dataType: \'html\', beforeSend: function() 
                        { 
                            $("#resultado").empty(); $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\'); 
                        }, 
                        success: function(data)
                        { 
                            $("#resultado").html(data); 
                        } 
                    }); 
                });

        </script>
    '; ?>

    <title>Cierre de Ventas</title>

</head>

<body>
  <form name="formulario" id="formulario">
    <input type="hidden" name="venta_pyme" id="venta_pyme" value="<?php echo $this->_tpl_vars['venta_pyme']; ?>
" />
    <div id="datosGral" class="x-hide-display">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <table style="width:50%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista" align="center">
        <thead>
          <tr>
            <th class="tb-head" style="text-align:center;" colspan="7">Generar Transferencia para Punto de Venta: <?php echo $this->_tpl_vars['parametros'][0]['codigo_siga']; ?>
</th>
          </tr>
          <tr>
            <td align="left">N°</td>
            <td align="center">Fecha</td>
            <td align="center">Cajero</td>
            <td align="center">Fecha Inicio</td>
            <td align="center">Fecha Fin</td>
            <td align="center">Monto</td>
            <td align="center">Acci&oacute;n</td>
          </tr>
          <?php $this->assign('counter', '1'); ?> <?php $this->assign('nro', '1'); ?> 
          <?php $_from = $this->_tpl_vars['consulta']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['dato']):
        $this->_foreach['outer']['iteration']++;
?>
              <tr>
                <td align="left"><?php echo $this->_tpl_vars['nro']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['fecha_arqueo']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['NAME']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['fecha_venta_ini']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['dato']['fecha_venta_fin']; ?>
</td>
                <td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['dato']['efectivo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", "") : number_format($_tmp, 2, ".", "")); ?>
</td>
                <td align="center"><input type="radio" value="<?php echo $this->_tpl_vars['dato']['id']; ?>
" id="id_cajeros[]"  name="id_cajeros[]" onclick="generardeposito()"></td>
              </tr>
              <?php $this->assign('counter', $this->_tpl_vars['counter']+1); ?> 
              <?php $this->assign('nro', $this->_tpl_vars['nro']+1); ?> 
          <?php endforeach; endif; unset($_from); ?>
        </thead>
        <tbody>
          <tr class="tb-head" align="center">
            <td align='center' colspan="7">
              &nbsp;
            </td>

          </tr>
        </tbody>
      </table>
  </form>
  </div>
  <br>
  <form method='POST' action='generar_deposito_fin2.php' name='form1'>
    <div id="resultado" width='50%' align="center"></div>
    <input type="text" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
" hidden="hidden">
    <input type="text" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
" hidden="hidden">
  </form>
</body>

</html>