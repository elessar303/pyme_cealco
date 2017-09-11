<?php 
/*
class SessionManager{
	// codifica usando base64
	private static function encode($value)
	{
		return base64_encode($value);
	}
	//decodifica usando base64
	private static function decode($value)
	{
		return base64_decode($value);
	}
	// obtiene el valor asociado a key
	public static function get($key)
	{
		if (SessionManager::isDefined($key))
			return 	SessionManager::decode($_SESSION[SessionManager::encode($key)]);
		else
			return "";	
	}
	// setea el valor de key
	public static function set($key,$value)
	{
		$key=SessionManager::encode($key);
		$value=SessionManager::encode($value);
		$_SESSION[$key]=$value;
	}
	// devuelve true si esta definido
	public static function isDefined($key)
	{
		if (isset($_SESSION[SessionManager::encode($key)]))
			return true;
		else
			return false;
	}
	//hace un dump de la session
	public static function sessionDump()
	{
		if (isset($_SESSION))
			foreach($_SESSION as $key=>$val)
				echo SessionManager::decode($key),":",SessionManager::decode($val),"<br/>";			
	}
	//hace un dump de la session codificada
	public static function sessionDumpEncoded()
	{
		if (isset($_SESSION))
			foreach($_SESSION as $key=>$val)
				echo $key,":",$val,"<br/>";			
	}
//returna true si la session esta definida
	public static function is_set()
	{
		if (isset($_SESSION))
			return true;
		else
			return false;			
	}
}*/
?>
