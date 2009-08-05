<?php

include 'classes.php';

/**
 * Singleton stellt Datenbankverbindung her und setzt Datenbankabfragen ab
 * In jeder Script Datei muss PersistenzManager::instance()->connect()
 * aufgerufen werden, bevor ein DB Zugriff erfolgen kann.
 */
class PersistencyManager
{
	private static $instance = NULL;
	private $connection;
	private $configFile = NULL;
	
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
		$fh = NULL;
		if (empty($configFile))
			$fh = fopen("../config/dbconfig.conf", "r");
		else
			$fh = fopen($configFile, "r");
			
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
	
	public function setConfig($file)
	{
		$this->configFile = $file;
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

function loadUser($name)
{
	if (empty($name))
		return FALSE;
	$sql = "SELECT id, name, password, role FROM benutzer b WHERE b.name=" . "'" . $name ."'";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return NULL;
	$row = $data[0];
	$benutzer = new Benutzer($row['name'], $row['password']);
	$benutzer->role = $row['role'];
	$benutzer->id = $row['id'];
	return $benutzer;	
}

function loadAllUser()
{
	$sql = "select id,name,role from benutzer";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return NULL;
	return $data;
}

function loadUserId($name)
{
	if (empty($name))
		return FALSE;
	$sql = "select id from benutzer where name="."'".$name."'";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return -1;
	$row = $data[0];
	return $row['id'];
}

/**
 * @param $user
 * @return unknown_type
 */
function saveUser($user)
{
	if (empty($user))
		return FALSE;
	$sql = "select name from benutzer b where b.name=" . "'" . $user->name . "'";
	$data = PersistencyManager::instance()->query($sql);
	if (is_array($data))
		return FALSE;
	$sql = "insert into benutzer(name, password, picture) values(" . 
					"'" . $user->name . 
					"'," . "'" . $user->password . "'" . ", NULL)";
	$data = PersistencyManager::instance()->query($sql);
	if (!$data)
		return FALSE; 
	return TRUE;
}

function timeStamp($dateTime)
{
	// String in Datum und Uhrzeit aufsplitten
	// (amerikanisches Datumsformat YYYY-MM-DD !!)
	$datetime = explode(" ", $dateTime);
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
	
	return mktime($hour, $min, $sec, $month, $day, $year);
}

function isTippExpired($spielId)
{
	if (empty($spielId))
		return TRUE;
	$sql = "select zeit from spiele where id=".$spielId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return TRUE;
	$curr_time_stamp = time();
	$spiel = $data[0];
		
	$spiel_time_stamp = timeStamp($spiel['zeit']);
	
	if ($curr_time_stamp < $spiel_time_stamp)
		return FALSE;
	return TRUE;
}

function loadSeasons()
{
	$sql = "select * from saisons";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
}

function saveSpieltag($nr, $saison)
{
	if (empty($nr) || empty($saison))
		return FALSE;
	$e = "";
	$sql = "insert into spieltage (saison_id, nr, datum) values (".$saison.",".$nr.","."'".$e."'".")";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return TRUE;
	return FALSE;
}

function loadSpieltag($nr)
{
	if (empty($nr))
		return FALSE;
	$sql = "select * from spieltage where nr=".$nr;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data[0];
}

function loadSpieltage()
{
	$sql = "select * from spieltage";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
}

function loadSpiele($spieltagId)
{
	if (empty($spieltagId))
		return FALSE;
	$sql = "select spiele.id as id, spiele.spieltag_id as spieltag_id, v1.name as t1, v2.name as t2,". 
	       "spiele.ergebnis as ergebnis, spiele.zeit as zeit from spiele ".
		   "inner join vereine v1 on v1.id = t1 ".
		   "inner join vereine v2 on v2.id = t2 ".
		   "where spiele.spieltag_id=".$spieltagId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
}

function loadSpiel($spielId)
{
	if (empty($spielId))
		return FALSE;
	$sql = "select t1, t2, ergebnis from spiele where id=".$spielId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data[0];
}

function loadSpielWithNames($spielId)
{
	if (empty($spielId))
		return FALSE;
	$sql = "select spiele.id as id, spiele.spieltag_id as spieltag_id, v1.name as t1, v2.name as t2,".
	       "spiele.ergebnis as ergebnis, spiele.zeit as zeit from spiele ".
		   "inner join vereine v1 on v1.id = t1 ".
		   "inner join vereine v2 on v2.id = t2 ".
		   "where spiele.id=".$spielId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data[0];
}

function saveSpiel($spieltagId, $heim, $ausw, $ergebnis, $zeit)
{
	if (empty($spieltagId) || empty($heim) || empty($ausw) ||empty($zeit))
		return FALSE;
	$sql = "insert into spiele (spieltag_id, t1, t2, ergebnis, zeit) values".
			"(".$spieltagId.",'".$heim."','".$ausw."','".$ergebnis."','".$zeit."')";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return TRUE;
	return FALSE;
}

function updateSpiel($spielId, $heim, $ausw, $erg, $zeit)
{
	if (empty($spielId))
		return FALSE;
	$sql = "update spiele set t1='".$heim."',t2='".$ausw."', ergebnis='".$erg."',zeit='".$zeit."' where id=".$spielId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return TRUE;
	return FALSE;
}

function loadTipp($spielId)
{
	$sql = "select * from tipp where spiel_id=".$spielId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data[0]; 
}

/**
 * @param $spielId
 * @param $benutzerId
 * @return unknown_type
 */
function loadTippFromUser($spielId, $benutzerId)
{
	if (empty($spielId) || empty($benutzerId))
		return FALSE;
	$sql = "select * from tipp where spiel_id=".$spielId." and user_id=".$benutzerId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data[0]; 
}
function loadTippAndErgebnis($userId)
{
	$sql = "select s.ergebnis, t.ergebnis as tipp, s.zeit from spiele s " .
		   "inner join tipp t on s.id = t.spiel_id ".
		   "where t.user_id=".$userId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
}

function loadAllTippsFromUser($benutzerId)
{
	$sql = "select * from tipp where user_id=".$benutzerId;
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
}

function saveTipp($userId, $spielId, $ergebnis)
{
	// Sobald ein Tipp mit einer Spielid in der Datenbank vorhanden ist,
	// wird ein update auf das Ergebnis ausgefŸhrt.
	
	if (loadTippFromUser($spielId, $userId)){
		$sql = "update tipp set ergebnis="."'".$ergebnis."'"." where spiel_id=".$spielId." and user_id=".$userId;
		$data = PersistencyManager::instance()->query($sql);
		if ($data)
			return TRUE;
	}
	else {
		$sql = "insert into tipp (user_id, spiel_id, ergebnis) values (" . 
			$userId . "," . $spielId . "," . "'" . $ergebnis . "'" . ")";
		$data = PersistencyManager::instance()->query($sql);
		if (!$data)
			return FALSE;		 
		return TRUE;
	}
}

function loadVereine()
{
	$sql = "select * from vereine";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data;
}

function loadVereinId($name)
{
	if (empty($name))
		return FALSE;
	$sql = "select * from vereine where name="."'".$name."'";
	$data = PersistencyManager::instance()->query($sql);
	if (!is_array($data))
		return FALSE;
	return $data[0]['id'];
}
?>