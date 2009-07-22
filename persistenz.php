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
		
		if (!$result)
			die('Abfragefehler ' . mysql_error());
		if (!is_resource($result))
			return TRUE;	
		if (mysql_num_rows($result) == 0)
			return FALSE;
		
		$array = array();
		while ($row = mysql_fetch_assoc($result)){
			array_push($array, $row);
		}		
		return $array;
	}
}

function loadBenutzer($name)
{
	$sqlStatement = "SELECT name, password FROM benutzer b WHERE b.name=" . "'" . $name ."'";
	$data = PersistenzManager::instance()->query($sqlStatement);
	$row = $data[0];
	return new Benutzer($row['name'], $row['password']);	
}

function saveBenutzer($benutzer)
{
	$sqlStatement = "select name from benutzer b where b.name=" . "'" . $benutzer->name . "'";
	$data = PersistenzManager::instance()->query($sqlStatement);
	if (is_array($data))
		return FALSE;
	$sqlStatement = "insert into benutzer(name, password, picture) values(" . 
					"'" . $benutzer->name . 
					"'," . "'" . $benutzer->password . "'" . ", NULL)";
	$data = PersistenzManager::instance()->query($sqlStatement);
	if (!$data)
		return FALSE; 
	return TRUE;
}
?>