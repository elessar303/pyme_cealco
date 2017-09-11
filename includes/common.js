function marcartodos(check_global,check_hijo){
	var opcion_global=document.getElementById(check_global)
	
	
	for(i=0; ele=document.frmPrincipal.elements[i]; i++){  		
		if (ele.id==check_hijo)
			{ele.checked =opcion_global.checked;}			
	}	

}
function MarcarTodos(valores)
{
	if (document.frmPrincipal.marcar_todos.value==1)
		{Opcion=true;document.frmPrincipal.marcar_todos.value=0;}
	else
		{Opcion=false;document.frmPrincipal.marcar_todos.value=1;}

	for(i=0; ele=document.frmPrincipal.elements[i]; i++){  		
		if (ele.name==valores)
			{ele.checked =Opcion;}			
	}	
}

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
	contenido_programa.open("GET", "procesos.php?accion=1&sector="+sector.value, true)
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

function periodo2()
{
	var frecuencia=document.getElementById('cboFrecuencia')
	
	var tdperiodo=document.getElementById('periodo')
	//alert(frecuencia.value)
	var contenido_tdperiodo=abrirAjax()
	contenido_tdperiodo.open("GET", "procesos.php?frecuencia="+frecuencia.value+"&opcion=1", true)
   	contenido_tdperiodo.onreadystatechange=function() 
	{
		if (contenido_tdperiodo.readyState==4)
		{
			tdperiodo.parentNode.innerHTML = contenido_tdperiodo.responseText;
		}
	}
	contenido_tdperiodo.send(null);
	
}

function cargar_fecha()
{
	var periodo=document.getElementById('sel_periodo')
	var frecuencia=document.getElementById('cboFrecuencia')
	var fechas=document.getElementById('fechas')
	//alert(frecuencia.value)
	var contenido_fechas=abrirAjax()
	contenido_fechas.open("GET", "procesos.php?frecuencia="+frecuencia.value+"&opcion=2&periodo="+periodo.value, true)
   	contenido_fechas.onreadystatechange=function() 
	{
		if (contenido_fechas.readyState==4)
		{
			fechas.parentNode.innerHTML = contenido_fechas.responseText;
		}
	}
	contenido_fechas.send(null);	
}

function cargar_tipo()
{
	var tipo=document.getElementById('tipo_registro')
	var cedula=document.getElementById('cedula')
	var codigo=document.getElementById('codigo')
	var tipotipo=document.getElementById('registro')
	//var fechas=document.getElementById('fechas')
	//alert(frecuencia.value)
	var contenido_tipotipo=abrirAjax()
	contenido_tipotipo.open("GET", "../paginas/procesos.php?opcion="+tipo.value+"&cedula="+cedula.value+"&codigo="+codigo.value, true)
   	contenido_tipotipo.onreadystatechange=function()
	{
		if (contenido_tipotipo.readyState==4)
		{
			tipotipo.parentNode.innerHTML = contenido_tipotipo.responseText;
		}
	}
	contenido_tipotipo.send(null);
}

function alerta(id,dia,anio)
{
	var celda=document.getElementById(id)	
	var laborable= document.getElementById('estado')
	var contenido_celda=abrirAjax()
	contenido_celda.open("GET", "modificar_calendarios.php?fecha="+id+"&estado="+laborable.value+"&dia="+dia, true)
   	contenido_celda.onreadystatechange=function() 
	{
		if (contenido_celda.readyState==4)
		{
			celda.parentNode.innerHTML = contenido_celda.responseText;
		}
	}
	contenido_celda.send(null);
	parent.cont.location.href="calendarios.php?estado="+laborable.value+"&ano="+anio
	//alert("FUNCIONA"+celda+id+dia+laborable.value);
}

function cargar_sueldo(){
 	var monto=document.getElementById('txtmonto')
	var cargo=document.getElementById('cboCargos')
	var paso=document.getElementById('txtpaso')
	
	var contenido_monto=abrirAjax()
	
	contenido_monto.open("GET", "procesos.php?opcion=3&cargo="+cargo.value+"&paso="+paso.value, true)
   	contenido_monto.onreadystatechange=function() 
	{ 
		if (contenido_monto.readyState==1)
		{
			monto.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			monto.appendChild(opcion)
			//monto.disabled=true
		}
		if (contenido_monto.readyState==4)
		{
			//monto.value = contenido_monto.responseText;
			monto.parentNode.innerHTML = contenido_monto.responseText;
		}
	}
	contenido_monto.send(null);
}


function cargar_ccosto(){
 	var unidad=document.getElementById('unidad')
	var ccosto=document.getElementById('ccosto')
	//var actividad=document.getElementById('sel_actividad')
	
	var contenido_ccosto=abrirAjax()
	
	contenido_ccosto.open("GET", "ccosto.php?opcion=1&unidad="+unidad.value, true)
   	contenido_ccosto.onreadystatechange=function() 
	{ 
		if (contenido_ccosto.readyState==1)
		{
			ccosto.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			ccosto.appendChild(opcion)
			ccosto.disabled=true
		}
		if (contenido_ccosto.readyState==4)
		{
			ccosto.parentNode.innerHTML = contenido_ccosto.responseText;
		}
	}
	contenido_ccosto.send(null);
}


function cargar_nombre(){
 	var nombre=document.getElementById('nombre')
	var ficha=document.getElementById('ficha')
	nombre.innerHTML =''
	
	var contenido_nombre=abrirAjax()
	
	contenido_nombre.open("GET", "procesos2.php?opcion=1&ficha="+ficha.value, true)
   	contenido_nombre.onreadystatechange=function() 
	{ 
		if (contenido_nombre.readyState==1)
		{
			nombre.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			nombre.appendChild(opcion)
			//monto.disabled=true
		}
		if (contenido_nombre.readyState==4)
		{
			//monto.value = contenido_monto.responseText;
			nombre.parentNode.innerHTML = contenido_nombre.responseText;
		}
	}
	contenido_nombre.send(null);
}

function cargar_concepto(){
 	var nombre=document.getElementById('concepto')
	var concepto=document.getElementById('codcon')
	var contenido_nombre=abrirAjax()
	
	contenido_nombre.open("GET", "procesos2.php?opcion=2&concepto="+concepto.value, true)
   	contenido_nombre.onreadystatechange=function() 
	{ 
		if (contenido_nombre.readyState==1)
		{
			nombre.length=0
			var opcion=document.createElement("opcion")
			opcion.value=0
			opcion.innerHTML= "Cargando..."
			nombre.appendChild(opcion)
			//monto.disabled=true
		}
		if (contenido_nombre.readyState==4)
		{
			//monto.value = contenido_monto.responseText;
			nombre.innerHTML =''
			nombre.parentNode.innerHTML = contenido_nombre.responseText;
		}
	}
	contenido_nombre.send(null);
}