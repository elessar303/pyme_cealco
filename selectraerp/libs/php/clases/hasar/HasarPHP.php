<?php

class HasarPHP {

    var $NamePort = "", $IndPort = false, $StatusError = "";

    /**
     * PrintLineItem - Imprimir ítem<br/>
     * $PI = "B" ASCII 66<br/>
     * Estado no válido* -> ERROR_0<br/>
     * Error sintáctico en alguno de los parámetros 1 a 5 -> ERROR_1 a ERROR_5<br/>
     * Tasa no coincide con las programadas -> ERROR_4<br/>
     * Error en límites (> máximo del documento o < 0) -> ERROR_0**<br/>
     * estados no válidos: todos menos SELL_DF y PRINT_TEXT_DF<br/>
     * *modifica status fiscal: comando no válido (bit 5) y desborde total (bit 6)<br/>
     */
    var $PI = 66;

    /**
     * OpenFiscalReceipt - Abrir comprobante fiscal<br/>
     * $DF = "@" ASCII 64<br/>
     * Estado no válido* -> ERROR_0<br/>
     * Error sintáctico en algunos de los parámetros 1 a 7 -> ERROR_1 a ERROR_7<br/>
     * *estados no válidos: todos menos INI_JF y EN_JF<br/>
     */
    var $DF = 64;

    /**
     * SetHeader - Texto Encabezado Documentos
     * $TH = "]" ASCII 93
     */
    var $TH = 93;

    /**
     * Separador de campos del comando
     * FS ASCII 28
     */
    var $FS = 28;

    /**
     * Caracter de nueva line NEWLINE
     * ASCII 10
     */
    var $LF = 10;

    /**
     * Backspace, DEL, SUPR
     * ASCII 127
     */
    var $BS = 127;
    var $file = "";
    var $NR = "";

    /**
     * Tipo de documento fiscal
     * 
     */
    var $TD = "";
    var $X = "";
    var $Z = "";

    /**
     * Crea una instancia de HasarPHP
     */
    function HasarPHP($file = "factura.txt") {
        $this->file = "C:\\Tools\\" . $file;
        $this->PI = chr($this->PI);
        $this->DF = chr($this->DF);
        $this->TH = chr($this->TH);
        $this->FS = chr($this->FS);
        $this->LF = chr($this->LF);
        $this->BS = chr($this->BS);
        $this->TD = "A"; //Tipo documento Factura por defecto
        $this->NamePort = "p1";
        $this->X = "9" . $this->FS . "X" . $this->LF;
        $this->Z = "9" . $this->FS . "Z" . $this->LF;
        /* $fp = fopen($file, "w");
          $write = fputs($fp, "");
          fclose($fp); */
    }

    /**
     * Funcion que establece el serial o numero de registro de la impresora
     */
    function setSerial($NR) {
        $this->NR = $NR;
    }

    function getSerial() {
        return $this->NR;
    }

    /**
     * Funcion que establece el el puerto a utilizar
     * @param type String $namePort representa el puerto al que está conectada la impresora fiscal. Default p1<br/>
     *     p1 -> COM1, p2 -> COM2, ..., pn -> COMn
     */
    function setPort($namePort = "p1") {
        $this->NamePort = $namePort;
    }

    function getPort() {
        return $this->NamePort;
    }

    function setTipoDocumento($TD = "A") {
        $this->TD = $TD;
    }

    function getTipoDocumento() {
        return $this->TD;
    }

// Funcion que verifica si el puerto est� abierto y la conexi�n con la impresora
//Retorno: true si esta presente y false en lo contrario
    function checkPrinter() {
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

    /**
     * Funcion que envia un comando a la impresora
     * Parametro: Comando en cadena de caracteres ASCII
     * Retorno: true si el comando es valido y false en lo contrario
     */
    function sendCmd($cmd = "*") {

        /* $LineaComando = "comando.bat";
          $fp = fopen($LineaComando, "w");
          $sentencia = "C:\Tools\wspooler -{$this->getPort()} -z -c \"{$cmd}\"";
          $write = fputs($fp, $sentencia);
          fclose($fp);

          exec($LineaComando); */
        exec("cmd /C start C:\Tools\wspooler.exe -{$this->NamePort} -z -c {$cmd}");
    }

    /**
     * Funcion que verifica el estado y error de la impresora
     * StatusExtra - Consulta extra de estado
     */
    function statusExtra() {
        $this->sendCmd();
        $respuesta = file("C:\Tools\respuesta.ans", "w");
        $lineas = count($respuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $repStatusExtra = $respuesta[$i];
        }

        return $repStatusExtra;
    }

    /**
     * Funcion que ejecuta comandos desde un archivo de texto plano
     * Parametro: Ruta del archivo con extencion .txt .bat
     * Retorno: Cadena con numero de lineas procesadas en el archivo y estado y error
     * @param type $file
     * @return type
     */
    function sendFileCmd($file = "factura.txt") {
        /* $LineaComando = "Comando.bat";
          $fp2 = fopen("C:\Tools\\" . $LineaComando, "w");
          $sentencia = "C:\Tools\wspooler.exe -{$this->NamePort} -z -f C:\Tools\\{$file}";
          $write = fputs($fp2, $sentencia);
          fclose($fp2);
          exec("cmd /C start C:\Tools\\{$LineaComando}"); */

        exec("cmd /C start C:\Tools\wspooler.exe -{$this->NamePort} -z -f C:\Tools\\{$file}");

        $rep = "";
        $file_resp = explode(".", $file);
        $repuesta = file("C:\Tools\{$file_resp[0]}.ans");
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $resp = $repuesta[$i];
        }
        return $resp;
    }

    /**
     * Función que efectua el cierre parcial de caja generando un Reporte X
     * @param type String $file = "x.txt"
     */
    function partialCash($file = "x.txt") {
        #$this->sendCmd($this->X);
        $fp = fopen("C:\Tools\\{$file}", "w");
        $write = fputs($fp, $this->X);
        fclose($fp);
        exec("cmd /C start C:\Tools\wspooler.exe -{$this->NamePort} -z -f C:\Tools\\{$file}");
    }

    /**
     * Función que efectua el cierre de caja generando un Reporte Z
     * @param type String $file = "z.txt"
     */
    function closhCash($file = "z.txt") {
        #$this->sendCmd($this->Z);
        $fp = fopen("C:\Tools\\{$file}", "w");
        $write = fputs($fp, $this->Z);
        fclose($fp);
        exec("cmd /C start C:\Tools\wspooler.exe -{$this->NamePort} -z -f C:\Tools\\{$file}");
    }

//Funci�n que sube al PC un tipo de estado de  la impresora
//Par�metro: Tipo de estado en cadena Ejem: S1
//Retorno: Cadena de datos del estado respectivo
    function uploadStatusCmd($cmd = "*") {

        $LineaComando = "Comando.bat";
        $fp2 = fopen($LineaComando, "w");
        $sentencia = "C:\Tools\wspooler.exe -{$this->getPort()} -z -c \"{$cmd}\"";
        $write = fputs($fp2, $sentencia);
        fclose($fp2);

        exec($LineaComando);

        $repStErr = "";
        $repuesta = file("C:\Tools\respuesta.ans");
        $lineas = count($repuesta);
        for ($i = 0; $i < $lineas; $i++) {
            $repStErr = $repuesta[$i];
        }
        $this->StatusError = $repStErr;
        /*
          $rep = "";
          $repuesta = file('C:\IntTFHKA\Status.txt');
          $lineas = count($repuesta);
          for ($i = 0; $i < $lineas; $i++) {
          $rep = $repuesta[$i];
          }

          return $rep; */
    }

//Funci�n que sube al PC reportes X � Z de la impresora
//Par�metro: Tipo de reportes en cadena Ejem: U0X. Otro Ejem:   U3A000002000003
//Retorno: Cadena de datos del o los reporte(s)
    function uploadReportCmd($cmd = "") {

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