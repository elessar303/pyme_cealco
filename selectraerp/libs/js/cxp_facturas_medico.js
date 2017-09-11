Ext.onReady(function(){
	var tab = new Ext.TabPanel({
		frame:true,
		contentEl:'PanelPagoFacturas',
		activeTab:0,
		height:450,
		items:[
		{
			title:'Facturas',
			contentEl:'tabfacturas',
			autoScroll:true,
			tbar: [
			{
				text:'Procesar Facturas',
				icon: '../../libs/imagenes/cancelar.png',
				handler: function(){
					GuardarPago();
				}
			}]
		}
	]
	});
	tab.render("formulario");
});

function GuardarPago()
{
	respon1 = $("#idFacturas").val();
	respon = $("#montoPago").val();
	alert(respon)
	alert(respon1)

	if(respon1=="")
	{
		Ext.Msg.alert("Alerta!","Debe seleccionar almenos una factura!!!");
		return false;
	}
	document.getElementById("formulario").submit();
}

function montoCheck()
{
	var facs=$("input[name='facturas']").val();
	var total=i=xx=0;
	var cad='';
	$(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Facturas: "+(xx)+"</span>");
	$(".pend").html("<b>0.00</b>");
	$("input[name='cantidad']").val(0);
	while(i<facs)
	{
		var checkAg=document.getElementById("optAgregar"+i);
		if(checkAg.checked==true)
		{
			var monto=document.getElementById("monto"+i).value;
			var fac=document.getElementById("codigo"+i).value;
			if(cad=='')
				cad=fac
			else
				cad=cad+','+fac
			total=parseFloat(total)+parseFloat(monto);
			xx=parseFloat(xx)+1;
		}
		i++;
	}
	$("input[name='montoPago']").val(total);
	$("input[name='idFacturas']").val(cad);
	$("input[name='cantidad']").val(xx);
	$(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Facturas: "+(xx)+"</span>");
	$(".pend").html("<b>"+parseFloat((total.toFixed(2)))+"</b>");
}