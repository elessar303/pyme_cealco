<?php

class BDControlador {
	
	protected $conf = "";
	protected $conexion = "";
	protected $query = "";
	protected $resultSet = "";
	protected $transaccion = true;
	protected $ERROR;        
	protected $user = "";
	protected $pass = "";
	protected $host = "";
	protected $bd = "";
        protected $port = "";
        private   $driver = "mysql";

        function __construct() {
		//$this->conf  = new Configurador();	
	}
        
        public function getConf() {
            return $this->conf;
        }

        public function setConf($conf) {
            $this->conf = $conf;
        }

        public function getUser() {
            return $this->user;
        }

        public function setUser($user) {
            $this->user = $user;
        }

        public function getPass() {
            return $this->pass;
        }

        public function setPass($pass) {
            $this->pass = $pass;
        }

        public function getHost() {
            return $this->host;
        }

        public function setHost($host) {
            $this->host = $host;
        }

        public function getBd() {
            return $this->bd;
        }

        public function setBd($bd) {
            $this->bd = $bd;
        }

        public function getDiver() {
            return $this->driver;
        }

        public function setDiver($driver) {
            $this->driver = $driver;
        }

        public function getPort() {
            return $this->port;
        }

        public function setPort($port) {
            $this->port = $port;
        }
	
	public function conectar(){
		if($this->getUser() == ""){$this->setUser(DB_USUARIO);}
                if($this->getPass() == ""){$this->setPass(DB_CLAVE);}
                if($this->getHost() == ""){$this->setHost(DB_HOST);}
                if($this->getBd() == ""){$this->setBd(SELECTRA_CONF_PYME);}
                if($this->getPort() == ""){$this->setPort(3306);}
			
		$port = (int)$this->getPort();
                if($this->driver == "mysql"){
                    $this->conexion= new mysqli($this->getHost(),$this->getUser(),$this->getPass(),$this->getBd(),$port);
                    $this->conexion->set_charset("utf8");
                }
                else if($this->driver == "mssql"){
                    $this->conexion = mssql_connect($this->getHost(), $this->getUser(), $this->getPass());
                    mssql_select_db($this->getBd(), $this->conexion);
                }
		return $this->conexion;
	}
        
	public function conexionRemota($host,$user,$pass,$bd){
		$this->setUser($user);
		$this->setPass($pass);
		$this->setHost($host);
		$this->setBd($bd);
		//$this->conectar();	
		return $this->conexion;		
	}
	
	/**
	 * @return unknown
	 */
	public function getERROR() {
		return $this->ERROR;
	}
	
	/**
	 * @return unknown
	 */
	public function getResultSet() {
		return $this->resultSet;
	}
	
	/**
	 * @param unknown_type $ERROR
	 */
	public function setERROR($ERROR) {
		$this->ERROR = $ERROR;
	}
	
	/**
	 * @param unknown_type $resultSet
	 */
	public function setResultSet($resultSet) {
		$this->resultSet = $resultSet;
	}

	
	public function setQuery($query){
		$this->query =$query;
	}
	
	public function getQuery(){
		return $this->query;
	}
	
	public function ejecutarSql(){
		$this->conectar();		
		//$this->conexion->set_charset("utf8");
		//mysqli
                if($this->driver == "mysql"){
                    $this->resultSet = $this->conexion->query($this->query);
                    if($this->conexion->error!=""){
                        try {   
                            throw new Exception("MySQL error ".$this->conexion->error." <br> Query:<br> ".$this->getQuery().", ".$this->conexion->error);   
                        } catch(Exception $e ) {
                                    $this->desconectar();
                            echo "Error No: ".$e->getCode(). " <br> ";
                            echo "Error Message: ". $e->getMessage() . "<br >";
                            echo "Stack Trace:<br> ".nl2br($e->getTraceAsString());
                            return false;
                        }
                    }
                }               
                else if($this->driver == "mssql"){
                    $this->resultSet = mssql_query($this->query);//@
                    if($this->resultSet==false){
                        try {   
                            throw new Exception("MSSQL error ".mssql_get_last_message()." <br> Query:<br> ".$this->getQuery());   
                        } catch(Exception $e ) {
                            $this->desconectar();
                            echo "Error No: ".$e->getCode(). " <br> ";
                            echo "Error Message: ". $e->getMessage() . "<br >";
                            echo "Stack Trace:<br> ".nl2br($e->getTraceAsString());
                            return false;
                        }
                    }
                }		
		$this->desconectar();
		return $this->resultSet;
	}	
	
	public function ejecutaInstruccion(){
		//echo $this->query; die();
		//$this->conexion->set_charset("utf8");
                if($this->driver == "mysql"){                	
                    $this->resultSet = $this->conexion->query($this->query);                    
                    if($this->conexion->error!=""){                                    	
                            throw new Exception("MySQL error ".$this->conexion->error." <br> Query:<br> ".$this->getQuery().", ".$this->conexion->error);   
                    }
                }               
                else if($this->driver == "mssql"){
                    $this->resultSet = mssql_query($this->query);//@
                    if($this->resultSet==false){
                            throw new Exception("MSSQL error ".mssql_get_last_message()." <br> Query:<br> ".$this->getQuery());   
                    }
                }
		
		return $this->resultSet;
	}
        
	public function multiQuery(){
		$this->conexion->multi_query($this->getQuery());
		if($this->conexion->error!=""){
			throw new Exception("MySQL error ".$this->conexion->error." <br> Query:<br> ".$this->getQuery().", ".$this->conexion->error);   
		}
		return $this->resultSet;
	}
	
        public function storeResult(){
		return $this->conexion->store_result();            
        }
        
	public function nextResult(){
		return $this->conexion->next_result();
	}
	
	public function useResult(){		
		return $this->conexion->use_result();
	}
	
	public function getConexion(){
		return $this->conexion;
	}
	
	public function commit(){
		$this->conexion->commit();
	}
	
	public function rollback(){
		$this->conexion->rollback();
	}
	
	public function autocommit($autocomm){
		$this->conexion->autocommit($autocomm);
	}
	
	public function fetch($resultado){
		//$resultado->fetch_row();
		//MYSQLI_NUM
		//MYSQLI_BOTH
		//return $this->resultSet->fetch_array(MYSQLI_ASSOC);
            $Row = null;
            if($this->driver == "mysql")
                $Row = $resultado->fetch_array(MYSQLI_ASSOC);
            else if($this->driver == "mssql")
                $Row = mssql_fetch_array($resultado);
            
            return $Row;
	}
	
	public function fetchAssoc($resultado){
		return $resultado->fetch_assoc();
	}
	
	public function numero_filas($resultado){
            $numRows = 0;
            if($this->driver == "mysql")
                $numRows = $resultado->num_rows;
            else if($this->driver == "mssql")
                $numRows = mssql_num_rows($resultado);
            
            return $numRows;
	}
		
	public static function fetchFields($result){
		return $result->fetch_fields();
	}
	
	public static function fetchField($result){
		return $result->fetch_field();
	}
	
	public static function numFields($result){
		return $result->field_count;
	}	
	
	public static function fetchFieldDirecto($result,$ind){
		return $result->fetch_field_direct($ind);
	}
	
	public function desconectar(){
            if($this->driver == "mysql")
                $this->conexion->close();
            else if($this->driver == "mssql")
                mssql_close($this->conexion);
	}
	
	
	public function lastId(){
		return $this->conexion->insert_id;
	}
	
	
	/**
	 * @return unknown
	 */
	public function getTransaccion() {
		return $this->transaccion;
	}
	
	/**
	 * @param unknown_type $transaccion
	 */
	public function setTransaccion($transaccion) {
		$this->transaccion = $transaccion;
	}
	
	

}

?>