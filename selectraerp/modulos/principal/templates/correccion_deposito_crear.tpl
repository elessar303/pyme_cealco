<!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> {include file="snippets/header_form.tpl"}
  <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
  <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" /> {literal}
  <script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {

      $("input[name='aceptar']").click(function() {
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
            $("#" + idSelect).html('Cargando...');
          },
          success: function(datos) {
            $("#" + idSelect).html(datos);
          },
          error: function(datos, falla, otroobj) {
            $("#" + idSelect).html(' Error...');
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
      if(valor!='')
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
                  $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este Nro. Cataporte ya existe.</b></span>");
              }
              if(resultado=="1")
              {
                  $("#notificacionVUsuario").html("");
                  $("#notificacionVUsuario").html("<img align=\"absmiddle\" src=\"../../../includes/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Nro. Cataporte Disponible</b></span>");
              }
          }
        });
      }
    };
    //]]>
  </script>
  {/literal}
</head>

<body>
  <form id="form-{$name_form}" name="formulario" action="" method="post">
    <div id="datosGral">
      {include file = "snippets/regresar.tpl"}
      <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}" />
      <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}" />
      <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}" />
      <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}" />
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
              <b>  Nro. Cataporte : {$nro_cataporte} </b>
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
              <b>  Monto : {$monto} BS. </b>
            </td>
          </tr>
          <tr>
            <td  class="label">
              Observación
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;" colspan="2">
              <input type="text" name="observacion" size="60" id="observacion" class="form-text" />
              <input type="hidden" name="cod"  id="cod" value="{$cod}" class="form-text" />
            </td>
          </tr>
        </tbody>
      </table>
      <table style="width:100%">
        <tbody>
          <tr class="tb-tit">
            <td>
              <input type="submit" name="aceptar" id="aceptar" value="Guardar" />
              <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </form>
</body>
</html>
