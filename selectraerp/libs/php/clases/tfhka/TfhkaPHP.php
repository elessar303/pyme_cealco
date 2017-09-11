<?php

class Tfhka {

    var $NamePort = "", $IndPort = false, $StatusError = "";

    function Tfhka() {

    }

// Funcion que establece el nombre del puerto a utilizar
    function SetPort($namePort = "") {
        $archivo = 'C:\IntTFHKA\Puerto.dat';
        $fp = fopen($archivo, "w");
        $string = "";
        $write = fputs($fp, $string);
        $string = $namePort;
        $write = fputs($fp, $string);
        fclose($fp);

        $this->NamePort = $namePort;
    }

// Funcion que verifica si el puerto est� abierto y la conexi�n con la impresora
//Retorno: true si esta presente y false en lo contrario
    function CheckFprinter() {
        $LineaComando = "Comando.bat";
        $fp2 = fopen($LineaComando, "w");
        $sentencia = "c:\IntTFHKA\IntTFHKA CheckFprinter()";
        $write = fputs($fp2, $sentencia);
        fclose($fp2);

        exec($LineaComando);

        $rep = "";
        $repuesta = file('C:\IntTFHKA\Status_Error.txt');
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $rep = $repuesta[$i];
        }
        $this->StatusError = $rep;
        if (substr($rep, 0, 1) == "T") {
            $this->IndPort = true;
            return $this->IndPort;
        } else {
            $this->IndPort = false;
            return $this->IndPort;
        }
    }
     function CheckFprinter1() {
        $Check=0;
        $nombTxt="C:\IntTFHKA\Status_Error.txt";
        $sentencia = "c:\IntTFHKA\IntTFHKA CheckFprinter()";
        $salida = shell_exec($sentencia);  
        $file = fopen($nombTxt,"r");
        //solo se lee la primera linea (la unica)       
        $fila=  fgets($file);
        // \t para separar la variable false 
        $estado = explode("\t", $fila);   
        //retorno true o false    
        $retorno= $estado[0];
        //variable de status 
        $estatus= $estado[1];
        //variable de error
        $error=$estado[2];       
        fclose($file);
        if($retorno=="FALSE"){
            $check=0;
        }else{
            $check=1;
        }
        //para borrar el archivo
        //$archivo=fopen($nombTxt,"w");
        //fclose($archivo);             
        
        return $check;

       
    }
     function ImpresoraEstado() {

        $nombTxt="C:\IntTFHKA\Status_Error.txt";        
        $file = fopen($nombTxt,"r");
        //solo se lee la primera linea (la unica)       
        $fila=  fgets($file);
        // \t para separar la variable false 
        $estado = explode("\t", $fila);   
        //retorno true o false    
        $retorno= $estado[0];
        //variable de status 
        $estatus= $estado[1];
        //variable de error
        $error=$estado[2];       
        fclose($file);       
        //para borrar el archivo
        //$archivo=fopen($nombTxt,"w");
        //fclose($archivo);             
        
        return $error;
       
    }


   

//Funci�n que envia un comando a la impresora
//Par�metro: Comando en cadena de caracteres ASCII
//Retorno: true si el comando es valido y false en lo contrario
    function SenCmd($cmd = "") {

        $LineaComando = "Comando.bat";
        $fp2 = fopen($LineaComando, "w");
        $sentencia = "c:\IntTFHKA\IntTFHKA SendCmd(" . $cmd . ")";
        $write = fputs($fp2, $sentencia);
        fclose($fp2);

        exec($LineaComando);

        $rep = "";
        $repuesta = file('C:\IntTFHKA\Status_Error.txt');
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $rep = $repuesta[$i];
        }
        $this->StatusError = $rep;
        if (substr($rep, 0, 1) == "T")
            return true;
        else
            return false;
    }

// Funcion que verifiva el estado y error de la impresora y lo establece en la variable global  $StatusError
//Retorno: Cadena con la informaci�n del estado y error y validiti bolleana
    function ReadFpStatus() {
        $LineaComando = "Comando.bat";
        $fp2 = fopen($LineaComando, "w");
        $sentencia = "c:\IntTFHKA\IntTFHKA ReadFpStatus()";
        $write = fputs($fp2, $sentencia);
        fclose($fp2);

        exec($LineaComando);

        $rep = "";
        $repuesta = file('C:\IntTFHKA\Status_Error.txt');
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $rep = $repuesta[$i];
        }

        $this->StatusError = $rep;

        return $this->StatusError;
    }

// Funci�n que ejecuta comandos desde un archivo de texto plano
//Par�metro: Ruta del archivo con extenci�n .txt � .bat
//Retorno: Cadena con n�mero de lineas procesadas en el archivo y estado y error
    function SendFileCmd($ruta = "") {
        $LineaComando = "Comando.bat";
        $fp2 = fopen($LineaComando, "w");
        $sentencia = "c:\IntTFHKA\IntTFHKA SendFileCmd(" . $ruta . ")";
        $write = fputs($fp2, $sentencia);
        fclose($fp2);

        exec($LineaComando);

        $rep = "";
        $repuesta = file('C:\IntTFHKA\Status_Error.txt');
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $rep = $repuesta[$i];
        }
        return $rep;
    }

//Funci�n que sube al PC un tipo de estado de  la impresora
//Par�metro: Tipo de estado en cadena Ejem: S1
//Retorno: Cadena de datos del estado respectivo
    function UploadStatusCmd($cmd = "") {

        $LineaComando = "Comando.bat";
        $fp2 = fopen($LineaComando, "w");
        $sentencia = "c:\IntTFHKA\IntTFHKA UploadStatusCmd(" . $cmd . ")";
        $write = fputs($fp2, $sentencia);
        fclose($fp2);

        exec($LineaComando);

        $repStErr = "";
        $repuesta = file('C:\IntTFHKA\Status_Error.txt');
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $repStErr = $repuesta[$i];
        }
        $this->StatusError = $repStErr;

        $rep = "";
        $repuesta = file('C:\IntTFHKA\Status.txt');
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $rep = $repuesta[$i];
        }

        return $rep;
    }

//Funci�n que sube al PC reportes X � Z de la impresora
//Par�metro: Tipo de reportes en cadena Ejem: U0X. Otro Ejem:   U3A000002000003
//Retorno: Cadena de datos del o los reporte(s)
    function UploadReportCmd($cmd = "") {

        $LineaComando = "Comando.bat";
        $fp2 = fopen($LineaComando, "w");
        $sentencia = "c:\IntTFHKA\IntTFHKA UploadReportCmd(" . $cmd . ")";
        $write = fputs($fp2, $sentencia);
        fclose($fp2);

        exec($LineaComando);

        $repStErr = "";
        $repuesta = file('C:\IntTFHKA\Status_Error.txt');
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $repStErr = $repuesta[$i];
        }
        $this->StatusError = $repStErr;

        $rep = "";
        $repuesta = file('C:\IntTFHKA\Reporte.txt');
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $rep .= $repuesta[$i];
        }

        return $rep;
    }

}

?>