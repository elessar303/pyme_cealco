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
				text:'Procesar Pendientes',
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


function seleccionar(){
	var i=document.getElementById("cantidad_item").value;
	var buscar=document.getElementById("buscar_factura").value;
	var si=0;
	for(k=0;k<=i;k++){
		var id=document.getElementById("numero"+k).value;
		if(id==buscar)
		{
			check=document.getElementById("optAgregar"+k);
			if(check.checked!=true)
			{
				check.checked=true;
				montoCheck(k)
				si=1;
			}
			else
				si=2;
		}
	}
	if(si==0){
		Ext.Msg.alert("Alerta","NO SE CONSIGUIO LA FACTURA.");
		$("input[id='informacion']").val("NO SE CONSIGUIO LA FACTURA");
	}else if (si==1){
		$("input[id='informacion']").val("SE SELECCIONO LA FACTURA");
	}else if (si==2){
		$("input[id='informacion']").val("FACTURA YA SELECCIONADA");
	}
	$("input[id='buscar_factura']").val("");
}

function GuardarPago()
{
	var montoEnviar=document.getElementById("enviar_total").value;
	if((montoEnviar=="0.00")||(montoEnviar=="0"))
	{
		Ext.Msg.alert("Alerta!","Debe seleccionar alguna Factura");
		return false;
	}
	$("#formulario").submit();
}

function montoCheck(i)
{
	var checkAg=document.getElementById("optAgregar"+i);
	var monto=document.getElementById("monto"+i).value;
	var montoPendiente=document.getElementById("pendiente_p").value;
	var montoEnviar=document.getElementById("enviar_total").value;
	var montoTotal=document.getElementById("pendiente_total").value;
	var cantidad=document.getElementById("cantidad").value;
	var xx=0;
	if(checkAg.checked==true)
	{
		$("input[name='enviar_total']").val(montoEnviar=parseFloat(montoEnviar)+parseFloat(monto));
		$("input[name='pendiente_total']").val(montoTotal=parseFloat(montoPendiente)-parseFloat(montoEnviar));
		xx=parseFloat(cantidad)+1;
	}
	else
	{
		$("input[name='enviar_total']").val(montoEnviar=parseFloat(montoEnviar)-parseFloat(monto));
		$("input[name='pendiente_total']").val(montoTotal=parseFloat(montoPendiente)+parseFloat(montoEnviar));
		xx=parseFloat(cantidad)-1;
	}
	$("input[name='cantidad']").val(xx);
	$(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Facturas: "+(xx)+"</span>");
}

function retenciones()
{
	$("input[name='montoExento']").val(0);
	$("input[name='totalIva']").val(0);
	var facs=document.getElementById("facturas").value;
	var ivaRet=baseIva=iva=exento=i=0;
	var cad='';
	while(i<facs)
	{
		var checkAg=document.getElementById("optAgregar"+i);
		if(checkAg.checked==true)
		{
			var cod=document.getElementById("codigo"+i).value;
			if(cad=='')
				cad=cod;
			else
				cad=cad+','+cod;

			var montoIva=document.getElementById("montoIva"+i).value;
			var montoIvaRet=document.getElementById("montoIvaRet"+i).value;
			var montoBaseIva=document.getElementById("montoBaseIva"+i).value;
			var montoExento=document.getElementById("montoExento"+i).value;
			iva=parseFloat(iva)+parseFloat(montoIva);
			ivaRet=parseFloat(ivaRet)+parseFloat(montoIvaRet);
			baseIva=parseFloat(baseIva)+parseFloat(montoBaseIva);
			exento=parseFloat(exento)+parseFloat(montoExento);
		}
		i++
	}
	$("input[name='montoBaseIva']").val(baseIva);
	$("input[name='montoExento']").val(exento);
	$("input[name='totalIva']").val(iva);
	$("#ret").find("tr").remove();
	$("input[name='totalRet']").val(0);
	$("input[name='totalRetIva']").val(ivaRet);
	if(cad!='')
	{
		
		$.ajax({
			type: 'GET',
			data: 'opt=retencionesFactura&facs='+cad,
			url:  '../../libs/php/ajax/ajax.php',
			beforeSend: function(){},
			success: function(data)
			{
				cadena = data.split("*l*l*l*");
				$("#ret").html(cadena[0]);
				var i=0;
				var mto=0;
				var k=cadena[1];
				while (i<k)
				{
					var montoR=document.getElementById("montoRet"+i).value;
					mto=parseFloat(mto)+parseFloat(montoR);	
					i=i+1;
				}
				$("input[name='totalRet']").val(mto);
			}
		});
	}
}

function montoCanMenosPend()
{
	var montoPago=document.getElementById("montoPago").value;
	var montoPendiente=document.getElementById("montoPendiente").value;
	$("input[name='montoPendiente']").val(montoPendiente=parseFloat(montoPendiente)-parseFloat(montoPago));
}
