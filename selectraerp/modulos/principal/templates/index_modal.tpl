{$cambio}
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
   <title></title>
   {include file="header_modal.tpl"}
   {literal}
   <style type="text/css">
      .panel{
         padding: 5px;
      }

      .p4 {
         padding: 4px;
      }

      .w70 {
         width: 70px;
      }

      .w200{
         width: 200px;
      }

      .w60 {
         width: 60px;
      }

      .w90 {
         width: 90px;
      }
   </style>
   {/literal}
</head>
<body>
   <div class="panel">
      {include file=$archivotpl|default:"sin_informacion.tpl"}
      {if $msgAUsuario neq ""}
      {literal}
         <script type="text/javascript">//<![CDATA[
            Ext.onReady(function() {
            new Ext.Window({
               title: 'Notificaci&oacute;n de Transacci&oacute;n',
               modal: true,
               autoHeight: true,
               width: 300,
               html: '{/literal}{$msgAUsuario}{literal}'
            }).show();
            });
            //]]>
         </script>
      {/literal}
      {/if}
   </div>
   {include file="foolter.tpl"}
</body>
</html>