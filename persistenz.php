<?php

include 'classes.php';

class PersistenzManager
{
	private static $instance = NULL;
	private $db = NULL;
	private $connection = NULL;
	
	private function __construct(){}
	private function __clone(){}
	
	public static function instance()
	{
		if (self::$instance == NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function connect($url, $dbname, $user, $pwd)
	{
		$this->connection = mysql_connect($url, $user, $pwd);
		if (!$this->connection)
			die('could not connect: ' . mysql_error());
			
		$this->db = mysql_select_db($dbname, $this->connection);
		if (!$this->db)
			die ('kein Zugriff auf ' . $dbname . " " . mysql_error());
		return true;
	}
	
	public function close()
	{
		if (!$this->connection)
			mysql_close($this->connection);
	}
	
	public function query($sql)
	{
		$result = mysql_query($sql, $this->connection);
		if (!result)
			die('Abfragefehler ' . mysql_error());
		if (result == TRUE)
			return TRUE;
		$array = array();
		while ($line = mysql_fetch_array($result)){
			array_push($array, $line);
		}
		return $array;
	}
}

class BenutzerManager
{
	private static $instance = NULL;
	private $persManager = NULL;
	
	private function __construct(){}
	private function __clone(){}
	
	public static function instance()
	{
		if (self::$instance == NULL){
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function setPersistenzManager($persManager)
	{
		if (!$persManager){
			$this->persManager = $persManager;
		}
	}
	
	public function loadBenutzer($name)
	{
		$sqlStatement = "select name, password from benutzer where name=" . $name;
		$data = $this->persManager->query($sqlStatement);
		//return new Benutzer($data['name'], $data['password']);
		return $data;
	}
	
	public function saveBenutzer($benutzer)
	{
		$sqlStatement = NULL;
		if (!$this->loadBenutzer($benutzer->name))
			$sqlStatement = ""; //update statement
		else
			$sqlStatement = "insert into benutzer(name, password, picture) values ('$benutzer->name','$benutzer->password','$benutzer->picture')";
			
		$ret = mysql_query($sqlStatement);
		if (!$ret)
			return false;
		return true;
	}
}

?>