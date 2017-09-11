Ext.onReady(function(){
	var tab = new Ext.TabPanel({
		frame:true,
		contentEl:'PanelPagoFacturas',
		activeTab:0,
		height:425,
		items:[
		{
			title:'Facturas',
			contentEl:'tabfacturas',
			autoScroll:true,
			
		},
		{
			title:'Pago',
			contentEl:'tabpago',
			autoScroll:true,
			tbar: [
			{
				text:'Procesar Pago',
				icon: '../../libs/imagenes/cancelar.png',
				handler: function(){
					GuardarPago();
				}
			},
			{
				text:'Buscar Pago',
				icon: '../../libs/imagenes/65.gif',
				handler: function(){
					cargarMovimiento(); 
					win.show();
				}
			}
			]
		}
		
	]
	});
	tab.render("formularioxx");
});
function cargarMovimiento()
{
	var cliente=$("#id_cliente").val();
	$.ajax({
		type: 'GET',
		data: 'opt=movimiento&cliente='+cliente,
		url:  '../../libs/php/ajax/ajax.php',
		beforeSend: function(){
// 			$("#items").find("option").remove();
// 			$("input[name='anticipo']").val("Cargando..");
		},
		success: function(data){
			cadena = data.split("*l*l*l*");
			$("#movimiento").html(cadena[0]);
			$("#cantMov").val(cadena[1]);
		}
	});
}

win = new Ext.Window(
{
	title:'Cargar Movimiento Bancario',
	height:360,
	width:550,
	autoScroll:true,
	modal:true,
	bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
	closeAction:'hide',
	contentEl:'movimientox',
	buttons:[
		{
			text:'Cerrar',
			icon: '../../libs/imagenes/cancel.gif',
			handler:function()	
			{
				win.hide();
				//calcular_iva_fact()
			}
		},
	]
});

function GuardarPago()
{
	
	respon1 = $("#descripcion").val();
	respon2 = $("#fecha").val();
	respon3 = $("#montoPago").val();
	respon4 = $("#montoTotal").val();
	if($("#montoTotal").val()==0)
	{
		Ext.Msg.alert("Alerta!","Debe seleccionar almenos una factura");
		return false;
	}
	if(parseFloat($("#pagoRecibido").val())<parseFloat($("#montoTotal").val()))
	{
		Ext.Msg.alert("Alerta!","El monto total de las facturas debe ser menor o igual al Pago recibido");
		return false;
	}
	if((respon1=="")||(respon2=="")||(respon3=="")||(respon3==0)||(respon4==0))
	{
		Ext.Msg.alert("Alerta!","Debe completar los campos obligatorios");
		return false;
	}
	
	$("#formularioxx").submit();
}

function montoCheck(i)
{
	var checkAg=document.getElementById("optAgregar"+i);
	var monto=document.getElementById("montob"+i).value;
	var montopag=document.getElementById("montopag"+i).value;
	var montoBase=document.getElementById("montoBase").value;
	var montoPendiente=document.getElementById("pendiente_p").value;
	var montoEnviar=document.getElementById("enviar_total").value;
	var montoTotal=document.getElementById("pendiente_total").value;
	var cantidad=document.getElementById("cantidad").value;
	var totaliva=document.getElementById("totaliva").value;
	var cantidadItem=$("#cantidad_item").val();
	$("#saldo"+i).val(parseFloat($("#saldo"+i).val())-parseFloat(montopag));
	var xx=0;
	
	if(checkAg.checked==true)
	{
// 		$("input[name='enviar_total']").val(montoEnviar=parseFloat(montoEnviar)+parseFloat(monto)+parseFloat(montoiva));
		$("input[name='enviar_total']").val(montoEnviar=parseFloat(montoEnviar)+parseFloat(montopag));
		$("input[name='pendiente_total']").val(montoTotal=parseFloat(montoPendiente)-parseFloat(montoEnviar));
		$("input[name='montoBase']").val(parseFloat(montoBase)+parseFloat(montopag));
// 		$("input[name='totaliva']").val(parseFloat(totaliva)+parseFloat(montoiva));
		xx=parseFloat(cantidad)+1;
	}
	else
	{
// 		$("input[name='enviar_total']").val(montoEnviar=parseFloat(montoEnviar)-parseFloat(monto)-parseFloat(montoiva));
		$("input[name='enviar_total']").val(montoEnviar=parseFloat(montoEnviar)-parseFloat(montopag));
		$("input[name='pendiente_total']").val(montoTotal=parseFloat(montoTotal)+parseFloat(montopag));
		$("input[name='montoBase']").val(parseFloat(montoBase)-parseFloat(montopag));
		$("#saldo"+i).val(parseFloat($("#saldo"+i).val())+parseFloat(montopag));
// 		$("input[name='totaliva']").val(parseFloat(totaliva)-parseFloat(montoiva));
		xx=parseFloat(cantidad)-1;
	}
	$("input[name='montoTotal']").val(parseFloat($("input[name='montoBase']").val())-parseFloat($("input[name='totalRetislr']").val()));
	$("input[name='cantidad']").val(xx);
	$(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Facturas: "+(xx)+"</span>");
}

function retencion_iva(){
	var i=document.getElementById("cantidad_item").value;
	var check;
	var monto;
	var arrayiva;
	var valor_final=0;
	for(k=0;k<=i;k++){
		check=document.getElementById("optAgregar"+k);
		if(check.checked==true){
			var retiva=document.getElementById("riva"+k).value;
			monto=document.getElementById("montoiva"+k).value;
			arrayiva=retiva.split('-');
			var valor=(parseFloat(monto)*arrayiva[1])/100;
			valor_final=valor+valor_final;
		}
	}
	$("input[id='totalRetiva']").val(valor_final);
	
	monto_total();
}
function retencion_islr(){
	var i=document.getElementById("cantidad_item").value;
	var check;
	var monto;
	var arrayiva;
	var valor_final=0;
	for(k=0;k<=i;k++){
		check=document.getElementById("optAgregar"+k);
		if(check.checked==true){
			var retislr=document.getElementById("islr"+k).value;
			monto=document.getElementById("montob"+k).value;
			arrayislr=retislr.split('-');
			var valor=(parseFloat(monto)*arrayislr[1])/100;
			valor_final=valor+valor_final;
			$("#im"+k).val(valor.toFixed(2));
		}
	}
	$("input[id='totalRetislr']").val(valor_final.toFixed(2));
	monto_total();
}
function actualizar_impuesto(){
	var i=document.getElementById("cantidad_item").value;
	var check;
	var monto;
	var arrayiva;
	var valor_final=0;
	for(k=0;k<=i;k++){
		check=document.getElementById("optAgregar"+k);
		if(check.checked==true){
			var im=document.getElementById("im"+k).value;
			valor_final=parseFloat(im)+valor_final;
			
		}
	}
	$("input[id='totalim']").val(valor_final.toFixed(2));
	monto_total();
}
function actualizar_abono(){

	var i=document.getElementById("cantidad_item").value;
	var check;
	var monto;
	var arrayiva;
	var valor_final=0;
	for(k=0;k<=i;k++){
		check=document.getElementById("optAgregar"+k);
		if(check.checked==true){
			var im=document.getElementById("montoabono"+k).value;
			valor_final=parseFloat(im)+valor_final;
		}
	}
	$("input[id='totalabono']").val(valor_final);
	monto_total();
}

function monto_total(){
	var montoBase=document.getElementById("montoBase").value;
	var totaliva=document.getElementById("totaliva").value;
	var totalabono=document.getElementById("totalabono").value;
	var montoiva=document.getElementById("totalRetiva").value;
	var montoisl=document.getElementById("totalRetislr").value;
	var montoim=document.getElementById("totalim").value;
	var valor_final=parseFloat(montoBase)+parseFloat(totaliva)-parseFloat(montoiva)-parseFloat(montoisl)-parseFloat(montoim);
	$("input[id='montoTotal']").val(valor_final);
}

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
		$("input[id='informacion']").val("NO SE CONSIGUIO LA FACTURA");
	}else if (si==1){
		$("input[id='informacion']").val("SE SELECCIONO LA FACTURA");
	}else if (si==2){
		$("input[id='informacion']").val("FACTURA YA SELECCIONADA");
	}
	$("input[id='buscar_factura']").val("");
}

function totalPagos()
{
	var mov = document.getElementById("cantMov").value;
	var i=0;
	var monto=0;
	$("input[name='totalPags']").val(monto.toFixed(2));
	$("input[name='pagoRecibido']").val(monto.toFixed(2));
	while(i<mov)
	{
		var movCheck = document.getElementById("optMov"+i);
		if(movCheck.checked==true)
		{
			var montoAnt = document.getElementById("montosss"+i).value;
			monto=parseFloat(monto)+parseFloat(montoAnt);
			$("input[name='totalPags']").val(monto.toFixed(2));
			$("input[name='pagoRecibido']").val(monto.toFixed(2));
			$("input[name='codigomov']").val($("#codmov"+i).val());
			$("input[name='trans']").val($("#numerom"+i).val());
			$("input[name='fechad']").val($("#fechamov"+i).val());
		}
		i++;
	}
}