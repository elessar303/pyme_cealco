<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClassDBMaker
 *
 * @author Sebastian
 */
require_once '../BDControlador.php';

class ClassDBMaker {
    private $table;
    private $conn;
    
    public function Init($bd,$user,$pass,$table) {
        $this->conn = new BDControlador();
        $this->conn->conexionRemota("localhost", $user, $pass, $bd);
        $this->conn->conectar();
        $this->table = $table;
    }
    
    public function makeWhatIAm(){
        $classNameAux = explode("_", $this->table);
        $className = "";
        foreach ($classNameAux as $value) {
            $className .= strtoupper(substr($value, 0,1)).substr($value, 1);
        }
        
        if(!file_exists("../".$className.".php")){
            if($file = fopen("../".$className.".php", "a")){
                $this->conn->setQuery("SHOW COLUMNS FROM ".$this->table);
                $result = $this->conn->ejecutarSql();
                fwrite($file, "<?php\n class ".$className."{\n");
                while ( $item = $this->conn->fetch($result)) {
                    $fieldNameAux = explode("_", $item['Field']);
                    $fieldName = "";
                    $ind = 0;
                    foreach ($fieldNameAux as $value) {
                        if($ind == 0){
                            $fieldName .= strtolower(substr($value, 0,1)).substr($value, 1);
                        }
                        else{
                            $fieldName .= strtoupper(substr($value, 0,1)).substr($value, 1);
                        }
                        $ind++;
                    }
                    $matches[] = $item;
                    //$item['Field'] = $fieldName;
                    fwrite($file, "\tprivate $".$fieldName.";\n");
                    if($item['Key'] == "PRI"){
                        fwrite($file, "\tpublic $"."primaryKeyField = '".$fieldName."';\n");
                        fwrite($file, "\tpublic $"."primaryKeyFieldInDb = '".$item['Field']."';\n");
                    }
                }
                fwrite($file, "\n // FUNCTIONS\n");
                $this->conn->setQuery("SHOW COLUMNS FROM ".$this->table);
                $result = $this->conn->ejecutarSql();
                while ( $item = $this->conn->fetch($result)) {
                    $fieldNameAux = explode("_", $item['Field']);
                    $fieldNameFunc = "";
                    $fieldName = "";
                    $ind = 0;
                    foreach ($fieldNameAux as $value) {
                        $fieldNameFunc .= strtoupper(substr($value, 0,1)).substr($value, 1);
                        if($ind == 0){
                            $fieldName .= strtolower(substr($value, 0,1)).substr($value, 1);
                        }
                        else{
                            $fieldName .= strtoupper(substr($value, 0,1)).substr($value, 1);
                        }
                        $ind++;
                    }
                    
                    fwrite($file, "\tpublic function get".$fieldNameFunc."(){\n");
                    fwrite($file, "\t\treturn $"."this->".$fieldName.";\n\t}\n");
                    
                    fwrite($file, "\tpublic function set".$fieldNameFunc."($".$fieldName."){\n");
                    fwrite($file, "\t\t$"."this->".$fieldName." = $".$fieldName.";\n\t}\n");
                }
                fwrite($file, "}\n?>");
                fclose($file);
            }
        }
    }
    
    public function makeWhatIDo(){
        $classNameAux = explode("_", $this->table);
        $className = "";
        foreach ($classNameAux as $value) {
            $className .= strtoupper(substr($value, 0,1)).substr($value, 1);
        }
        
        if(!file_exists("../Accion".$className.".php")){
            if($file = fopen("../Accion".$className.".php", "a")){
                $this->conn->setQuery("SHOW COLUMNS FROM ".$this->table);
                $result = $this->conn->ejecutarSql();
                fwrite($file, "<?php\nrequire_once('".$className.".php');\n");
                fwrite($file, "require_once('modelos/Modelo".$className.".php');\n");
                fwrite($file, "class Accion".$className."{\n");
                fwrite($file, "\tprivate $"."modelo = null;\n");
                fwrite($file, "\tprivate $"."obj".$className." = null;\n");
                fwrite($file, "\n // FUNCTIONS\n");
                //CREACION DE FUNCION SAVE
                fwrite($file, "\tpublic function save($"."request){\n");
                fwrite($file, "\t\t$"."this->modelo = new Modelo".$className."();\n");
                fwrite($file, "\t\t$"."this->obj".$className." = new ".$className."();\n");
                
		while ( $item = $this->conn->fetch($result)) {
                    $fieldNameAux = explode("_", $item['Field']);
                    $fieldNameFunc = "";
                    $ind = 0;
                    foreach ($fieldNameAux as $value) {
                        $fieldNameFunc .= strtoupper(substr($value, 0,1)).substr($value, 1);                        
                    }
                    fwrite($file, "\t\t$"."this->obj".$className."->set".$fieldNameFunc."((isset($"."request[\"".$item['Field']."\"]))?$"."request[\"".$item['Field']."\"]:'');\n");
                }
                fwrite($file, "\t\t$"."response = $"."this->modelo->save($"."this->obj".$className.",$"."request);\n");
                fwrite($file, "\t\treturn $"."response;\n");
                fwrite($file, "\t}\n");
                
                //CREACION DE FUNCION PARA OBTENER UN REGISTRO
                fwrite($file, "\tpublic function get_one($"."request){\n");
                fwrite($file, "\t\t$"."this->modelo = new Modelo".$className."();\n");
                fwrite($file, "\t\t$"."this->obj".$className." = new ".$className."();\n");
                $result = $this->conn->ejecutarSql();
		while ( $item = $this->conn->fetch($result)) {
                    $fieldNameAux = explode("_", $item['Field']);
                    $fieldNameFunc = "";
                    $ind = 0;
                    foreach ($fieldNameAux as $value) {
                        $fieldNameFunc .= strtoupper(substr($value, 0,1)).substr($value, 1);                        
                    }
                    if($item['Key'] == "PRI" && $item['Extra'] == "auto_increment"){
                        fwrite($file, "\t\t$"."this->obj".$className."->set".$fieldNameFunc."($"."request[\"".$item['Field']."\"]);\n");
                    }
                }
                fwrite($file, "\t\t$"."response = $"."this->modelo->getOne($"."this->obj".$className.");\n");
                fwrite($file, "\t\treturn $"."response;\n");
                fwrite($file, "\t}\n");
                
                //CREACION DE FUNCION PARA OBTENER LISTA DE REGISTROS
                fwrite($file, "\tpublic function get_list($"."request){\n");
                fwrite($file, "\t\t$"."this->modelo = new Modelo".$className."();\n");
                fwrite($file, "\t\t$"."this->obj".$className." = new ".$className."();\n");
                fwrite($file, "\t\t$"."response = $"."this->modelo->getList($"."request);\n");
                fwrite($file, "\t\treturn $"."response;\n");
                fwrite($file, "\t}\n");
                
                
                fwrite($file, "}\n?>");
                fclose($file);
            }
        }
        
    }
    
    public function makeHowIDoIt(){
        $classNameAux = explode("_", $this->table);
        $className = "";
        foreach ($classNameAux as $value) {
            $className .= strtoupper(substr($value, 0,1)).substr($value, 1);
        }
        
        if(!file_exists("../modelos/Modelo".$className.".php")){
            if($file = fopen("../modelos/Modelo".$className.".php", "a")){
                $this->conn->setQuery("SHOW COLUMNS FROM ".$this->table);
                $result = $this->conn->ejecutarSql();
                fwrite($file, "<?php\n require_once('../BDControlador.php');\n");
                fwrite($file, " require_once('../".$className.".php');\n");
                fwrite($file, "class Modelo".$className."{\n");
                fwrite($file, "\tprivate $"."bdControlador = null;\n");
                fwrite($file, "\n // FUNCTIONS\n");
                fwrite($file, "\tpublic function save(".$className." &$".$className.",$"."request){\n");
                fwrite($file, "\t\t$"."lastId = 0;\n");
                fwrite($file, "\t\t$"."this->bdControlador = new BDControlador();\n\t\ttry{\n\t\t\t$"."this->bdControlador->conectar();\n\t\t\t$"."this->bdControlador->autocommit(FALSE);\n");
                fwrite($file, "\t\t\t$"."saveQuery = '';\n");
                $queryAdd = "insert into ".$this->table." (";
                $queryAddValues = " values (";
                $queryUpdate = "update ".$this->table."  set ";
                $queryWhereUpdate = "";
                
                $idField = "";
                
                while ( $item = $this->conn->fetch($result)) {
                    
                    $fieldNameAux = explode("_", $item['Field']);
                    $fieldNameFunc = "";
//                    $ind = 0;
//                    foreach ($fieldNameAux as $value) {
//                        if($ind == 0){
//                            $fieldName .= strtolower(substr($value, 0,1)).substr($value, 1);
//                        }
//                        else{
//                            $fieldName .= strtoupper(substr($value, 0,1)).substr($value, 1);
//                        }
//                        $ind++;
//                    }
                    foreach ($fieldNameAux as $value) {
                        $fieldNameFunc .= strtoupper(substr($value, 0,1)).substr($value, 1);                        
                    }
                    $queryAdd .= $item['Field'].",";
                    if($item['Key'] == "PRI" && $item['Extra'] == "auto_increment"){
                        $queryAddValues .= "NULL,";
                        $idField = $item['Field'];
                    }
                    else{
                        $queryAddValues .= "'{"."$".$className."->get".$fieldNameFunc."()}',";
                    }
                    $queryUpdate .= $item['Field']." = '{"."$".$className."->get".$fieldNameFunc."()}',";
                    if($item['Key'] == "PRI"){
                        $queryWhereUpdate = " where ".$item['Field']." = '{"."$".$className."->get".$fieldNameFunc."()}'";
                    }                    
                }
                $queryAdd = substr($queryAdd, 0,-1).")";
                $queryAddValues = substr($queryAddValues, 0,-1).")";
                $queryUpdate = substr($queryUpdate, 0,-1);
                
                fwrite($file, "\t\t\tif($"."request[\"operacion\"] == 'add'){\n");
                fwrite($file, "\t\t\t\t$"."saveQuery = \"".$queryAdd.$queryAddValues."\";\n");
                fwrite($file, "\t\t\t}\n");
                fwrite($file, "\t\t\telse if($"."request[\"operacion\"] == 'update'){\n");
                fwrite($file, "\t\t\t\t$"."saveQuery = \"".$queryUpdate.$queryWhereUpdate."\";\n");
                fwrite($file, "\t\t\t}\n");
                
                fwrite($file, "\t\t\t$"."this->bdControlador->setQuery($"."saveQuery);\n");
                fwrite($file, "\t\t\t$"."this->bdControlador->ejecutaInstruccion();\n");
                fwrite($file, "\t\t\tif($"."request[\"operacion\"] == 'add'){\n");
                fwrite($file, "\t\t\t\t$"."lastId =$"."this->bdControlador->lastId();\n");
                fwrite($file, "\t\t\t}\n");
                fwrite($file, "\t\t\t$"."this->bdControlador->commit();\n");
                fwrite($file, "\t\t\treturn  Array('success' => true,'mensaje' =>'OK','".$idField."' => $"."lastId);\n");
                fwrite($file, "\t\t}\n");
                fwrite($file, "\t\tcatch(Exception $"."ex){\n");
                fwrite($file, "\t\t\t$"."this->bdControlador->rollback();\n");
                fwrite($file, "\t\t\t$"."this->bdControlador->desconectar();\n");
                fwrite($file, "\t\t\treturn  Array('success' => false,'mensaje' =>'NO_OK','error'=>$"."ex->getMessage());\n");
                fwrite($file, "\t\t}\n");
                fwrite($file, "\t}\n");
                
                //CREACION DE FUNCION PARA OBTENER UN REGISTRO
                $result = $this->conn->ejecutarSql();
                fwrite($file, "\tpublic function getOne(".$className." &$".$className."){\n");
                fwrite($file, "\t\t$"."this->bdControlador = new BDControlador();\n\t\ttry{\n\t\t\t$"."this->bdControlador->conectar();\n");
                fwrite($file, "\t\t\t$"."query = '';\n");
                
                $query = "select ";
                $whereIdField = "";
                while ( $item = $this->conn->fetch($result)) {
                    $query .= $item['Field'].",";
                    if($item['Key'] == "PRI" && $item['Extra'] == "auto_increment"){
                        $whereIdField = $item['Field']." = '{"."$".$className."->primaryKeyFieldInDb}'";;
                    }  
                }
                $query = substr($query, 0,-1)." from ".$this->table." where ".$whereIdField;
                
                fwrite($file, "\t\t\t\t$"."query = \"".$query."\";\n");
                
                fwrite($file, "\t\t\t$"."this->bdControlador->setQuery($"."query);\n");
                fwrite($file, "\t\t\t$"."result = $"."this->bdControlador->ejecutaInstruccion();\n");
                fwrite($file, "\t\t\t$"."num = $"."this->bdControlador->numero_filas($"."result);\n");
                fwrite($file, "\t\t\t$"."matches = Array();\n");
                fwrite($file, "\t\t\twhile ($"."item = $"."this->bdControlador->fetch($"."result)){\n");
                fwrite($file, "\t\t\t\t$"."matches[] = $"."item;\n\t\t\t}\n");
		
                fwrite($file, "\t\t\treturn  Array('success' => true,'totalCount' => $"."num,'matches' => $"."matches);\n");
                fwrite($file, "\t\t}\n");
                fwrite($file, "\t\tcatch(Exception $"."ex){\n");
                fwrite($file, "\t\t\t$"."this->bdControlador->desconectar();\n");
                fwrite($file, "\t\t\treturn  Array('success' => false,'mensaje' =>'NO_OK','error'=>$"."ex->getMessage());\n");
                fwrite($file, "\t\t}\n");
                fwrite($file, "\t}\n");
                
                
                //CREACION DE FUNCION PARA OBTENER LISTA DE REGISTROS
                $result = $this->conn->ejecutarSql();
                fwrite($file, "\tpublic function getList($"."request){\n");
                fwrite($file, "\t\t$"."this->bdControlador = new BDControlador();\n\t\ttry{\n\t\t\t$"."this->bdControlador->conectar();\n");
                fwrite($file, "\t\t\t$"."query = '';\n");
                
                $query = "select ";
                $andWhere = "";
                while ( $item = $this->conn->fetch($result)) {
                    $query .= $item['Field'].",";
                    $andWhere .= " and (".$item['Field']." like '%{"."$"."request}%') or ";
                }
                $query = substr($query, 0,-1)." from ".$this->table." where 1";
                $andWhere = substr($andWhere, 0,-3);
                
                fwrite($file, "\t\t\t\t$"."query = \"".$query."\";\n");
                
                fwrite($file, "\t\t\tif($"."request['patronbuscar'] != ''){\n");
                fwrite($file, "\t\t\t\t$"."query .= \"".$andWhere."\";\n");
                fwrite($file, "\t\t\t}\n");
                
                fwrite($file, "\t\t\t$"."this->bdControlador->setQuery($"."query);\n");
                fwrite($file, "\t\t\t"."$"."result = $"."this->bdControlador->ejecutaInstruccion();\n");
                fwrite($file, "\t\t\t$"."num = $"."this->bdControlador->numero_filas($"."result);\n");
                fwrite($file, "\t\t\t$"."matches = Array();\n");
                fwrite($file, "\t\t\twhile ($"."item = $"."this->bdControlador->fetch($"."result)){\n");
                fwrite($file, "\t\t\t\t$"."matches[] = $"."item;\n\t\t\t}\n");
		
                fwrite($file, "\t\t\t$"."this->bdControlador->desconectar();;\n");
                fwrite($file, "\t\t\treturn  Array('success' => true,'totalCount' => $"."num,'matches' => $"."matches);\n");
                fwrite($file, "\t\t}\n");
                fwrite($file, "\t\tcatch(Exception $"."ex){\n");
                fwrite($file, "\t\t\t$"."this->bdControlador->desconectar();\n");
                fwrite($file, "\t\t\treturn  Array('success' => false,'mensaje' =>'NO_OK','error'=>$"."ex->getMessage());\n");
                fwrite($file, "\t\t}\n");
                fwrite($file, "\t}\n");
                
                fwrite($file, "}\n?>");
                fclose($file);
            }
        }        
    }
}

$ob = new ClassDBMaker();
$ob->Init("administrativo", "root", "armadillo01", $_GET['table']);
$ob->makeWhatIAm();
$ob->makeWhatIDo();
$ob->makeHowIDoIt();