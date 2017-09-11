/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function abrirAjax()
{

	var xmlhttp=false;
	try
	{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp;
}

function calcular_impuestos(cod_tipo_impuesto){
        
 	var cod_tipo_impuesto1=document.getElementById('cod_tipo_impuesto1')
        var cod_tipo_impuesto2=document.getElementById('cod_tipo_impuesto2')
        //var idcod_impuesto="cod_impuesto"+cod_tipo_impuesto.value
        var cod_impuesto=document.getElementById('cod_impuesto')

//alert (cod_impuesto.value)
alert (cod_tipo_impuesto.value)
alert (cod_tipo_impuesto1.value)
alert (cod_tipo_impuesto2.value)
/*
        var texto=linea.options[linea.selectedIndex].text

	var contenido_islr=abrirAjax()

	contenido_islr.open("GET", "calculo_islr.php?codigo="+linea.value+"&proveedor="+proveedor.value+"&monto_pago="+monto_pago.value+"&monto_base="+monto_base.value, true)
   	contenido_islr.onreadystatechange=function()
	{
		if (contenido_islr.readyState==1)
		{
			islr_texto.value="Calculando...";

		}
		if (contenido_islr.readyState==4)
		{
			var cadena=contenido_islr.responseText;
			var separada= cadena.split(',')
			islr_texto.value = separada[0]+" "+texto
			porce_reten.value= separada[1]
			//var sust =separada[2]
			if(separada[0]=="No se puede calcular")
			{
				monto_islr.value = 0
			}else
			{
				monto_islr.value = separada[0]

			}
			total_impuestos()
			total_final()
		}
	}

	contenido_islr.send(null);
*/
}
