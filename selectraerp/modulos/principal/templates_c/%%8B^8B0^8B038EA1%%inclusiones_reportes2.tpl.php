<?php /* Smarty version 2.6.21, created on 2017-08-13 22:32:59
         compiled from snippets/inclusiones_reportes2.tpl */ ?>
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
                <?php echo '
            <script type="text/javascript">//<![CDATA[
            Ext.onReady(function(){
                var formpanel = new Ext.Panel({
                    //title:\' <img src=\'+$("#imagen").val()+\' width="22" height="22" class="icon" /> '; ?>
<?php echo '\',
                    title:\' <img src=\'+$("#imagen").val()+\' width="22" height="22" class="icon" /> '; ?>
<?php echo $this->_tpl_vars['campo_seccion'][0]['nom_menu']; ?>
<?php echo '\',
                    autoHeight: 600,
                    width: \'100%\',
                    collapsible: true,// '; ?>
<?php echo ' ? true : false,
                    titleCollapse: true,
                    contentEl:\'datosGral\',
                    frame:true
                });
                formpanel.render("formulario");
                $("input[name=\'aceptar\'], input[name=\'cancelar\']").button();//Coloca estilo JQuery
                $("#formato").buttonset();
            });
            function valida_envia(rpt1, rpt2){
                if (document.formulario.fecha.value.length == 0){
                   alert("Debe seleccionar una fecha para el documento.");
                   document.formulario.fecha.focus();
                   return false;
                }
                var inputs =document.getElementsByTagName("input");
                var flag_fecha = false;
                for(var i=0;i<inputs.length;i++){
                    if(inputs[i].getAttribute("name") == "cant_fechas"){
                        var v = inputs[i].getAttribute("value");
                            i = inputs.length;
                            flag_fecha = true;
                    }
                }
                var flag_filtro = false;
                for(var i=0;i<inputs.length;i++){
                    if(inputs[i].getAttribute("name") == "tiene_filtro"){
                        var filtro = inputs[i].getAttribute("value");
                            i = inputs.length;
                            flag_filtro = true;
                    }
                }
                var ini = document.formulario.fecha.value;
                if(document.formulario.siga)
                var siga =document.formulario.siga.value;
                var tipo_mov = \'0\';
                var tipo_mov = document.getElementById("tipo_mov");
                tipo_mov=tipo_mov.value;
                //var producto = document.formulario.producto; 
                //alert("tamaño"+producto.options.length);
                var flag = 0;
                var producto1 = \'\';
               
                var filtro_codigo = document.getElementById("filtro_codigo");
                filtro_codigo=filtro_codigo.value;

                        

                if(producto1!=""){
                    producto1="&producto="+producto1;}
                    else{
                        producto1="&producto=null";
                    }
                if(siga!="0"){
                siga="&siga="+siga;
                    }else{
                    siga="&siga=null";
                    }

                if(tipo_mov!="0"){
                tipo_mov="&tip_mov="+tipo_mov;
                    }else{
                    tipo_mov="&tipo_mov=null";
                    }

                    if(filtro_codigo!=""){
                filtro_codigo="&filtro_codigo="+filtro_codigo;
                    }else{
                    filtro_codigo="&filtro_codigo=0";
                    }

                var fin = flag_fecha ? ( v == "2" ? document.formulario.fecha2.value : null) : null;
                var report = document.formulario.radio[1].checked ? rpt1 : rpt2;
                var params = !fin ? \'?fecha=\'+ini : \'?fecha=\'+ini+\'&fecha2=\'+fin;
                    params = flag_filtro ? params + \'&filtrado_por=\'+filtro : params;
                //window.open(\'../../reportes/\'+report+\'?fecha=\'+ini+\'&fecha2=\'+fin+\'&filtrado=\'+document.formulario.filtrado.value);
                window.open(\'../../reportes/\'+report+params+siga+producto1+tipo_mov+filtro_codigo);
                //document.formulario.submit();
            }
            //]]>
            </script>
        '; ?>

    </head>
    <body>
    </body>
</html>