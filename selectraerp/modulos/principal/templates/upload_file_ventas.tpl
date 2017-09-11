<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Junior Ayala" />
        <title></title>
        {include file="snippets/inclusiones_reportes.tpl"}
    </head>
    {literal}
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                $("#fecha1").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha2" ).datepicker( "option", "minDate", selectedDate );
                    }
                });
                $("#fecha2").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                    //numberOfMonths: 1,
                    //yearRange: "-100:+100",
                    dateFormat: "yy-mm-dd",
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datepicker( "option", "maxDate", selectedDate );
                    }
                });
            });
            //]]>
            </script>
        {/literal}
    <body>
        <form name="formulario" id="formulario" method="post">
        <div id="datosGral" class="x-hide-display">
        <table style="width:100%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista">
                <thead>
                 
                </tbody>
        </table>
        </div>
        </form>        
    </body>
</html>    