<?php

include 'classes.php';

/**
 * Singleton stellt Datenbankverbindung her und setzt Datenbankabfragen ab
 * In jeder Script Datei muss PersistenzManager::instance()->connect()
 * aufgerufen werden, bevor ein DB Zugriff erfolgen kann.
 */
class PersistenzManager
{
	private static $instance = NULL;
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
	
	private function readConfig()
	{
		$fh = fopen("../config/dbconfig.conf", "r");
		$config_arr = array();
		if ($fh != FALSE){
			while (!feof($fh)){
				$line = fgets($fh, 80);
				$pair = explode("=", $line);
				$key = ltrim(rtrim($pair[0]));
				$value = ltrim(rtrim($pair[1]));
				$config_arr[$key] = $value;
			}
			fclose($fh);
		}
		return $config_arr;
	}
	
	public function connect()
	{
		$config = $this->readConfig();
		$url = $config['url'];
		$this->connection = mysql_connect($config['url'], $config['user'], $config['pwd']);
		if (!$this->connection)
			die('could not connect: ' . mysql_error());
			
		$db = mysql_select_db($config['db'], $this->connection);
		if (!$db)
			die ('kein Zugriff auf ' . $config['db'] . " " . mysql_error());
		return TRUE;
	}
	
	/**
	 * Muss nicht explizit aufgerufen werden.
	 * DB Verbindungen existieren nicht Ÿber Scriptgrenzen hinweg.
	 */ 
	 
	public function close()
	{
		if (!$this->connection)
			if (is_resource($this->connection))
				mysql_close($this->connection);
	}
	
	/**
	 * Absetzen einer Datenbankabfrage.
	 * RŸckgabe von SELECT, INSERT, UPDATE und DELETE berŸcksichtigen !!
	 */
	public function query($sql)
	{
		$result = mysql_query($sql, $this->connection);
		
		if (!$result)
			die('Abfragefehler ' . mysql_error());
		if (!is_resource($result))  // INSERT, UPDATE, DELETE
			return TRUE;	
		if (mysql_num_rows($result) == 0)
			return FALSE;
		// Ergebnismenge des SELECT Befehls
		$array = array();
		while ($row = mysql_fetch_assoc($result)){
			array_push($array, $row);
		}		
		return $array;
	}
}

function loadBenutzer($name)
{
	$sql = "SELECT id, name, password, role FROM benutzer b WHERE b.name=" . "'" . $name ."'";
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return NULL;
	$row = $data[0];
	$benutzer = new Benutzer($row['name'], $row['password']);
	$benutzer->role = $row['role'];
	$benutzer->id = $row['id'];
	return $benutzer;	
}

function loadAlleBenutzer()
{
	$sql = "select id,name,role from benutzer";
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return NULL;
	return $data;
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

function isTippExpired($spielId)
{
	$sql = "select zeit from spiele where id=".$spielId;
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return TRUE;
	$curr_time_stamp = time();
	$spiel = $data[0];
	
	// String in Datum und Uhrzeit aufsplitten
	// (amerikanisches Datumsformat YYYY-MM-DD !!)
	$datetime = explode(" ", $spiel['zeit']);
	$date_str = $datetime[0];
	$time_str = $datetime[1];
	
	$date = explode("-", $date_str);
	$time = explode(":", $time_str);
	
	$year = $date[0];
	$day = $date[2];
	$month = $date[1];
	
	$hour = $time[0];
	$min = $time[1];
	$sec = $time[2];
	
	$spiel_time_stamp = mktime($hour, $min, $sec, $month, $day, $year);
	
	if ($curr_time_stamp < $spiel_time_stamp)
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

function loadTipps($benutzerId)
{
	$sql = "select * from tipp where user_id=".$benutzerId;
	$data = PersistenzManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
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