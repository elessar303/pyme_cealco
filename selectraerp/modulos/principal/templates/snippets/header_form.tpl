<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {if $campo_seccion neq null}
            {*$campo_seccion[0].nom_menu*}
            {assign var=nom_menu value=$campo_seccion[0].nom_menu}
            {assign var=imagen value=$campo_seccion[0].img_ruta}
        {else}
            {assign var=nom_menu value=$cabeceraSeccionesByOptMenu[0].nom_menu}
            {if $subseccion[0].descripcion neq null}
                {*$subseccion[0].descripcion*}
                {assign var=nom_menu value=$subseccion[0].descripcion}
                {assign var=imagen value=$subseccion[0].img_ruta}
            {/if}
        {/if}
        {if $cabeceraSeccionesByOptMenu[0].cod_modulo eq 54}
            {assign var=valcolap value=0}
        {else}
            {assign var=valcolap value=1}
        {/if}
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){
                    $("#buscar").click(function(){
                        /*Envía el data de búsqueda en tb_head.tpl*/
                        $("form").submit();
                    });
                });
                function direccionar(url){
                    window.location.href=url;
                }
                Ext.onReady(function(){
                    var img = $("#imagen").val();//{/literal}{$imagen}{literal}
                    //var titulo = $("#descripcion").val();
                    var titulo = img != null ? '<img src='+img+' width="22" height="22" class="icon" /> {/literal}{$nom_menu}{literal}' :'';
                    var valcolap = {/literal}{$valcolap}{literal}
                    var panelFormulario = new Ext.Panel({
                        //title:'<img src='+img+' width="22" height="22" class="icon" /> {/literal}{$nom_menu}{literal}',
                        title:titulo,
                        autoHeight:600,
                        width:'100%',
                        collapsible:valcolap ? true : false,
                        titleCollapse:true,
                        contentEl:'datosGral',
                        frame:true/*,
                        // Pensando colocar una barra aqui que sustituya la que aun esta en html con tabla y agrgarle un boton de regresar
                        tbar:[{
                        //iconCls:'print-icon'
                            contentEl:'boton_regresar'
                        }]*/
                    });
                    // La variable $name_form viene del *.php correspondiente al *.tpl donde esta plantilla es incluida y asi form-{/literal}{$name_form}{literal} es el id del formulario
                    panelFormulario.render("form-{/literal}{$name_form}{literal}");
                });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
    </body>
</html>