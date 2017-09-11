function validar_montoacausar(monto_odp,monto_escrito){

if(monto_escrito>monto_odp){
alert("El monto ingresado no puede ser mayor al de la orden de pago, por favor revise los montos")
}

}


function paginacion(url,valor,campos){

parent.cont.location.href=url+".php?pagina="+valor+"&"+campos
}


function actualizar(seleccion){
	var opcion=document.getElementsByTagName("input");

	for (i=0; i<opcion.length; i++){
		if (opcion[i].type == "radio" && opcion[i].name != seleccion.name) {
			opcion[i].checked = false;
		}
	}	
}
function regresar(){
	parent.cont.location.href='configuracion.php'
}

function habilitar(valor){

	var opcion=document.getElementsByTagName("input");
	
	for (i=0; i<opcion.length; i++){
		if (opcion[i].type == "radio" && opcion[i].value == valor) {
			opcion[i].checked = true
			
		}
		else{
			opcion[i].disabled=true
		}
	}
}

function confirmar(msg,url) {
 self.rValue = false;
 if (confirm(msg) == true) {
  window.location.href = url;
 }
}

function over(element,estilo){
	element.addClassName(estilo);
}
function out(element,estilo){
	element.removeClassName(estilo);
}

function copytext(s) {
	window.clipboardData.setData("Text", s);
}

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

function imprimir(nombre){
	var ficha = document.getElementById(nombre)
	var ventimp = window.open(' ', 'popimpr','width=10, height=10, TOOLBAR=NO, LOCATION=NO, MENUBAR=NO, SCROLLBARS=NO, RESIZABLE=NO')
var estilo="<link href=\"../estilos.css\" rel=\"stylesheet\" type=\"text/css\">"
ventimp.document.write( estilo )
	ventimp.document.write( ficha.innerHTML )
	ventimp.document.close()
	ventimp.print()
	ventimp.close()
}
function cargar_municipio(){
 	
 	var estado=document.getElementById('cod_estado')
	var municipio=document.getElementById('cod_municipio')
	var contenido_municipio=abrirAjax()
		contenido_municipio.open("GET", "procesos.php?estado="+estado.value, true)
   contenido_municipio.onreadystatechange=function() 
	{
		if (contenido_municipio.readyState==1)
		{
			municipio.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Calculando..."
			municipio.appendChild(opcion)
			municipio.disabled=true
		}
		if (contenido_municipio.readyState==4)
		{
			municipio.parentNode.innerHTML = contenido_municipio.responseText;
		}
	}
	contenido_municipio.send(null);
}


function cargar_programa(){
	var sector=document.getElementById('sel_sector')
	var programa=document.getElementById('sel_programa')
	var contenido_programa=abrirAjax()
	contenido_programa.open("GET", "procesos2.php?accion=1&sector="+sector.value, true)
   contenido_programa.onreadystatechange=function() 
	{
		if (contenido_programa.readyState==1)
		{
			programa.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			programa.appendChild(opcion)
			programa.disabled=true
		}
		if (contenido_programa.readyState==4)
		{
			programa.parentNode.innerHTML = contenido_programa.responseText;
		} 
	}
	contenido_programa.send(null);
}
function cargar_actividad(){
 	var sector=document.getElementById('sel_sector')
	var programa=document.getElementById('sel_programa')
	var actividad=document.getElementById('sel_actividad')
	
	var contenido_actividad=abrirAjax()
	
	contenido_actividad.open("GET", "procesos.php?accion=2&sector="+sector.value+"&programa="+programa.value, true)
   	contenido_actividad.onreadystatechange=function() 
	{ 
		if (contenido_actividad.readyState==1)
		{
			actividad.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			actividad.appendChild(opcion)
			actividad.disabled=true
		}
		if (contenido_actividad.readyState==4)
		{
			actividad.parentNode.innerHTML = contenido_actividad.responseText;
		}
	}
	contenido_actividad.send(null);
}



function cargar_partida(){
 	var sector=document.getElementById('sel_sector')
	var programa=document.getElementById('sel_programa')
	var actividad=document.getElementById('sel_actividad')
	var partida=document.getElementById('cod')
	var contenido_partida=abrirAjax()
	
	contenido_partida.open("GET", "procesos.php?accion=3&sector="+sector.value+"&programa="+programa.value+"&actividad="+actividad.value, true)
   	contenido_partida.onreadystatechange=function() 
	{
		if (contenido_partida.readyState==4)
		{
			partida.parentNode.innerHTML = contenido_partida.responseText;
		}
	}
	contenido_partida.send(null);
}
function cargar_ordinal(){
 	var sector=document.getElementById('sel_sector')
	var programa=document.getElementById('sel_programa')
	var actividad=document.getElementById('sel_actividad')
	var partida=document.getElementById('cod')
	var ordinal=document.getElementById('sel_ordinal')
	var contenido_ordinal=abrirAjax()
	
	contenido_ordinal.open("GET", "ordinal_procesos.php?accion=1&partida="+partida.value, true)
   	contenido_ordinal.onreadystatechange=function() 
	{
		if (contenido_ordinal.readyState==4)
		{
			ordinal.parentNode.innerHTML = contenido_ordinal.responseText;
		}
	}
	contenido_ordinal.send(null);
}
function cargar_ucompra(){
	var unidad=document.getElementById('unidad')
	var codcen=document.getElementById('cod_centro')
	var contenido_codcen=abrirAjax()
	contenido_codcen.open("GET", "procesos2.php?accion=1&unidad="+unidad.value, true)
   	contenido_codcen.onreadystatechange=function() 
	{
		if (contenido_codcen.readyState==1)
		{
			codcen.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			codcen.appendChild(opcion)
			codcen.disabled=true
		}
		if (contenido_codcen.readyState==4)
		{
			codcen.parentNode.innerHTML = contenido_codcen.responseText;
		} 
	}
	contenido_codcen.send(null);
}

function cargar_actividad_2(){
 	var programa=document.getElementById('sel_programa')
	var actividad=document.getElementById('sel_actividad')
	
	var contenido_actividad=abrirAjax()
	
	contenido_actividad.open("GET", "procesos2.php?accion=1&programa="+programa.value, true)
   	contenido_actividad.onreadystatechange=function() 
	{ 
		if (contenido_actividad.readyState==1)
		{
			actividad.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			actividad.appendChild(opcion)
			actividad.disabled=true
		}
		if (contenido_actividad.readyState==4)
		{
			actividad.parentNode.innerHTML = contenido_actividad.responseText;
		}
	}
	contenido_actividad.send(null);
}



function cargar_partida_2(){
 	var programa=document.getElementById('sel_programa')
	var actividad=document.getElementById('sel_actividad')
	var partida=document.getElementById('cod')
	var contenido_partida=abrirAjax()
	
	contenido_partida.open("GET", "procesos2.php?accion=2&programa="+programa.value+"&actividad="+actividad.value, true)
   	contenido_partida.onreadystatechange=function() 
	{
		if (contenido_partida.readyState==4)
		{
			partida.parentNode.innerHTML = contenido_partida.responseText;
		}
	}
	contenido_partida.send(null);
}

function verificar_existencia(codmat,canti)
{
	//alert(canti)
 	
	var tipo=document.getElementById('tipo')
	//var cod=document.getElementById('cod_material[]')
	//alert("funciona"+tipo.value)
	if(tipo.value == 3)
	{
		var cant=document.getElementById(canti)
		var contenido_cant=abrirAjax()
		if(cant.value == "")
			return
		contenido_cant.open("GET", "verificar_existencia.php?cant="+cant.value+"&cod="+codmat, true)
   		contenido_cant.onreadystatechange=function() 
		{
			if (contenido_cant.readyState == 4)
			{
				f = contenido_cant.responseText;
				if(f == 1)
				{
					return
				}	
				else
				{	
					alert("NO TIENE El MATERIAL "+codmat+" EN EXISTENCIA")		
				}
				 
			}
		}
	}
	contenido_cant.send(null); 
	//f = contenido_cant.responseText	
	//alert(f)
}
function numeros(e){
		var key
		if (window.event){ key=e.KeyCode }
		else if(e.which){ key=e.which}
		if(key!=8 && key!=46){	
			if(key<48 || key >57){ return false}
		}
		
		return true
	}

function cargar_familias(){
	var segmentos=document.getElementById('segmentos')
	var familias=document.getElementById('familias')
	var contenido_familias=abrirAjax()
	contenido_familias.open("GET", "materiales_ajax.php?accion=1&segmentos="+segmentos.value, true)
   contenido_familias.onreadystatechange=function() 
	{
		if (contenido_familias.readyState==1)
		{
			familias.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			familias.appendChild(opcion)
			familias.disabled=true
		}
		if (contenido_familias.readyState==4)
		{
			familias.parentNode.innerHTML = contenido_familias.responseText;
		} 
	}
	contenido_familias.send(null);
}
function cargar_clases(){
	var segmentos=document.getElementById('segmentos')
	var familias=document.getElementById('familias')
	var clases=document.getElementById('clases')
	var contenido_clases=abrirAjax()
	contenido_clases.open("GET", "materiales_ajax.php?accion=2&segmentos="+segmentos.value+"&familias="+familias.value, true)
   contenido_clases.onreadystatechange=function() 
	{
		if (contenido_clases.readyState==1)
		{
			clases.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			clases.appendChild(opcion)
			clases.disabled=true
		}
		if (contenido_clases.readyState==4)
		{
			clases.parentNode.innerHTML = contenido_clases.responseText;
		} 
	}
	contenido_clases.send(null);
}
function cargar_productos(){
 	var segmentos=document.getElementById('segmentos')
	var familias=document.getElementById('familias')
	var clases=document.getElementById('clases')
	var productos=document.getElementById('productos')
	
	var contenido_productos=abrirAjax()
	
	contenido_productos.open("GET", "materiales_ajax.php?accion=3&segmentos="+segmentos.value+"&familias="+familias.value+"&clases="+clases.value, true)
   	contenido_productos.onreadystatechange=function() 
	{ 
		if (contenido_productos.readyState==1)
		{
			productos.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			productos.appendChild(opcion)
			productos.disabled=true
		}
		if (contenido_productos.readyState==4)
		{
			productos.parentNode.innerHTML = contenido_productos.responseText;
		}
	}
	contenido_productos.send(null);
}

function trimestre(archivo){
	var trimes=document.menu_int.trime.value
	window.location=archivo+'?trime='+trimes

}

function AbrirVentana(Ventana,Largo,Alto,Modal) 
{
	if (Modal==1)
	{
	mainWindow = showModalDialog(Ventana,'mainWindow','dialogWidth:'+Alto+'px;dialogHeight:'+Largo+'px;resizable:yes;toolbar:no;menubar:no;scrollbars:yes;help: no');
	}
	else
	{
	
	mainWindow = window.open(Ventana,'mainWindow','menub ar=no,resizable=no,width='+Alto+',height='+Largo+',left=400,top=200,titlebar=yes,alwaysraised=yes,status=no,scrollbars=yes');
	}


}

function cargar_unidad(){
	var unidad=document.getElementById('unidad')
	var codcen=document.getElementById('centro')
	var contenido_codcen=abrirAjax()
	contenido_codcen.open("GET", "centroajax.php?unidad="+unidad.value, true)
   	contenido_codcen.onreadystatechange=function() 
	{
		if (contenido_codcen.readyState==1)
		{
			codcen.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			codcen.appendChild(opcion)
			codcen.disabled=true
		}
		if (contenido_codcen.readyState==4)
		{
			codcen.parentNode.innerHTML = contenido_codcen.responseText;
		} 
	}
	contenido_codcen.send(null);
}
