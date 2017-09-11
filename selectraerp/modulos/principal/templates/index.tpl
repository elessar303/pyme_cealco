{$cambio}
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>.: SISCOL :.</title>
        <link rel="shortcut icon" href="../../../includes/imagenes/logo.ico" />
        {include file="header.tpl"}
    </head>
    <body>
        <div id="Cabecera">
            {include file="cabecera_principal.tpl"}
        </div>
        <div id="Lateral">
            {include file="menu.tpl"}
        </div>
        <div id="Contenido">
            <div class="Relleno">
                {include file=$archivotpl|default:"sin_informacion.tpl"}
                {$msgAUsuario}
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
        </div>
        {include file="foolter.tpl"}
    </body>
</html>