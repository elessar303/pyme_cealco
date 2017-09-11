
function mostrarBotonAgregar()
{
    var boton = document.getElementById('boton1');

    if (document.bdcorrecta && document.empresacorrecta && document.bdcorrecta2 && document.bdcorrecta3)
        boton.style.visibility = 'visible';
    else
        boton.style.visibility = 'hidden';

}
function chk_NombreEmpresa(valor){

    if (valor.value != '')
    {

        var aux = 'validarEmpresa.php?nombreBD=' + valor.value;

        cargarUrl(aux ,'resultado1');

    }
    else

    {
        var aux = document.getElementById('resultado1');
        aux.innerHTML='';


    }

    mostrarBotonAgregar();

}

function agregarEmpresa(){
    if (confirm('Va a crear una nueva empresa. Continuar?')){
        var boton = document.getElementById('boton1');
        boton.style.visibility = 'hidden';
        var aux = document.getElementById('iframe_procesamiento');
        var formu = document.getElementById('formularioNuevaEmpresa');
        var procesando = document.getElementById('procesando');
        aux.src = 'agregar_empresa.php?nombre=' + formu.nombre.value + '&baseDatosAdm=' + formu.base_adm.value + '&baseDatosCon=' + formu.base_con.value + '&baseDatosNom=' + formu.base_nom.value ;
        formu.nombre.value= '';
        formu.base_adm.value = '';
        formu.base_con.value = '';
        formu.base_nom.value = '';
        ocultarAgregarEmpresa();
        mostrarProcesando();
    }

}

function chk_BaseDatos(valor){

    if(valor.id== 'base_adm')
    {
        resultado = 'resultado2';
    }else if(valor.id== 'base_con')
    {
        resultado = 'resultado3';
    }else if(valor.id== 'base_nom')
    {
        resultado = 'resultado4';
    }
    if (valor.value != ''){
        var aux = 'validarBaseDatos.php?nombreBD=' + valor.value + '&resul='+resultado;
        cargarUrl(aux ,resultado);
    }else{
        var aux = document.getElementById(resultado);
        aux.innerHTML='';
    }
    mostrarBotonAgregar();
}


function urldecode (str) {

    return decodeURIComponent(str.replace(/\+/g, '%20'));
}

function mostrarAgregarEmpresa()
{

    var aux = document.getElementById('nuevaEmpresa');
    aux.style.visibility='visible';

}

function ocultarAgregarEmpresa()
{

    var aux = document.getElementById('nuevaEmpresa');
    aux.style.visibility='hidden';

}

function mostrarProcesando()
{

    var aux = document.getElementById('procesando');
    aux.style.visibility='visible';

}

function ocultarProcesando()
{

    var aux = document.getElementById('procesando');
    aux.style.visibility='hidden';

}