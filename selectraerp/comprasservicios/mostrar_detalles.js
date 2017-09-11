function abrirAjax() {
	var xmlhttp=false;
	try {
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e) {
		try {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E) {
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp;
}

function detalles(valor){
	var cont=document.getElementById('detalles_proveedor')
	var contenido=abrirAjax()
	contenido.open("POST", "mostrar_detalles_proveedor.php?"+valor, true)
   contenido.onreadystatechange=function() {
		if (contenido.readyState==1) {
			cont.innerHTML="Cargando..."
		}
		if (contenido.readyState==4) {
			cont.innerHTML=contenido.responseText
		}
	}
	contenido.send(null);
}

function detalles_ordenes(valor){
	var cont=document.getElementById('detalles_orden_proveedor')
	var contenido=abrirAjax()
	contenido.open("POST", "mostrar_detalles_ordenes.php?"+valor, true)
   contenido.onreadystatechange=function() {
		if (contenido.readyState==1) {
			cont.innerHTML="Cargando..."
		}
		if (contenido.readyState==4) {
			cont.innerHTML=contenido.responseText
		}
	}
	contenido.send(null);
}
function detalles_odp(valor){
	var cont=document.getElementById('detalles_odp_proveedor')
	var contenido=abrirAjax()
	contenido.open("POST", "mostrar_detalles_odp.php?"+valor, true)
   contenido.onreadystatechange=function() {
		if (contenido.readyState==1) {
			cont.innerHTML="Cargando..."
		}
		if (contenido.readyState==4) {
			cont.innerHTML=contenido.responseText
		}
	}
	contenido.send(null);
}