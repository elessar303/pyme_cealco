<?php

class SpoolerConfDB {

    private $dirDBF;
    private $nameDBF;
    private $numReg;
    private $registros;
    private $REFINT;
    private $NUMDOC;
    private $TIPODOC;
    private $FSTATUS;

    function __construct() {
        $this->REFINT = array();
        $this->NUMDOC = array();
        $this->TIPODOC = array();
        $this->FSTATUS = array();
        $this->registros = array();
        $this->setDirDBF();
        $this->setNameDBF();
    }

    function setDirDBF($dir_dbf="C:\\PRINTSPOOL\\") {
        $this->dirDBF = $dir_dbf;
    }

    function setNameDBF($name_dbf="datos3.dbf") {
        $this->nameDBF = $name_dbf;
    }

    function getPathDBF() {
        return $this->getDirDBF() . $this->getNameDBF();
    }

    function getArrayReferenciaInterna() {
        return $this->REFINT;
    }

    function getArrayNumeroDocumento() {
        return $this->NUMDOC;
    }

    function getArrayTipoDocumento() {
        return $this->TIPODOC;
    }

    private function getDirDBF() {
        return $this->dirDBF;
    }

    private function getNameDBF() {
        return $this->nameDBF;
    }

    function getArrayFStatus() {
        return $this->FSTATUS;
    }

    function openDBF() {
        return dbase_open($this->dirDBF . $this->nameDBF, 0) or die("¡Error! No se pudo abrir el archivo de base de datos dbase");
    }

    function obtenerNumRegistrosDBF() {
        return $this->numReg; #dbase_numrecords($this->openDBF());
    }

    private function waitToWriteDBF() {
        for ($i = 0; $i < 10000; $i++)
            for ($j = 0; $j < 10000; $j++)
                ;
    }

    function cargarArraysRegistrosDBF() {
        $this->waitToWriteDBF();
        $db = dbase_open($this->dirDBF . $this->nameDBF, 0) or die("¡Error! No se pudo abrir el archivo de base de datos dbase"); #$this->openDBF();

        if ($db) {
            $this->numReg = dbase_numrecords($db);
            for ($i = 1; $i <= $this->numReg; $i++) {
                $fila = dbase_get_record_with_names($db, $i);

                /* $this->REFINT[$i] = (string) $fila['REFINT'];
                  $this->NUMDOC[$i] = (string) $fila['NUMDOC'];
                  $this->TIPODOC[$i] = (string) $fila['TIPODOC'];
                  $this->FSTATUS[$i] = (string) $fila['FSTATUS']; */

                $this->registros['REFINT'][$i] = $fila['REFINT'];
                $this->registros['NUMDOC'][$i] = $fila['NUMDOC'];
                $this->registros['TIPODOC'][$i] = $fila['TIPODOC'];
                $this->registros['FSTATUS'][$i] = $fila['FSTATUS'];
                $this->registros['NROZ'][$i] = $fila['NROZ'];
                $this->registros['IMPSERIAL'][$i] = $fila['IMPSERIAL'];
            }
        }
        dbase_close($db);
    }

    /**
     * @return mixed
     */
    function obtenerUltimoRegistroDBF() {
        #$this->cargarArraysRegistrosDBF();
        #return array('REFINT' => $this->registros['REFINT'][$this->numReg], 'NUMDOC' => $this->registros['NUMDOC'][$this->numReg], 'TIPODOC' => $this->registros['TIPODOC'][$this->numReg], 'FSTATUS' => $this->registros['FSTATUS'][$this->numReg], 'NROZ' => $this->registros['NROZ'][$this->numReg], 'IMPSERIAL' => $this->registros['IMPSERIAL'][$this->numReg]);
        $db = dbase_open($this->getPathDBF(), 0) or die("¡Error! No se pudo abrir el archivo de base de datos dbase");
        $this->numReg = dbase_numrecords($db);
        return dbase_get_record_with_names($db, $this->numReg);
    }

}

?>
