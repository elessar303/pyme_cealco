function totalRetIslr()
{
    var cant = document.getElementById("cantidad").value;
    var j=0;
    var total=0;
    while(j<=cant)
    {
        if(document.getElementById("montoRetenidoIslr"+j) != null)
        {
            var montoRet=document.getElementById("montoRetenidoIslr"+j).value;
            total=parseFloat(total)+parseFloat(montoRet);
        }
        j=parseFloat(j)+1
    }
    return total;
}

function calcular_iva_fact()
{
    var montoBase = document.getElementById("montoBase").value;
    var montoExento = document.getElementById("montoExento").value;
    var porcentaje = document.getElementById("porcentajeIva").value;
    var checkIva = document.getElementById("optIva");
    var ivas = document.getElementById("ivas");
    var impIva = (montoBase*porcentaje)/100;
    var montoConIva = parseFloat(montoBase)+parseFloat(montoExento)+parseFloat(impIva);
    var montoSinIva = parseFloat(montoBase)+parseFloat(montoExento);

    $("input[name='montoIva']").val(impIva);
    $("input[name='montoConIva']").val(montoConIva);
    $("input[name='montoSinIva']").val(montoSinIva);

    if(checkIva.checked==true){
        $.ajax({
            type: 'GET',
            data: 'opt=cxpIvaFactura&codIva='+ivas.value+"&montoBase="+parseFloat(impIva),
            url:  '../../libs/php/ajax/ajax.php',
            beforeSend: function(){
            },
            success: function(data){
                cadena = data.split("-");
                $("input[name='porcentajeRetIva']").val(cadena[0])
                $("input[name='montoRetenido']").val(cadena[1])

                var montoRetIva2=document.getElementById("montoRetenido").value;
                var montoConIva2=document.getElementById("montoConIva").value;
                var anticipo=document.getElementById("anticipo").value;
                $("input[name='totalPagar']").val((parseFloat(montoConIva2)-parseFloat(anticipo)-parseFloat(montoRetIva2)-parseFloat(totalRetIslr())).toFixed(2))
            }
        });
    }
    else
    {
        $("input[name='porcentajeRetIva']").val(0)
        $("input[name='montoRetenido']").val(0)

        var montoRetIva2=document.getElementById("montoRetenido").value;
        var montoConIva2=document.getElementById("montoConIva").value;
        var anticipo=document.getElementById("anticipo").value;
        $("input[name='totalPagar']").val((parseFloat(montoConIva2)-parseFloat(anticipo)-parseFloat(montoRetIva2)-parseFloat(totalRetIslr())).toFixed(2))
    }

}

function calcular_retiva_fact()
{
    var montoBase = document.getElementById("montoIva");
    var impIva = document.getElementById("ivas");
    var checkIva = document.getElementById("optIva");
   
    if(checkIva.checked==true){
        $.ajax({
            type: 'GET',
            data: 'opt=cxpIvaFactura&codIva='+impIva.value+"&montoBase="+parseFloat(montoBase.value),
            url:  '../../libs/php/ajax/ajax.php',
            beforeSend: function(){
            },
            success: function(data){
                cadena = data.split("-");
                $("input[name='porcentajeRetIva']").val(cadena[0])
                $("input[name='montoRetenido']").val(cadena[1])
                var montoRetIva2=document.getElementById("montoRetenido").value;
                var montoConIva2=document.getElementById("montoConIva").value;
                var anticipo=document.getElementById("anticipo").value;
                $("input[name='totalPagar']").val((parseFloat(montoConIva2)-parseFloat(anticipo)-parseFloat(montoRetIva2)-parseFloat(totalRetIslr())).toFixed(2))
            }
        });
    }
    else
    {
        $("input[name='porcentajeRetIva']").val(0)
        $("input[name='montoRetenido']").val(0)
        var montoRetIva2=document.getElementById("montoRetenido").value;
        var montoConIva2=document.getElementById("montoConIva").value;
        var anticipo=document.getElementById("anticipo").value;
        $("input[name='totalPagar']").val((parseFloat(montoConIva2)-parseFloat(anticipo)-parseFloat(montoRetIva2)-parseFloat(totalRetIslr())).toFixed(2))
    }
}

function calcular_retislr_fact(valor)
{
    var servicio=document.getElementById("codServ"+valor).value;
    var entidad=document.getElementById("codEntidad"+valor).value;
    var montoBase=document.getElementById("montoServ"+valor).value;
    var montoRet=document.getElementById("montoRetenidoIslr"+valor);
    $.ajax({
        type: 'GET',
        data: 'opt=cxpRetIslrFactura&servicio='+servicio+"&montoBase="+parseFloat(montoBase)+"&entidad="+entidad,
        url:  '../../libs/php/ajax/ajax.php',
        beforeSend: function(){
        },
        success: function(data){
            montoRet.value=data;
            var montoRetIva2=document.getElementById("montoRetenido").value;
            var montoConIva2=document.getElementById("montoConIva").value;
            var anticipo=document.getElementById("anticipo").value;
            $("input[name='totalPagar']").val((parseFloat(montoConIva2)-parseFloat(anticipo)-parseFloat(montoRetIva2)-parseFloat(totalRetIslr())).toFixed(2))
        }
    });
}

function habilitarFacturaAfectada()
{
    var checkNc=document.getElementById("optNc").value;
    var facAfec=document.getElementById("facturaAfectada");
    if(checkNc!='FAC')
    {
        facAfec.disabled=false
    }
    else
    {
        facAfec.disabled=true
        facAfec.value=''
    }
}


function cargarAnticipo()
{
    var edoCta=$("#edoCta").val();
    $.ajax({
        type: 'GET',
        data: 'opt=anticipos&edoCta='+edoCta,
        url:  '../../libs/php/ajax/ajax.php',
        beforeSend: function(){
        // 			$("#items").find("option").remove();
        // 			$("input[name='anticipo']").val("Cargando..");
        },
        success: function(data){
            cadena = data.split("*l*l*l*");
            $("#anticipos").html(cadena[0]);
            $("input[name='cantAnt']").val(cadena[1]);
        }
    });
}

winAnt = new Ext.Window(
{
    title:'Cargar Anticipos',
    height:360,
    width:459,
    autoScroll:true,
    modal:true,
    bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
    closeAction:'hide',
    contentEl:'anticipox',
    buttons:[
    {
        text:'Cerrar',
        icon: '../../libs/imagenes/cancel.gif',
        handler:function()
        {
            win.hide();
            calcular_iva_fact()
        }
    }
    ]
});

function totalAnticipos()
{
    var anticipos = document.getElementById("cantAnt").value;
    var i=0;
    var monto=0;
    $("input[name='totalAtn']").val(monto.toFixed(2));
    $("input[name='anticipo']").val(monto.toFixed(2));
    while(i<anticipos)
    {
        var anticiposCheck = document.getElementById("optAnticipo"+i);
        if(anticiposCheck.checked==true)
        {
            var montoAnt = document.getElementById("monto"+i).value;
            monto=parseFloat(monto)+parseFloat(montoAnt);
            $("input[name='totalAtn']").val(monto.toFixed(2));
            $("input[name='anticipo']").val(monto.toFixed(2));
        }
        i++;
    }
}
