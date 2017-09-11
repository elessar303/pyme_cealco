<?php /* Smarty version 2.6.21, created on 2017-07-13 14:18:57
         compiled from snippets/inclusionesFpdUbicacion.tpl */ ?>
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


                  
                //window.open(\'../../reportes/\'+report+\'?fecha=\'+ini+\'&fecha2=\'+fin+\'&filtrado=\'+document.formulario.filtrado.value);

                window.open(\'../../fpdf/\'+report+"?"+ubicacion);
                //document.formulario.submit();
            }
            //]]>
            </script>
        '; ?>

    </head>
    <body>
    </body>
</html>