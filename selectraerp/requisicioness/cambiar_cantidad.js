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

function cambiar_cantidades(celda,codigo,descripcion,cam,id,centro,id_det,unidad){
 
	//alert("entre aqui")
	var cont=document.getElementById(celda)
	
 	cont.innerHTML=cam
	var contenido=abrirAjax()
	//alert("cambiar_cantidades_req.php?codigo="+codigo+"&cantidad="+cam+"&id="+id+"&centro="+centro+"&id_det="+id_det+"&unidad="+unidad)
	contenido.open("GET","cambiar_cantidades_req.php?codigo="+codigo+"&cantidad="+cam+"&id="+id+"&centro="+centro+"&id_det="+id_det+"&unidad="+unidad, true)
	
   contenido.onreadystatechange=function() 
	{ 
		/*if (contenido.readyState==1)
		{
	
			
			cont.innerHTML="Cargando..."
			
		}
		if (contenido.readyState==4)
		{
			
			cont.innerHTML=contenido.responseText
			
	
		} */
	}
	
	contenido.send(null);
}