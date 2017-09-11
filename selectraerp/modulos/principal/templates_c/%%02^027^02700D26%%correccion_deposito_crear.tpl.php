<?php /* Smarty version 2.6.21, created on 2017-06-12 15:04:41
         compiled from correccion_deposito_crear.tpl */ ?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
  <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" /> <?php echo '
  <script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {

      $("input[name=\'aceptar\']").click(function() {
        if ($("#descripcion_localidad").val() == "") {
          $("#descripcion_localidad").focus();
          Ext.Msg.alert("Alerta", "Debe Ingresar la descripción de la localidad!");
          return false;
        }
      });

      // agregado el 22/01/14 para activar la region segun el estado q pertenece
      function cargarRegion(idSelect, idEstado) {
        var paramentros = "opt=cargarRegion&idEstado=" + idEstado;
        $.ajax({
          type: "POST",
          url: "../../libs/php/ajax/ajax.php",
          data: paramentros,
          beforeSend: function(datos) {
            $("#" + idSelect).html(\'Cargando...\');
          },
          success: function(datos) {
            $("#" + idSelect).html(datos);
          },
          error: function(datos, falla, otroobj) {
            $("#" + idSelect).html(\' Error...\');
          }
        });
      };
      // fin de la funcion
      // llamada de la funcion para cargar region
      id_estado = $("#id_estado_atiende").val();
      cargarRegion("regionCot", id_estado);
      // cuando cambia estado que atiende salta la funcion ajax
      $("#id_estado_atiende").change(function() {
        id_estado = $("#id_estado_atiende").val();
        cargarRegion("regionCot", id_estado);
      });
      //fin de la llamada

    });

    function verificarcodigo()
    {
      valor = $("#nro_deposito_usuario").val();
      if(valor!=\'\')
      {
        $.ajax(
        {
          type: "GET",
          url:  "../../libs/php/ajax/ajax.php",
          data: "opt=Validarnrodeposito&v1="+valor,
          beforeSend: function()
          {
              $("#notificacionVUsuario").html(MensajeEspera("<b>Veficando Nro. Cataporte..</b>"));
          },
          success: function(data)
          {
              resultado = data
              if(resultado=="-1")
              {
                  $("#nro_deposito_usuario").val("").focus();
                  $("#notificacionVUsuario").html("");
                  $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, este Nro. Cataporte ya existe.</b></span>");
              }
              if(resultado=="1")
              {
                  $("#notificacionVUsuario").html("");
                  $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Nro. Cataporte Disponible</b></span>");
              }
          }
        });
      }
    };
    //]]>
  </script>
  '; ?>

</head>

<body>
  <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="" method="post">
    <div id="datosGral">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
" />
      <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
" />
      <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
" />
      <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
" />
      <table style="width:100%; background-color: white;" border="0">
        <thead>
          <tr>
            <th colspan="3" class="tb-head" style="text-align:center;">
              &nbsp;
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td  class="label">
              Nro. Cataporte Nuevo
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;" width="40%">
              <input type="text" name="nro_deposito_usuario" size="60" id="nro_deposito_usuario" class="form-text" Onblur='verificarcodigo()'/><div id='notificacionVUsuario'></div>
            </td>
            <td  class="label" style="text-align: center;">
              <b>  Nro. Cataporte : <?php echo $this->_tpl_vars['nro_cataporte']; ?>
 </b>
            </td>
          </tr>
          <tr>
            <td  class="label">
              Monto Nuevo
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;">
              <input type="text" name="monto_usuario" size="60" id="monto_usuario" class="form-text" />
            </td>
            <td  class="label" style="text-align: center;">
              <b>  Monto : <?php echo $this->_tpl_vars['monto']; ?>
 BS. </b>
            </td>
          </tr>
          <tr>
            <td  class="label">
              Observación
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;" colspan="2">
              <input type="text" name="observacion" size="60" id="observacion" class="form-text" />
              <input type="hidden" name="cod"  id="cod" value="<?php echo $this->_tpl_vars['cod']; ?>
" class="form-text" />
            </td>
          </tr>
        </tbody>
      </table>
      <table style="width:100%">
        <tbody>
          <tr class="tb-tit">
            <td>
              <input type="submit" name="aceptar" id="aceptar" value="Guardar" />
              <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </form>
</body>
</html>