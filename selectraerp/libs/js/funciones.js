// JavaScript Document
function pregunta(){
	if (confirm('Estas seguro de eliminar el registro?')){
		return true;
	}else{
		return false;
	}
}


// FX es el formulario a validar
function validar(fx){
	email_str=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/;

	for (i = 0; i < fx.elements.length; i++) {
		if ((fx.elements[i].type == "text" ||
			fx.elements[i].type == "password" ||
			fx.elements[i].type == "textarea") &&

			(fx.elements[i].type != "hidden" && fx.elements[i].type != "file") &&
			(fx.elements[i].name.indexOf("_omit") < 0) &&

			(fx.elements[i].value == "" ||
			fx.elements[i].value == "null" ||
			fx.elements[i].value == "NULL" ||
			fx.elements[i].value == "NaN" ||
			fx.elements[i].value.indexOf("\"", 0) > -1 ||
			fx.elements[i].value.indexOf("=", 0) > -1 ||
			fx.elements[i].value.indexOf("\'", 0) > -1)) {

			alert("Debe ingresar "+fx.elements[i].title);
			fx.elements[i].style.backgroundColor = "#F9E9B8";

			if(fx.elements[i].type != "hidden")
				fx.elements[i].focus();

			return false;
			break;
		}
		fx.elements[i].style.backgroundColor = "#FFFFFF";
		if( (fx.elements[i].name == "email_omit" || fx.elements[i].name == "email" || fx.elements[i].name.indexOf("CORREO") > -1) && fx.elements[i].value!=""){
			if(!email_str.test(fx.elements[i].value)) {
				alert("El formato del campo email no es valido");
				fx.elements[i].style.backgroundColor = "#F9E9B8";
				return false;
				break;
			}
		}
		if((fx.elements[i].value=="x999" || fx.elements[i].value=="X999") && (fx.elements[i].name.indexOf("_omit") < 0 )){
			if(!email_str.test(fx.elements[i].value)) {
				alert("Seleccione "+fx.elements[i].title);
				fx.elements[i].style.backgroundColor = "#F9E9B8";
				return false;
				break;
			}
		}else if (fx.elements[i].length > 1){
			fx.elements[i].style.backgroundColor = "#FFFFFF";
		}
	}
	if (cuenta == 0){
		cuenta++;
		return true;
	}else{
		//alert("Por favor espera la respuesta de tu peticion!");
		return true;
	}

}
//onKeyPress="return(formato_campo(this,event,1))"
function formato_campo(fld,e,t) {
//alert(e);
    var aux = aux2 = '';
	var i = j = 0;

	if(t==1)
    	var strCheck = '0123456789';
	if(t==2)
    	var strCheck = 'AaBbCcDdEeFfGgHhIiJjKkLl��NnMmOoPpQqRrSsTtUuVvWwXxYyZz���������� '+String.fromCharCode(225)+String.fromCharCode(233)+String.fromCharCode(237)+String.fromCharCode(243)+String.fromCharCode(250)+String.fromCharCode(193)+String.fromCharCode(201)+String.fromCharCode(205)+String.fromCharCode(211)+String.fromCharCode(218)+String.fromCharCode(241)+String.fromCharCode(209);
	if(t==3)
    	var strCheck = '0123456789-ext';
	if(t==4)
    	var strCheck = '0123456789,.';
	if(t==5)
    	var strCheck = '0123456789.';
	if(t==6)
		var strCheck = 'AaBbCcDdEeFfGgHhIiJjKkLl��NnMmOoPpQqRrSsTtUuVvWwXxYyZz���������� 0123456789-';
	if(t==7)
    	var strCheck = 'AaBbCcDdEeFfGgHhIiJjKkLlNnMmOoPpQqRrSsTtUuVvWwXxYyZz.@0123456789-_';
	if(t==8)
		var strCheck = 'ABCDEFGHIJKLNMOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	if(t==9)
		var strCheck = 'AaBbCcDdEeFfGgHhIiJjKkLl��NnMmOoPpQqRrSsTtUuVvWwXxYyZz���������� 0123456789.()/,-:';
	if(t==10)
		var strCheck = 'sS0123456789';
	if(t==11)
    	var strCheck = '0123456789:';
	if(t==12)
    	var strCheck = '0123456789-';
	if(t==13)
		var strCheck = 'ABCDEFGHIJKLNMOPQRSTUVWXYZ 0123456789-';
	if(t==14)
		var strCheck = 'VEJGvejg0123456789'; //'VEJGvejg-0123456789';
	if(t==15)
    	var strCheck = '0123456789-/ ';
	if(t==16)
    	var strCheck = '0123456789, ';
	//CBA INICIO
	if(t==17)
    	var strCheck = '0123456789';
	if(t==18)
		var strCheck = 'ABCDEFGHIJKLMN�OPQRSTUVWXYZabcdefghijklmn�opqrstuvwxyz ';
	if(t==19)
		var strCheck = 'abcdefghijklmnopqrstuvwxyz._';
	if(t==20)
		var strCheck = 'ABCDEFGHIJKLNMOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz.';

	//CBA FIN
	if(t==21)	//Para los lotes
		var strCheck = 'ABCDEFGHIJKLNMOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	if(t==22)
		var strCheck = 'AaBbCcDdEeFfGgHhIiJjKkLl��NnMmOoPpQqRrSsTtUuVvWwXxYyZz����������:';
	if(t==23)
    	var strCheck = '0123456789,';	
	if(t==24)
    	var strCheck = '0123456789-_/';

	// validando caracteres espaciales
	if(t==23) var strCheck = "ABCDEFGHIJKLMN�OPQRSTUVWXYZabcdefghijklmn�opqrstuvwxyz ,.1234567890_-+*/"+String.fromCharCode(225)+String.fromCharCode(233)+String.fromCharCode(237)+String.fromCharCode(243)+String.fromCharCode(250)+String.fromCharCode(193)+String.fromCharCode(201)+String.fromCharCode(205)+String.fromCharCode(211)+String.fromCharCode(218)+String.fromCharCode(241)+String.fromCharCode(209);

	if(navigator.appName != "Microsoft Internet Explorer")
		var whichCode = e.which;
	else
		var whichCode = e.keyCode;

	if (whichCode == 16) return false;					//shift
    if (whichCode == 0) return true;
	if (whichCode == 8) return true; 					// Enter
	if (whichCode == 9) return true; 					// Tab
	if (whichCode == 13) return true; 					// Enter
	//if (whichCode == 46) return true;
	key = String.fromCharCode(whichCode);				// Consigue el valor del codigo de tecla...
    if (strCheck.indexOf(key) == -1) return false; 		// no es una tecla valida

}

//onKeyPress="return(formato_moneda(this,event,max_ent,max_dec))"
function formato_moneda(fld, e, num_max_ent, num_max_dec, negativo) {
	if(negativo) {
		menos = '-';
	}else{
		negativo = false;
		menos = '';
	}

	var bandera=0;
	var num_dec=0;
	var comas=0;
	var comas2=0;
	var campo='';
	var aux_entero=0;
	var parte_entera='';

   var sep_dec = ',';
   var key = '';
   var i = j = 0;
   var len = len2 = 0;
   var strCheck = menos+'0123456789,';
   var aux = aux2 = '';
//   var whichCode = (window.Event) ? e.which : e.keyCode;
	if(navigator.appName != "Microsoft Internet Explorer")
		var whichCode = e.which;
	else
		var whichCode = e.keyCode;
    key = String.fromCharCode(whichCode);

	if (whichCode == 13) return true; 					// Enter

	if (whichCode == 8) return true; 					// Enter

	if (whichCode == 46) key=',';						// Enter

	if (whichCode == 0) return true; 					// Consigue el valor del codigo de tecla...

   	if (strCheck.indexOf(key) == -1){
   		return false; 	// no es una tecla valida
	}

	if(negativo){
		if(key == '-' && fld.value.length > 0)
			return false;
	}

	for(i=0;i<fld.value.length;i++){
		if(fld.value.charAt(i)=='.')
			comas2=comas2+1;
   	}

	if(comas2>0){
		campo_split=fld.value.split('.');
		for(i=0;i<=comas2;i++){
			campo+=campo_split[i];
		}
	}else
		campo=fld.value;

	if(sep_dec.indexOf(key)!= -1){
		if(campo.indexOf(key)!= -1){
			return false;
		}
	}

	if((campo+key).indexOf(',')!= -1)
		bandera=1;

	cadena=campo.split(',');
    if (cadena[0].length >= num_max_ent && key!=',' && bandera!=1)
      return false;
	if(bandera!=1)
		cadena[0]+=key;

	//calcular numero de comas
	for(i=(cadena[0].length)/3;i>1;i--)
		comas=comas+1;
	if(cadena[0].length%3 == 0)
		aux_entero=3;
	else
		aux_entero=(cadena[0].length%3);

	if(bandera!=1){
		for(i=0;i<(aux_entero);i++){
			parte_entera+=cadena[0].charAt(i);
		}
		if(cadena[0].length>3)
			parte_entera+='.';

		for(i=aux_entero,j=1;i<(cadena[0].length);i++,j++){
			parte_entera+=cadena[0].charAt(i);
			if(j%3==0&& cadena[0].length-1!=i){
				parte_entera+='.';
			}
		}

		campo_final=parte_entera;
		}
	else{
		for(i=0;i<(aux_entero);i++){
			parte_entera+=cadena[0].charAt(i);
		}
		if(cadena[0].length>3)
			parte_entera+='.';

		for(i=aux_entero,j=1;i<(cadena[0].length);i++,j++){
			parte_entera+=cadena[0].charAt(i);
			if(j%3==0&& cadena[0].length-1!=i){
				parte_entera+='.';
				}
			}
		if(cadena[1]!=undefined)
			len2=cadena[1].length;
		else len2=0;

		for(i=0;i<len2;i++){
			num_dec++;
		}
		if(num_dec>=num_max_dec)
			return false;
		if(cadena[1]!=undefined)
			campo_final=(parte_entera+','+cadena[1]+key);
		else
			campo_final=(parte_entera+',');
	}

	fld.value=campo_final;

	return false;

}


//<a href="javascript:ventanaPopUp('pagina.html','ventana','600px','400px','yes');">link</a>
function ventanaPopUp(pagina,nom_ventana,ancho,alto,scroll_b,resizable){
	var opciones=("toolbar=no, "+
				  "location=no, "+
				  "directories=no, "+
				  "status=no, "+
				  "menubar=yes, "+
				  "scrollbars="+scroll_b+","+
				  "resizable="+(resizable != '' && resizable != undefined && resizable != 'undefined' ? resizable : 'no')+","+
				  "top=100,"+
				  "left=250,"+
				  "width="+ancho+","+
				  "height="+alto+"");
	var w=window.open(pagina,nom_ventana,opciones);
}

function ventanaPopUp2(pagina,nom_ventana,ancho,alto,scroll_b,resizable){
	var opciones=("toolbar=no, "+
				  "location=no, "+
				  "directories=no, "+
				  "status=no, "+
				  "menubar=yes, "+
				  "scrollbars="+scroll_b+","+
				  "resizable="+(resizable != '' && resizable != undefined && resizable != 'undefined' ? resizable : 'no')+","+
				  "top=100,"+
				  "left=130,"+
				  "width="+ancho+","+
				  "height="+alto+"");
	var w=window.open(pagina,nom_ventana,opciones);
}

function validar_rif(rif){
	error = false;
	s_char=rif.value.substr(0,1);
	if (s_char == 'V' || s_char == 'E' || s_char == 'J' || s_char == 'G' || s_char == 'v' || s_char == 'e' || s_char == 'j' || s_char == 'g'){
		var strCheck = "0123456789";
		c_Codigo=rif.value.substr(1); //2
		for (var x=0; x < c_Codigo.length; x++) {
		  if (strCheck.indexOf(c_Codigo.charAt(x)) == -1){
			  error = true;
			  break;
		  }
		}
	}else{
		error = true;
	}

	if (error) {
		alert("El RIF ingresado no es correcto. Ingresela el el formato: [V,E,J,G]XXXXXXXXXXX");
		rif.style.backgroundColor = "#F9E9B8";
		rif.focus();
		return false;
	}

	return true;
}

function val_hora(hora, titulo){
        var er_fh = /^(1|01|2|02|3|03|4|04|5|05|6|06|7|07|8|08|9|09|10|11|12)\:([0-5]0|[0-5][1-9])\ (A|P)\M$/;
//      var er_fh = /^(1|01|2|02|3|03|4|04|5|05|6|06|7|07|8|08|9|09|10|11|12)\:([0-5]0|[0-5][1-9])$/;
        if( hora.value == "" ){
			alert("Introduzca la hora "+titulo+". Ingresela el el formato: hh:mm [AM/PM]");
			hora.focus();
			return false;
        }
        if ( !(er_fh.test( hora.value )) ) {
			alert("La hora "+titulo+" no es v�lida. Ingresela el el formato: hh:mm [AM/PM]");
			hora.focus();
			return false;
        }

        return true;
}

// Mascara para la hora
function vHora(f,e){
	if(navigator.appName != "Microsoft Internet Explorer")
		var whichCode = e.which;
	else
		var whichCode = e.keyCode;

    if (whichCode == 0) return true;
	if (whichCode == 8) return true; 					// Enter
	if (whichCode == 9) return true; 					// Tab
	if (whichCode == 13) return true; 					// Enter

	if (f.value.length < 5)
		if ((whichCode >= 48) && (whichCode <= 57)) {
			if (f.value.length == 2) {
				f.value = f.value + ":";
			}
		}else{
			return false;
		}
	else{
		if (f.value.length >= 5){
			if ((whichCode >= 48) && (whichCode <= 57)) {
				return false;
			}else
				if ((whichCode == 65) || (whichCode == 97) || (whichCode == 80) || (whichCode == 112)) {
					var l = "";
					if ((whichCode == 65) || (whichCode == 97))
						l = " A";
					if ((whichCode == 80) || (whichCode == 112))
						l = " P";

					f.value = f.value.replace(' ','') + l + "M";
				}else return false;
		}
	}
}

function ltrim(s) {
   return s.replace(/^\s+/, "");
}

function rtrim(s) {
   return s.replace(/\s+$/, "");
}

function trim(s) {
   return rtrim(ltrim(s));
}

function ale(valor){
	alert(valor);
	return false;
}


// funciones por oscar arocha

function avanzaPg(formulario){
	pg = parseInt(window.document.getElementById('pgActual').value);
	window.document.getElementById('pgActual').value = pg+1;
    form = window.document.getElementById(formulario); //selecciona formulario por el id
    form.submit(); // realiza submit del formulario
}

function enviaPg(pag,formulario){
	window.document.getElementById('pgActual').value = pag;
    form = window.document.getElementById(formulario); //selecciona formulario por el id
    form.submit(); // realiza submit del formulario
}


function regresaPg(formulario){
	pg = parseInt(window.document.getElementById('pgActual').value);
	window.document.getElementById('pgActual').value = pg-1;
    form = window.document.getElementById(formulario); //selecciona formulario por el id
    form.submit(); // realiza submit del formulario
}

function compara_fechas(fecha, fecha2)  {  
var xMonth=fecha.substring(3, 5);  
var xDay=fecha.substring(0, 2);  
var xYear=fecha.substring(6,10);  
var yMonth=fecha2.substring(3, 5);  
var yDay=fecha2.substring(0, 2);  
var yYear=fecha2.substring(6,10);  
 if (xYear> yYear)  {  
	return(true)  
 }  
 else{  
	if (xYear == yYear){   
		if (xMonth> yMonth)  {  
		    return(true)  
        }  
        else{   
           if (xMonth == yMonth)  {  
	       if (xDay> yDay)  return(true);  
	       else  return(false);  
           }  
           else  return(false);  
        }  
     }  
     else  return(false);  
 }  
}  

//Compara las fechas, incluyendo si son iguales
function validar_fechas(fecha, fecha2)  {  
//fecha menor
var xYear = parseInt(fecha.substr(0,4));  
var xMonth = parseInt(fecha.substr(5,2));  
var xDay = parseInt(fecha.substr(7,2));  
//fecha mayor
var yYear = parseInt(fecha2.substr(0,4)); 
var yMonth = parseInt(fecha2.substr(5,2));  
var yDay = parseInt(fecha2.substr(7,2));  

 if (xYear > yYear)  {
	return false;  
 }
 else{
	 if(xYear < yYear){
	    return true;
     }
     else{  
		if (xMonth > yMonth)  {
		    return false;  
        }  
        else{   
           if (xMonth == yMonth)  {  
	           if (xDay > yDay){
		            return false;
	           }
	           else{
	        	   if(xDay <= yDay){
	        		   return true;
	        	   }	   
	           }   
           }  
           else{
        	   return true;
           }
        }  
    }
 }
}  

function diaFinal(anho,mes){
		//var dia = 0;
		switch(mes){
			case '01':
			case '03':
			case '05':
			case '07':
			case '08':
			case '10':
			case '12':{
				dia = 31;
			}break;
			case '04':
			case '06':
			case '09':
			case '11':{
				dia = 30;
			}break;
			case '02':{
				if(anho%4 == 0) dia = 29;
				else dia = 28
			}break;
		}
		return dia;
}


function validaString(str){
	   var permitidos = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz ,.1234567890_-+*/%"+String.fromCharCode(225)+String.fromCharCode(233)+String.fromCharCode(237)+String.fromCharCode(243)+String.fromCharCode(250)+String.fromCharCode(193)+String.fromCharCode(201)+String.fromCharCode(205)+String.fromCharCode(211)+String.fromCharCode(218)+String.fromCharCode(241)+String.fromCharCode(209); //lista de caracteres permitidos
	   var caract;
	   var valor = 1;
	  if(str.length > 0){
	   for(i=0;i<str.length;i++){
	      caract = str.charAt(i);
	      if(permitidos.indexOf(caract) == -1){
	         //valor = 'Error Caracter';
	         valor = 0;
	         break;
	      }
	    }
	   }
	   else valor = 0;
	   return valor;
}


var timeout	= 300;
var closetimer	= 0;
var ddmenuitem	= 0;
var nivel1=0;
var nivel2=0;
var sw=0;
var opacidad=0;
var id_intervalo="";


var tickercontents=new Array()
var tickdelay=6000 //delay btw messages
var highlightspeed=4 //10 pixels at a time.
var currentmessage=0
var clipwidth=0

function mensajeInicial(estatus,timer1){
    
    var width= screen.width+"px";
    var height=screen.height-190+"px";
    var left= ((screen.width-475)/ 2)  +"px";

    if (window.innerWidth) //if browser supports window.innerWidth		
        var top= ((window.innerHeight-411-50)/2)+"px";
    else if (document.all) //else if browser supports document.all (IE 4+)
        var top= ((document.body.clientHeight-411)/2)+"px";

    var div_fondo_opaco =  document.getElementById("fondo_opaco");	
    var div_mensaje_emergente =  document.getElementById("mensaje_emergente");	

    div_mensaje_emergente.style.top=top;
    div_mensaje_emergente.style.left=left;
    div_fondo_opaco.style.height=height;
    div_fondo_opaco.style.width=width;;

    id_intervalo2 = setInterval("document.getElementById('cierre').style.visibility='visible'",timer1);

    //alert(estatus);
    if (estatus == 'abrir'){		
        div_fondo_opaco.style.display = 'block';
        div_mensaje_emergente.style.display = 'block';
        id_intervalo = setInterval("mostrar_fondo_opaco()",30);
    }else{
        div_fondo_opaco.style.display = 'none';
        div_mensaje_emergente.style.display = 'none';		
    }
}

function mostrar_fondo_opaco(){
	
	opacidad +=0.03;
	
	var div_fondo_opaco =  document.getElementById("fondo_opaco");
	var div_mensaje_emergente =  document.getElementById("mensaje_emergente");	
		
	if (opacidad > 0.76){
		//Termina la carga del div del fondo
		clearInterval(id_intervalo);
		div_mensaje_emergente.style.display = 'block';		
	}else{
		div_fondo_opaco.style.opacity = opacidad;
		div_mensaje_emergente.style.opacity = 0.25 + opacidad;
		
	}
}

// open hidden layer
function mopen(id, n1, n2){		
	//alert(nivel);
	// cancel close timer
	mcancelclosetime();
	document.onclick = mclose;
	// close old layer
	if (n1 == 0){
		mclose();		
	}else{
		sw=1;
	}
	// get new layer and show it

	ddmenuitem = document.getElementById(id);
	ddmenuitem.style.visibility = 'visible';

}
// close showed layer
function mclose()
{
    if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';	
}

// go close timer
function mclosetime()
{		
    closetimer = window.setTimeout(mclose, timeout);
	
}

// cancel close timer
function mcancelclosetime(val)
{
	//alert(val);
	
	if(closetimer)
	{
            window.clearTimeout(closetimer);
            closetimer = null;
	}
}

// close layer when click-out
document.onclick = mclose; 

function invocarOpcion(opcion){
	
    var width= screen.width - 8;
    var height=screen.height - 120;

    var opciones = "toolbar=no, status =yes , menubar=no, resizable=yes, location=yes, width=" + width  + "," + "height= " +height + ", left = 0 , top = 0, scrollbars=yes";
    //alert(opciones);
    ventana = window.open(opcion , 'ventana',opciones );
    ventana.focus();
    //document.write('<html> <body> <logic:forward name="registro_portada" />  </body> </html>');
    //alert('si');
	
}


function changetickercontent(){
    crosstick.style.clip="rect(0px 0px auto 0px)";
    crosstick.innerHTML=tickercontents[currentmessage];
    highlightmsg()
}

function highlightmsg(){
    var msgwidth=crosstick.offsetWidth
    if (clipwidth<msgwidth){
        clipwidth+=highlightspeed
        crosstick.style.clip="rect(0px "+clipwidth+"px auto 0px)"
        beginclip=setTimeout("highlightmsg()",30)
    }else{
        clipwidth=0
        clearTimeout(beginclip)
        if (currentmessage==tickercontents.length-1) currentmessage=0
        else currentmessage++ 

        setTimeout("changetickercontent()",tickdelay)
 
    }
}

function start_ticking(mensaje1, mensaje2, mensaje3){


    if(mensaje1!='') tickercontents[0] = mensaje1;
    
    if(mensaje2!='') tickercontents[1] = mensaje2;
    
    if(mensaje3!='') tickercontents[2] = mensaje3;
    
    crosstick=document.getElementById? document.getElementById("marquesina") : document.all.marquesina

    crosstickParent=crosstick.parentNode? crosstick.parentNode : crosstick.parentElement

    if (parseInt(crosstick.offsetHeight)>0)
        crosstickParent.style.height=crosstick.offsetHeight+'px'
    else
        //setTimeout("crosstickParent.style.height=crosstick.offsetHeight+'px'",100) //delay for Mozilla's sake
        changetickercontent()
}