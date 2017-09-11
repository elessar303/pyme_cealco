<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        {literal}
            <script type="text/javascript">
                $(document).ready(function(){
                    //window.location=window.confirm("Generar Corte X")?"corte_x.php?generar=si":"?opt_menu=106";
                    if(confirm("¿Esta Seguro de generar el comprobante?"))
                    {
                        parametros=
                        {
                            "opt": "generarComprobanteMaestro"
                        };

                        $.ajax(
                        {
                            type: "POST",
                            url: "../../libs/php/ajax/ajax.php",
                            data: parametros,
                            dataType: "html",
                            asynchronous: false,
                            error: function()
                            {
                              alert("error petición ajax");
                            },
                            success: function(data)
                            {
                                if(data==-1)
                                {
                                    alert("Error, Existen Cajeros Pendientes");
                                    window.location="?opt_menu=89";
                                    return false;
                                }
                                if(data==-2)
                                {
                                    alert("Error, Ya se realizó el comprobante del día, debe esperar a una proxima apertura para generarlo de nuevo");
                                    window.location="?opt_menu=89";
                                    return false;
                                }
                                if(data==1)
                                {
                                    alert("Comprobante Realizado");
                                    window.open('../../reportes/comprobante_contable.php');
                                    window.location="?opt_menu=89";
                                }
                                else
                                {
                                    alert("Error, por favor consulte al administrador");
                                }

                            }
                        });
                    }
                    else
                    {
                       window.location="?opt_menu=89";
                    }
                });
            </script>
        {/literal}
    </head>
    <body>
    </body>
</html>