function nuevoAjax()  // crea un objeto httpxmlrequest. Verifica si el explorador es explorer u otro ya que se crean de manera distinta
{    
    var miAjax=null;

    if (navigator.appName=="Microsoft Internet Explorer")
    {
        miAjax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else
    {
        miAjax= new XMLHttpRequest();
    }	
    return miAjax; // retorna un objeto httpxml request
}


function enviarPost(url,destino)
{
    miAjax = nuevoAjax(); // se crea el objeto xmlthhprequest
    miAjax.open("POST",url,true); //se setea la url y el método de apertura de la dirección
    miAjax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');

    miAjax.onreadystatechange=function()
    {
        var aux = document.getElementById(destino); 
        if (miAjax.readyState==4)
        {
            aux.innerHTML=miAjax.responseText;
        }
        else
        {
            aux.innerHTML="Cargando... <img src='Imagenes/load.gif'/> ";
        }        
    };
	
	
	
    var variables = document.getElementsByTagName('input');
    var auxi,cadi;
    cadi = "";
    for (auxi=0; auxi < variables.length ; auxi++)
    {
        try {
            if (variables[auxi].value !='' ) cadi = cadi  + variables[auxi].id + "=" + variables[auxi].value + "&";
        } catch (error) {}
    }
	

    window.setTimeout(function () {
        miAjax.send(cadi);
    }, 100);

	

}


function cargarVideosActuales()
{


//cargarUrl('/Interfaz/procesamientoDatos/listado.php?id='" + idItemVideo + "'&lista=VIDEOS RELACIONADOS&baseDatos=prueba','videosRelacionados')
}




function cargarUrl(cadena,destiny) //se llama a una dirección origen y se devuelve el contenido de la pagina en el sección destino establecida
{  //alert (window.location); 
 

    var dest = destiny.split('-');
    var destino = dest[0];
    var idItemVideo = dest[1];








    miAjax = nuevoAjax(); // se crea el objeto xmlthhprequest
    miAjax.open("GET",cadena,true); //se setea la url y el método de apertura de la dirección
    miAjax.onreadystatechange=function()
    {
        var aux = document.getElementById(destino); 
        if (miAjax.readyState==4)
        {
	
            try
            {
                eval(miAjax.responseText);
            } catch(err) {
                aux.innerHTML=miAjax.responseText;
            }
	   
	
        }
        else
        {
			
    //aux.innerHTML="Cargando... <img src='Imagenes/load.gif'/> ";

    }
               
    };
    miAjax.send(null);
	
	


}




function verificarCampos(cadena,form) //se llama a una dirección origen y se devuelve el contenido de la pagina en el sección destino establecida
{   



    miAjax = nuevoAjax(); // se crea el objeto xmlthhprequest
    miAjax.open("GET","/Interfaz/validaciones/ValidacionesBaseDatos.php?"+cadena,true); //se setea la url y el método de apertura de la dirección
    miAjax.onreadystatechange=function()
    { 
        if (miAjax.readyState==4)
        {
		
            if (miAjax.responseText.length!=0)
            {    
                eval(miAjax.responseText);
            
            }
            else
            {
		      
			
        }
	                
		
        }
		
               
    };

    miAjax.send(null);
     


}




function borrarTodosLosMenus()
{


    for(var i=1; i<=20;i++)
    {
        if(document.miSeccionAnterior[i]!=undefined)
            document.miSeccionAnterior[i].style.visibility="hidden";
	
    }
	


}
function borrarMenus(nivel)
{


    while(document.miSeccionAnterior[nivel]!=undefined)
    {
	
        document.miSeccionAnterior[nivel].style.visibility="hidden";
        nivel++;
    }
	


}


function cargarUrl2(cadena,destino,nivel) //se llama a una dirección origen y se devuelve el contenido de la pagina en el sección destino establecida
{   

    var j;
    var aux3;

    if (document.miSeccionAnterior==undefined )
    {
        var miSeccion=new Array();
        document.miSeccionAnterior=miSeccion;
    }


    borrarMenus(nivel);




    aux3 = document.getElementById(destino);

    document.miSeccionAnterior[nivel]=aux3;
    aux3.style.visibility='visible';


/*if (aux3.innerHTML.length<3 || aux3.innerHTML== "Cargando... <img src='Imagenes/load.gif'/> ")
//cargarUrl(cadena,destino);*/

}


function volver()
{

    cargarUrl(document.arreglo[document.cont-2],document.miSeccion[document.cont-2]);
    if (document.cont>=2) {
        document.cont=document.cont-2;
    }
}

function actualizarFoto(seccion,foto)
{


    var aux = document.getElementById(seccion);
    aux.src="../../fotosPersonas/"+foto;
//cargarUrl(document.arreglo[document.cont-1],seccion);

}

function actualizarVideo(seccion,video)
{


    var aux = document.getElementById('cajita' + seccion);

    aux.innerHTML="<video  id='" + seccion + "'  width= '300' src='../../videos/" +video + "'  controls> </video><br><a class='linkVideo' href='../../videos/" + video + "' target='_blank'> Tamaño Original </a>";
//cargarUrl(document.arreglo[document.cont-1],seccion);

}

function activarSubMenu(primerMenu,menu1,menu2)
{
    var seccion = document.getElementById("seccion1");
    var m1 = document.getElementById(menu1);
    var m2 = document.getElementById(menu2);
    var aux=m1;

    //try {alert (m2.id);} catch(err) {}



    seccion.onmouseover = function () 

    {  
        var aux2=document.getElementById(primerMenu);
        try {
            while (aux2.hijoActivo != 'undefined')
            {
                aux2.hijoActivo.style.visibility='hidden';
                aux2=aux2.hijoActivo;
            }
	
        } 
        catch(err) {}

    }




    try {
        while (aux.hijoActivo != 'undefined')
        {
            aux.hijoActivo.style.visibility='hidden';
            aux=aux.hijoActivo;
        }
	
    } 
    catch(err) {}


    m2.style.visibility='visible';
    m2.padreActivo=m1;
    m1.hijoActivo=m2;


        


}

