<?php /* Smarty version 2.6.21, created on 2017-08-01 20:04:33
         compiled from cataportes.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'cataportes.tpl', 74, false),array('modifier', 'number_format', 'cataportes.tpl', 76, false),)), $this); ?>
<!DOCTYPE html>
<html>
<head>
  <?php echo '
    <script language="JavaScript" type="text/JavaScript">
      function cambiar_estatus(id)
      {
        if (!confirm(\'¿Esta Seguro De Cambiar Estatus De Cataporte?\'))
        {
          return false;
        }
       
        parametros={"id": id,  "opt": "cambiar_estatus_cataporte"};
        $.ajax(
        {
          type: "POST",
          url: "../../libs/php/ajax/ajax.php",
          data: parametros,
          dataType: "html",
          asynchronous: false,
          success: function(data)
          {
            if(data==1)
            {
              alert("Estatus Cambiado");
              location.reload();
            }else
            {
              if(data==-2)
              {
                alert("Error, Consulte Al Administrador");
                location.reload();
              }
            }
          }
        });
      }
    </script>
  '; ?>
  
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
  <body>
    <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="" method="post">
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
          <thead>
            <tr class="tb-head">
              <?php $_from = $this->_tpl_vars['cabecera']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
              <th style="text-align:center;"><?php echo $this->_tpl_vars['campos']; ?>
</th>
              <?php endforeach; endif; unset($_from); ?>
              <th colspan="4">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($this->_tpl_vars['cantidadFilas'] == 0): ?>
              <tr>
                <td colspan="10" style="text-align:center;">
                  <?php echo $this->_tpl_vars['mensaje']; ?>

                </td>
              </tr>
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
                  <td style="text-align:center;"><?php echo $this->_tpl_vars['campos']['nro_cataporte']; ?>
</td>
                  <td class="cantidades" style="text-align:center;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['fecha'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%Y") : smarty_modifier_date_format($_tmp, "%d-%m-%Y")); ?>
</td> 
                  <td style="padding-left:10px; text-align:center;"><?php echo $this->_tpl_vars['campos']['cant_bolsas']; ?>
</td> 
                  <td style="text-align:right; padding-right:20px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['campos']['monto'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", "") : number_format($_tmp, 2, ".", "")); ?>
</td>
                  <td style="cursor:pointer; width:30px; text-align:center">
                    <img class="editar" onclick="javascript: window.open('../../reportes/imprimir_copia_cataporte.php?codigo=<?php echo $this->_tpl_vars['campos']['id']; ?>
');" title="Imprimir Copia Cataporte" src="../../../includes/imagenes/ico_print.gif"/>
                  </td>
                  <td style="width: 30px; text-align:center">
                    <?php if (( $this->_tpl_vars['campos']['retirado'] != '' )): ?>
                      <img  class="editar"  title="Cataporte Retirado" src="../../../includes/imagenes/ico_ok.gif"/>
                    <?php else: ?>
                      <img onclick="cambiar_estatus(<?php echo $this->_tpl_vars['campos']['id']; ?>
);" title="Cataporte no Retirado" src="../../../includes/imagenes/ico_note.gif"/>
                    <?php endif; ?>

                  </td>
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