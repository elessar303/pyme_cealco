
function guardar()
{
    if($("#claveOLD").val()=="" || $("#clave1").val()==""||$("#clave2").val()==""){
        Ext.Msg.alert("Alerta","Debe llenar todos los campos.");
        return false;
    }else{
        if ($("#clave1").val() != '' || $("#clave2").val() != '') {
            if ($("#clave1").val() != $("#clave2").val()) {
                Ext.Msg.alert("Alerta","Las claves no coinciden.");
                $("#clave1, #clave2").val("");
                $("#clave1").focus();
                return false;
            }
            else {
                claveMD5 = hex_md5($("#clave1").val());
                $("#clave1, #clave2").val(claveMD5);
                claveMD5 = hex_md5($("#claveOLD").val());
                $("#claveOLD").val(claveMD5);
            }
        }
    }
    //var claveOLD=$("#claveOLD").val();
    var claveOLD=$("#claveOLD").val();
    var clave1=$("#clave1").val();
    
    $.ajax({
        type: 'GET',
        data: 'opt=cambiarClave&clave1='+clave1+'&claveOLD='+claveOLD,
        url:  '../../libs/php/ajax/ajax.php',
        beforeSend: function(){
        // $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Datos...<b>"));
        },
        success: function(data){
            resultado = data
            //alert(resultado);
            if(resultado == "1"){
                //       $("#claveOLD").val("").focus();
                $("#claveOLD").val("").focus();
                Ext.Msg.alert("Alerta","La clave anterior no es correcta. Intente de Nuevo.");
            }
            else  {
                Ext.Msg.alert("Alerta","Debe cerrar y iniciar session para que los cambios sean efectuados.");
                winClave.hide();
            }
        }
    });
}

winClave = new Ext.Window(
{
    title:'Cambiar Clave',
    height:195,
    width:420,
    autoScroll:true,
    modal:true,
    bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
    closeAction:'hide',
    contentEl:'cambiarclave',
    buttons:[
    {
        text:'Cambiar Clave',
        icon: '../../libs/imagenes/save.gif',
        handler:function()
        {
            guardar()
        }
    },
    {
        text:'Cerrar',
        icon: '../../libs/imagenes/cancel.gif',
        handler:function()
        {
            winClave.hide();
        }
    }
    ]
});
