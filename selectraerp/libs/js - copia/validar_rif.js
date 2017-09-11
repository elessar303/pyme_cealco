$(document).ready(iniciar);


function iniciar()
{
//$('#rif').keyup(validarrif);
//$('#rif').blur(validarrif);
//$('.error').hide();
$('#nit').keyup(validar);
$('#nit').blur(validar);
$('.error').hide();
}


// Validar RIF Venezuela
function validarrifv(){
	rif=$('#rif').get(0).value;

	if(!rif.match(/^[G,J]+[-]+([0-9]{8})+[-]+([0-9]{1})$/))
{	$("span#rif_error").show();
$('#rif').addClass("inc");
	    $('#rif').removeClass("corr");
$("#rif").focus();
		sw =0;

	}else{$("span#rif_error").hide();
	$('#rif').addClass("corr");
		$('#rif').removeClass("inc");}
}

//Fin  Validar RIF Venezuela

// Funcion Validar NIT Colombia
function validar() {

i_rut=$('#nit').get(0).value;
//i_rut=i_rut.substring(0, 9)
cadena=i_rut.split('-')
i_rut=cadena[0];
digv=cadena[1];
//alert (i_rut)
//alert (digv)
var pesos = new Array(71,67,59,53,47,43,41,37,29,23,19,17,13,7,3);
rut_fmt = zero_fill(i_rut, 15)
//alert(rut_fmt)
suma = 0
for ( i=0; i<=14; i++ )
suma += rut_fmt.substring(i, i+1) * pesos[i]
//alert(suma)
resto = suma % 11
//alert(resto)
if ( resto == 0 || resto == 1 )
digitov = resto
else
digitov = 11 - resto

//alert(digitov)
    if(digitov!=digv)
    {
        $("span#nit_error").show();
        $('#nit').addClass("inc");
        $('#nit').removeClass("corr");
        $("#nit").focus();
        sw =0;

    }else
    {   $("span#nit_error").hide();
        $('#nit').addClass("corr");
        $('#nit').removeClass("inc");
    }
//return(digitov)
}

function zero_fill(i_valor,num_ceros) {

//num_ceros = "000000";
relleno = ""
i = 1
salir = 0
while ( ! salir ) {
total_caracteres = i_valor.length + i
if ( i > num_ceros || total_caracteres > num_ceros )
salir = 1
else
relleno = relleno + "0"
i++
}

i_valor = relleno + i_valor
//alert(i_valor)
return i_valor
}

// Fin Funcion Validar NIT Colombia

/*
 Otra Funcion para Validar NIT

function validar() {
foundError = false;
showError = false;
	if(!isCheckOK() && showError == false){
		alert("Error en el dígito de verificación del NIT");
		$("span#rif_error").show();
                $('#nit').addClass("inc");
                $('#nit').removeClass("corr");
                $("#nit").focus();
                sw =0;
	}
	else
		alert("Nit Correcto!");
}

function isCheckOK() {
	ceros = "000000";
	li_peso= new Array();
	li_peso[0] = 71;
	li_peso[1] = 67;
	li_peso[2] = 59;
	li_peso[3] = 53;
	li_peso[4] = 47;
	li_peso[5] = 43;
	li_peso[6] = 41;
	li_peso[7] = 37;
	li_peso[8] = 29;
	li_peso[9] = 23;
	li_peso[10] = 19;
	li_peso[11] = 17;
	li_peso[12] = 13;
	li_peso[13] = 7;
	li_peso[14] = 3;

	//nit=$('#nit').get(0).value;
	ls_str_nit = ceros + $('#nit').get(0).value;
	li_suma = 0;
	for(i = 0; i < 15; i++){
				li_suma += ls_str_nit.substring(i,i+1) * li_peso[i];
	}
	digito_chequeo = li_suma%11;
	if (digito_chequeo >= 2)
		digito_chequeo = 11 - digito_chequeo;

        alert (digito_chequeo)
	if(document.forma1.chequeo.value != digito_chequeo){
		return false;
	}
	else
		return true;
}
*/