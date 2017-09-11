Ext.onReady(function(){
	var tab = new Ext.TabPanel({
		frame:true,
		contentEl:'PanelPagoFacturas',
		activeTab:0,
		height:450,
		items:[
		{
			title:'Pago / Abono',
			contentEl:'tabpago',
			autoScroll:true,
			tbar: [
			{
				text:'Procesar Pago',
				icon: '../../libs/imagenes/cancelar.png',
				handler: function(){
					GuardarPago();
				}
			}]
		},
		{
			title:'Facturas',
			contentEl:'tabfacturas',
			autoScroll:true,
		}
	]
	});
	tab.render("formulario");
});

//ACTUALIZA EL MONTO  DE PAGO CON RESPECTO A LAS NOTAS DE CREDITO
function montoCanMenosNc()
{
	var montoPago=$("input[name='montoPago']").val();
// 	var total=$("input[name='totalizar_total_general']").val();
// 	var montoRet=$("input[name='totalRet']").val();
	var notaCred=$("input[name='totalNotaCred']").val();
// 	alert(total)
// 	alert(montoRet)
// 	alert(montoPago)
		
	//$("input[name='montoPendiente']").val(((parseFloat(total)+parseFloat(notaCred))-(parseFloat(montoPago)+parseFloat(montoRet)+parseFloat(notaCred))));
//	$("input[name='montoPendiente']").val(((parseFloat(total))-(parseFloat(montoPago)+parseFloat(montoRet))));
	//alert(((parseFloat(total))-(parseFloat(montoPago)+parseFloat(montoRet))))
// 	alert(((parseFloat(total))-(parseFloat(montoPago)+parseFloat(montoRet))))
	$("#montoPago").val((parseFloat(montoPago)-parseFloat(notaCred)));
// 	alert((parseFloat(montoPago)-parseFloat(notaCred)))
}

//ACTUALIZA EL MONTO PENDIENTE Y DE PAGO 
function montoCanMenosPend()
{
	var montoPago=$("input[name='montoPago']").val();
	var total=$("input[name='totalizar_total_general']").val();
	var montoRet=$("input[name='totalRet']").val();
	var notaCred=$("input[name='totalNotaCred']").val();
	var k=(parseFloat(montoPago)+parseFloat(montoRet)).toFixed(2)
	//alert(parseFloat(total).toFixed(2))
	//alert(k)
	$("input[name='montoPendiente']").val(parseFloat(total)-parseFloat(k));
	montoCanMenosNc();
}

function GuardarPago()
{
	
	respon1 = $("#descripcion").val();
	respon2 = $("#fecha").val();
	respon3 = $("#montoPago").val();
	if((respon1=="")||(respon2=="")||(respon3=="")||(respon3==0))
	{
		Ext.Msg.alert("Alerta!","Debe completar los campos obligatorios");
		return false;
	}
	$("#formulario").submit();
}

//PRECALCULA EL MONTO A PAGAR, PENDIENTE, Y EL TOTAL DE IVA RETENIDO
function montoCheck(i)
{
	//CANTIDAD DE DOCUMENTOS (FACTURAS, NOTA DE CREDITO O DEBITO) MOSTRADAS
	var facs=document.getElementById("facturas").value;

	//TOTAL PENDIENTE POR PAGAR
	var total=$("input[name='totalizar_total_general']").val();
	
	
	var notaCred=i=xx=pagar=0;

	//CADENA QUE CONTIENE EL ID DEl DOCUMENTO SELECCIONADAS
	var cad='';

	//INICIALIZA EL MONTO PENDIENTE CON EL TOTAL PENDIENTE
	$("input[name='montoPendiente']").val(total);

	//INICIALIZA LA CANTIDAD DEl DOCUMENTO SELECCIONADAS EN CERO
	$("input[name='cantidad']").val(xx);
	$(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Facturas: "+(xx)+"</span>");

	//RECORRO LOS DOCUMENTO MOSTRADAS
	while(i<facs)
	{
		//TRAIGO EL CHECK DEl DOCUMENTO i 
		var checkAg=document.getElementById("optAgregar"+i);
		
		//VERIFICO SI EL DOCUMENTO FUE SELECCIONADA
		if(checkAg.checked==true)
		{
			//TRAIGO EL MONTO DEL DOCUMENTO SELECCIONADA
			var monto=document.getElementById("monto"+i).value;
			
			//TRAIGO EL MONTO DEL IVA RETENIDO DEL DOCUMENTO SELECCIONADA
			var iva=document.getElementById("montoIvaRet"+i).value;

			//TRAIGO EL TIPO DE DOCUMENTO
			var tipoDoc=document.getElementById("tipoDoc"+i).value;

			//VERIFICO SI ES UNA NOTA DE CREDITO
			if(tipoDoc=='NC')
			{
				//ACUMULO EL TOTAL DE MONTO NOTAS DE CREDITOS
				notaCred=parseFloat(notaCred)+parseFloat(monto);
				//MULTIPLICO EL MONTO DE LA NC POR -1  YA QUE LAS NC DISMINUYEN EL PAGO
				monto=0;
			}
			//ACUMULO EL MONTO A PAGAR CON LOS DOCUMENTOS SELECCIONADOS
			pagar=parseFloat(monto)+parseFloat(pagar);
			//DISMUNUYO EL TOTAL QUE HACE REFERENCIA AL MONTO PENDIENTE
			total=parseFloat(total)-parseFloat(monto);
			//ACUMULO EL TOTAL DE DOCUMENTOS SELECCIONADOS
			xx=parseFloat(xx)+1;
			
		}
		i++
	}
	//ESCRIBO EL TOTAL DEL MONTO DE NOTAS DE CREDITO EN EL CAMPO SIGUIENTE
	$("input[name='totalNotaCred']").val(notaCred);
	//ESCRIBO EL TOTAL DEL MONTO A PAGAR EN EL CAMPO SIGUIENTE
	$("input[name='montoPago']").val(pagar);
	//ESCRIBO EL TOTAL DEL MONTO PENDIENTE EN EL CAMPO SIGUIENTE
	$("input[name='montoPendiente']").val(total);
	
	//ESCRIBO EL TOTAL DE DOCUMENTOS SELECCIONADOS EN EL CAMPO SIGUIENTE
	$("input[name='cantidad']").val(xx);

	//MUESTRO LA CANTIDAD DE DOCUMENTOS SELECCIONADAS
	$(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Facturas: "+(xx)+"</span>");
	retenciones();
}

function retenciones()
{
	//INICIALIZO LAS VARIABLES EL CERO
	$("input[name='montoExento']").val(0);
	$("input[name='totalIva']").val(0);

	//OBTENGO EL TOTAL DE DOCUMENTOS MOSTRADOS
	var facs=document.getElementById("facturas").value;
	
	//INICIALIZO LAS VARIABLES EN CERO
	var ivaRet=baseIva=iva=exento=i=0;

	//CADENA QUE CONTIENE EL ID DEl DOCUMENTO SELECCIONADAS
	var cad='';

	//RECORRO EL TOTAL DE DOCUMENTOS
	while(i<facs)
	{
		//TRAIGO EL CHECK DEl DOCUMENTO i 
		var checkAg=document.getElementById("optAgregar"+i);

		//VERIFICO SI EL DOCUMENTO FUE SELECCIONADA
		if(checkAg.checked==true)
		{
			//TRAIGO EL ID DEL DOCUMENTO
			var cod=document.getElementById("codigo"+i).value;

			//CREO Y CONCATENO LOS ID DE LOS DOCUMENTOS SELECCIONADOS
			if(cad=='')
				cad=cod;
			else
				cad=cad+','+cod;

			//TRAIGO EL MONTO DEL IVA
			var montoIva=document.getElementById("montoIva"+i).value;

			//TRAIGO EL MONTO DEL IVA RETENIDO
			var montoIvaRet=document.getElementById("montoIvaRet"+i).value;

			//TRAIGO EL MONTO BASE PARA EL CALCULO DEL IVA
			var montoBaseIva=document.getElementById("montoBaseIva"+i).value;

			//TRAIGO EL MONTO EXENTO
			var montoExento=document.getElementById("montoExento"+i).value;

			//TRAIGO EL TIPO DE DOCUMENTO
			var tipoDoc=document.getElementById("tipoDoc"+i).value;

			//VERIFICO SI ES UNA NOTA DE CREDITO
			if(tipoDoc=='NC')
			{
				//LLEVO A CERO EL MONTO BASE DEL IVA
				montoBaseIva=0;
			}

			//ACUMULO EL MONTO DE IVA
			iva=parseFloat(iva)+parseFloat(montoIva);

			//ACUMULO EL MONTO IVA RETENIDO
			ivaRet=parseFloat(ivaRet)+parseFloat(montoIvaRet);

			//ACUMULO EL MONTO BASE DEL IVA
			baseIva=parseFloat(baseIva)+parseFloat(montoBaseIva);

			//ACUMULO EL MONTO EXENTO
			exento=parseFloat(exento)+parseFloat(montoExento);
		}
		i++;
	}

	//ESCRIBO EL TOTAL DEL MONTO BASE 
	$("input[name='montoBaseIva']").val(baseIva);

	//ESCRIBO EL TOTAL DEL MONTO EXENTO
	$("input[name='montoExento']").val(exento);

	//ESCRIBO EL TOTAL DEL MONTO DE IVA
	$("input[name='totalIva']").val(iva);

	//ELIMINO LAS RETENCIONES DE ISLR EN CASO DE QUE EXISTAN
	$("#ret").find("tr").remove();

	//INICIALIZAMOS EL MONTO TOTAL DE LAS RETENCIONES
	$("input[name='totalRet']").val(0);

	//TRAIGO EL MONTO A PAGAR
	var montoPago=$("input[name='montoPago']").val();

	//TRAIGO EL MONTO PENDIENTE POR PAGAR GENERAL
	var total=$("input[name='totalizar_total_general']").val();

	//TRAIGO EL MONTO TOTAL RETENIDO
	var montoRet=$("input[name='totalRet']").val();

	//CALCULO EL MONTO PENDIENTE
	$("input[name='montoPendiente']").val((parseFloat(total)-(parseFloat(montoPago)+parseFloat(montoRet))));
	//$("input[name='totalRetIva']").val(ivaRet);

	//VERIFICO QUE ESTE SELECCIONADA AL MENOS UNA FACTURA
	if(cad!='')
	{
		//ESCRIBO LA CADENA DE FACTURAS SELECCI0NADAS EN EL CAMPO SIGUIENTE
		$("input[name='idFacturas']").val(cad);

		//AJAX QUE TRAE LAS RETENCIONES DE IVA E ISLR AGRUPADAS POR TIPO DE RETENCION Y LA CANTIDAD DE FILAS DE RETENCIONES
		$.ajax({
			type: 'GET',
			data: 'opt=retencionesFactura&facs='+cad,
			url:  '../../libs/php/ajax/ajax.php',
			beforeSend: function(){},
			success: function(data)
			{
				//EL AJAX DEVUELVE EN DOS CAMPOS LAS FILAS DE RETENCIONES Y EN EL SEGUNDO LA CANTIDAD DE FILAS
				cadena = data.split("*l*l*l*");

				//INCLUYE LAS FILAS
				$("#ret").html(cadena[0]);
				var i=0;
				var mto=0;
				var k=cadena[1];
				while (i<k)
				{
					//VOY RECORRIENDO LAS FILAS MOSTRADAS Y ACUMULANDO EL MONTO RETENIDO
					var montoR=document.getElementById("montoRet"+i).value;
					mto=parseFloat(mto)+parseFloat(montoR);	
					i=parseFloat(i)+1;
				}
				//GUARDO LA CANTIDAD DE FILAS DE RETENCIONES EN EL CAMPO SIGUIENTE
				$("input[name='cantidadImp']").val(k);

				//GUARDO EL TOTAL DE LOS MONTOS DE LAS RETENCIONES EN EL CAMPO SIGUIENTE
				$("input[name='totalRet']").val(mto);
				
				//TRAIGO EL MONTO PAGO, EL TOTAL PENDIENTE, Y EL TOTAL DE MONTO RETENIDO CANCULADO ANTERIORMENTE
				var montoPago=$("input[name='montoPago']").val();
				var total=$("input[name='totalizar_total_general']").val();
				var montoRet=$("input[name='totalRet']").val();

				//AUXILIAR PARA EL CALCULO
				var aux=0;
				
				//CALCULO EL AUX (AUNQUE EL MONTO PAGO YA TIENE DESCONTADA LAS RETENCIONES)
				aux=parseFloat(montoPago)-parseFloat(montoRet);
				

				

				//VERIFICA LOS MONTOS DE PAGO Y PENDIENTE PARA GUARDARLOS EN SUS CAMPOS RESPECTIVOS
				if((aux+parseFloat(montoRet))==total)
				{
					$("input[name='montoPago']").val(aux);
					$("input[name='montoPendiente']").val((parseFloat(total)-(parseFloat(aux)+parseFloat(montoRet))));
				}
				else if((parseFloat(montoPago)+parseFloat(montoRet))==total)
				{
					$("input[name='montoPendiente']").val((parseFloat(total)-(parseFloat(montoPago)+parseFloat(montoRet))));
				}
				else
				{
					$("input[name='montoPendiente']").val((parseFloat(total)-(parseFloat(aux)+parseFloat(montoRet))));
				}
				
				montoCanMenosPend();
			}
		});
	}
}
