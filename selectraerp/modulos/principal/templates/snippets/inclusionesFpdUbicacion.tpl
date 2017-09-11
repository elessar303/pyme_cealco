<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes" />
        <title></title>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-timepicker-addon.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        {*if $campo_seccion neq NULL}
            {assign var=nom_menu value=$campo_seccion[0].nom_menu}
        {else}
            {assign var=nom_menu value=$cabeceraSeccionesByOptMenu[0].nom_menu}
        {/if}
        {if $cabeceraSeccionesByOptMenu[0].cod_modulo eq 54}
            {assign var=valcolap value=0}
        {else}
            {assign var=valcolap value=1}
        {/if*}
        {literal}
            <script type="text/javascript">//<![CDATA[
            Ext.onReady(function(){
                var formpanel = new Ext.Panel({
                    //title:' <img src='+$("#imagen").val()+' width="22" height="22" class="icon" /> {/literal}{*$nom_menu*}{literal}',
                    title:' <img src='+$("#imagen").val()+' width="22" height="22" class="icon" /> {/literal}{$campo_seccion[0].nom_menu}{literal}',
                    autoHeight: 600,
                    width: '100%',
                    collapsible: true,// {/literal}{*$valcolap*}{literal} ? true : false,
                    titleCollapse: true,
                    contentEl:'datosGral',
                    frame:true
                });
                formpanel.render("formulario");
                $("input[name='aceptar'], input[name='cancelar']").button();//Coloca estilo JQuery
                $("#formato").buttonset();
            });
            function valida_envia(rpt1, rpt2){


                // if (document.formulario.ubicacion.value.length == 0){
                //    alert("Debe seleccionar una Ubicacion.");
                //    document.formulario.ubicacion.focus();
                //    return false;
                // }      

				var ubicacion= document.formulario.ubicacion.value;
                    if(ubicacion!="0"){
                        ubicacion="&ubicacion="+ubicacion;
                    }else{
                        ubicacion="&ubicacion=null";
                    }    
                
                var report = document.formulario.radio.checked ? rpt1 : rpt2;


                  
                //window.open('../../reportes/'+report+'?fecha='+ini+'&fecha2='+fin+'&filtrado='+document.formulario.filtrado.value);

                window.open('../../fpdf/'+report+"?"+ubicacion);
                //document.formulario.submit();
            }
            //]]>
            </script>
        {/literal}
    </head>
    <body>
    </body>
</html>