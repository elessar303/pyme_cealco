<!DOCTYPE html>
<html>
<head>
  {literal}
    <script language="JavaScript" type="text/JavaScript">
      function cambiar_estatus(id)
      {
        if (!confirm('¿Esta Seguro De Cambiar Estatus De Cataporte?'))
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
  {/literal}  
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  {include file="snippets/header_form.tpl"}
</head>
  <body>
    <form id="form-{$name_form}" name="formulario" action="" method="post">
      <div id="datosGral" class="x-hide-display">
        {include file = "snippets/regresar_buscar_botones.tpl"}
        {include file = "snippets/tb_head.tpl"}
        <br/>
        <table class="seleccionLista">
          <thead>
            <tr class="tb-head">
              {foreach from=$cabecera key=i item=campos}
              <th style="text-align:center;">{$campos}</th>
              {/foreach}
              <th colspan="4">Opciones</th>
            </tr>
          </thead>
          <tbody>
            {if $cantidadFilas eq 0}
              <tr>
                <td colspan="10" style="text-align:center;">
                  {$mensaje}
                </td>
              </tr>
            {else}
              {foreach from=$registros key=i item=campos}
                {if $i mod 2 eq 0}
                  {assign var=bgcolor value=""}
                {else}
                  {assign var=bgcolor value="#cacacf"}
                {/if}
                <tr bgcolor="{$bgcolor}">
                  <td style="text-align:center;">{$campos.nro_cataporte}</td>
                  <td class="cantidades" style="text-align:center;">{$campos.fecha|date_format:"%d-%m-%Y"}</td> 
                  <td style="padding-left:10px; text-align:center;">{$campos.cant_bolsas}</td> 
                  <td style="text-align:right; padding-right:20px;">{$campos.monto_usuario|number_format:2:".":""}</td>
                  <td style="cursor:pointer; width:30px; text-align:center">
                    <img class="editar" onclick="javascript: window.open('../../reportes/imprimir_copia_cataporte.php?codigo={$campos.id}');" title="Imprimir Copia Cataporte" src="../../../includes/imagenes/ico_print.gif"/>
                  </td>
                  <td style="width: 30px; text-align:center">
                    {if ($campos.retirado neq '') }
                      <img  class="editar"  title="Cataporte Retirado" src="../../../includes/imagenes/ico_ok.gif"/>
                    {else}
                      <img onclick="cambiar_estatus({$campos.id});" title="Cataporte no Retirado" src="../../../includes/imagenes/ico_note.gif"/>
                    {/if}

                  </td>
                </tr>
                {assign var=ultimo_cod_valor value=$campos.id_item}
              {/foreach}
            {/if}
          </tbody>
        </table>
        {include file = "snippets/navegacion_paginas.tpl"}
      </div>
    </form>
  </body>
</html>