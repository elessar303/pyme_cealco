<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    {include file="snippets/header_form.tpl"}
        <!--link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        
            <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>-->
            <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
            <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
            {literal}
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function(){

                $("#aceptar").click(function(){
                    showLoading();
                });

                function showLoading() {
                    document.getElementById('loadingmsg').style.display = 'block';
                    document.getElementById('loadingover').style.display = 'block';
                }

                $("input[name='aceptar']").click(function(){
                    if($("#descripcion_almacen").val()==""){
                        $("#descripcion_almacen").focus();
                        Ext.Msg.alert("Alerta","Debe Ingresar la descripción del almacen!");
                        return false;
                    }
                });
            });
            //]]>
        </script>

        <style type="text/css">
        #loadingmsg {
          color: black;
          background: #fff; 
          padding: 10px;
          position: fixed;
          top: 50%;
          left: 50%;
          z-index: 100;
          margin-right: -25%;
          margin-bottom: -25%;
      }
      #loadingover {
          background: black;
          z-index: 99;
          width: 100%;
          height: 100%;
          position: fixed;
          top: 0;
          left: 0;
          -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";
          filter: alpha(opacity=80);
          -moz-opacity: 0.8;
          -khtml-opacity: 0.8;
          opacity: 0.8;
      }
  </style>
  {/literal}
</head>
<div id='loadingmsg' style='display: none;'>Procesando, por favor espere...</div>
<div id='loadingover' style='display: none;'></div>
<body>
    <form id="form-{$name_form}" name="formulario" action="" method="post">
        <div id="datosGral">
          {if $smarty.get.loc}
          {include file = "snippets/regresar_boton_alm_loc.tpl"}   
          {else}
          {include file = "snippets/regresar_boton.tpl"}
          {/if}
          <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
          <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
          <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
          <input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}"/>
          <table style="width:100%; background-color: white;">
            <thead>
                <tr>
                    <th colspan="8" class="tb-head" style="text-align:center;">
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" class="label">
                        Tabla
                    </td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="tabla" size="60" id="tabla" class="form-text" required="required"/>
                        {html_options values=$option_values_numero output=$option_output_numero}
                    </td>

                    <td colspan="3" class="label">
                        Columna
                    </td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="columna" size="60" id="columna" class="form-text" required="required"/>
                        {html_options values=$option_values_numero output=$option_output_numero}
                    </td>
                </tr>


            </tbody>
        </table>
        <table style="width:100%">
            <tbody>
                <tr class="tb-tit">
                    <td>
                        <input type="submit" name="aceptar" id="aceptar" value="Guardar"/>
                        <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}';"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
</body>
</html>