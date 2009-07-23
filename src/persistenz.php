<?php

include 'classes.php';

class PersistenzManager
{
	private static $instance = NULL;
	//private $db = NULL;
	private $connection;
	
	private function __construct(){}
	private function __clone(){}
	
	public static function &instance()
	{
		if (self::$instance == NULL){
			self::$instance = new self;
			//self::$instance->connect();
		}
		return self::$instance;
	}
	
	public function connect()
	{
		$url = "127.0.0.1:3306";
		$this->connection = mysql_connect($url, "root", "");
		if (!$this->connection)
			die('could not connect: ' . mysql_error());
			
		$db = mysql_select_db("bltippdb", $this->connection);
		if (!$db)
			die ('kein Zugriff auf ' . $dbname . " " . mysql_error());
		return TRUE;
	}	
	public function close()
	{
		if (!$this->connection)
			if (is_resource($this->connection))
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
	$sql = "SELECT name, password, role FROM benutzer b WHERE b.name=" . "'" . $name ."'";
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return NULL;
	$row = $data[0];
	$benutzer = new Benutzer($row['name'], $row['password']);
	$benutzer->role = $row['role'];
	return $benutzer;	
}

function loadBenutzerId($name)
{
	$sql = "select id from benutzer where name="."'".$name."'";
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return -1;
	$row = $data[0];
	return $row['id'];
}

function saveBenutzer($benutzer)
{
	$sql = "select name from benutzer b where b.name=" . "'" . $benutzer->name . "'";
	$data = PersistenzManager::instance()->query($sql);
	if (is_array($data))
		return FALSE;
	$sql = "insert into benutzer(name, password, picture) values(" . 
					"'" . $benutzer->name . 
					"'," . "'" . $benutzer->password . "'" . ", NULL)";
	$data = PersistenzManager::instance()->query($sql);
	if (!$data)
		return FALSE; 
	return TRUE;
}

function loadSpieltage()
{
	$sql = "select * from spieltage";
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
}

function loadSpiele($spieltagNr)
{
	$sql = "select id, t1, t2, ergebnis from spiele where spieltag_id=".$spieltagNr;
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
}

function loadSpiel($spielId)
{
	$sql = "select t1, t2, ergebnis from spiele where id=".$spielId;
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data[0];
}

function loadTipp($spielId)
{
	$sql = "select * from tipp where spiel_id=".$spielId;
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data[0]; 
}

function saveTipp($userId, $spielId, $ergebnis)
{
	// Sobald ein Tipp mit einer Spielid in der Datenbank vorhanden ist,
	// wird ein update auf das Ergebnis ausgefŸhrt.
	
	if (loadTipp($spielId)){
		$sql = "update tipp set ergebnis="."'".$ergebnis."'"." where spiel_id=".$spielId;
		$data = PersistenzManager::instance()->query($sql);
		if ($data)
			return TRUE;
	}
	else {
		$sql = "insert into tipp (user_id, spiel_id, ergebnis) values (" . 
			$userId . "," . $spielId . "," . "'" . $ergebnis . "'" . ")";
		$data = PersistenzManager::instance()->query($sql);
		if (!$data)
			return FALSE;		 
		return TRUE;
	}
}
?>