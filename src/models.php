<?php
class GameDayTable
{
	private $data = array(array());
	private $header = array("","Team 1","","Team 2","Ergebnis","Tipp");
	private $columns = 0;
	private $rows = 0;
	
	public function __construct($userId, $gameDayId)
	{
		$this->columns = sizeof($header);
		$this->load($userId, $gameDayId);
	}
	
	private function load($userId, $gameDayId)
	{
		if (empty($userId) || !is_numeric($userId))
			return FALSE;
		if (empty($gameDayId) || !is_numeric($userId))
			return FALSE;
		$games = loadSpiele($gameDayId);
		$this->rows = sizeof($games);
		for ($i = 0; $i < sizeof($games); $i++){
			$this->fillRow($this->data[$i], $games[$i], $userId);
		}
	}
	
	private function fillRow($row, $game, $userId)
	{
		$row[0] = $game['id'];
		$row[1] = "<img src=\"".$game['t1w']."\"/>";
		$row[2] = $game['t1'];
		$row[3] = "<img src=\"".$game['t2w']."\"/>";
		$row[4] = $game['t2'];
		$row[5] = $game['ergebnis'];
		
		$tipp = loadTippFromUser($game['id'], $userId);
		$str = "";
		if (!isTippExpired($game['id'])){
			$str = "<input type=\"text\" size=\"5\" maxlength=\"5\"".
				   " name=\"tippergebnis[]\" />";
			if ($tipp != FALSE){
				$str = "<input type=\"text\" size=\"5\" maxlength=\"5\"".
					   " name=\"tippergebnis[]\" value=\"".$tipp['ergebnis']."\" />";
			}
		}
		else{
			$str = "<input type=\"text\" size=\"5\" maxlength=\"5\"".
				   " name=\"tippergebnis[]\" value=\"".$tipp['ergebnis']."\" readonly />";
		}
		$row[5] = $str;
	}
	
	public function get($row, $col)
	{
		return $this->data[$row][$col];
	}
	
	public function columns()
	{
		return $this->columns;
	}
	
	public function rows()
	{
		return $this->rows;	
	}
	
	public function header()
	{
		return $this->header;
	}
}

class PlayerMatchDayTable
{
	private $data = array(array());
	private $header = array("Spieler","Punkte","");
	private $columns = 0;
	private $rows = 0;
	
	public function __construct($matchDayNr)
	{
		$this->columns = sizeof($this->header);
		$this->load($matchDayNr);
	}
	
	private function load($matchDayNr)
	{
		if (empty($matchDayNr))
			return FALSE;
		if (!is_numeric($matchDayNr))
			return FALSE;
		$pointAlloc = readPunkteConfig("../config/punkte.conf");	
		$users = loadAllUser();
		if (!is_array($users))
			return FALSE;
		$this->rows = sizeof($users);
		$matchDay = loadSpieltag($matchDayNr);
		for ($i = 0; $i < sizeof($users); $i++){
			$user = $users[$i];
			$results = loadTippAndErgebnis($user['id'], $matchDay['id']);
			$points = 0;
			if ($results != FALSE){
				$points = $this->sumPoints($results, $pointAlloc);
			}
			$this->fillRow($i, $user, $points);
		}
		
		function cmp_points($a, $b)
		{
			if ($a[1] == $b[1])
				return 0;
			return ($a[1] > $b[1]) ? -1 : 1; 	
		}
		
		usort($this->data, 'cmp_points');
	}
	
	private function sumPoints($results, $p)
	{
		$points = 0;
		for ($j = 0; $j < sizeof($results); $j++){
			$res = $results[$j];
			if (!ereg("[0-9]{1}:[0-9]{1}", $res['ergebnis']) ||
				!ereg("[0-9]{1}:[0-9]{1}", $res['tipp']))
				continue;
			$gameTime = timeStamp($res['zeit']);
			$currentTime = time();
			if ($currentTime > $gameTime)
				$points += berechnePunkte($res['tipp'], $res['ergebnis'], $p);
		}
		return $points;
	}
	
	private function fillRow($row, $user, $points)
	{
		$this->data[$row][0] = $user['name'];
		$this->data[$row][1] = $points;
		$this->data[$row][2] = "<img src=\"".$user['picture']."\"/>";		
	}
	
	public function get($row, $col)
	{
		return $this->data[$row][$col];
	}
	
	public function columns()
	{
		return $this->columns;
	}
	
	public function rows()
	{
		return $this->rows;	
	}
	
	public function header()
	{
		return $this->header;
	}
}

class PlayerTable
{
	private $data = array(array());
	private $header = array("Spieler","Punkte","Tipps","");
	private $columns = 0;
	private $rows = 0;
	 
	public function __construct()
	{
		$this->columns = sizeof($this->header);
		$this->load();
	}
	
	private function load()
	{
		$users = loadAllUser();
		if (!is_array($users))
			return FALSE;
		$pointAlloc = readPunkteConfig("../config/punkte.conf");
		$this->rows = sizeof($users);
		for ($i = 0; $i < sizeof($users); $i++){
			$user = $users[$i];
			$results = loadTippAndErgebnis($user['id'], NULL);
			$points = 0;
			$this->data[$i][2] = 0;
			if ($results != FALSE){
				for ($j = 0; $j < sizeof($results); $j++){
					$res = $results[$j];
					if (!ereg("[0-9]{1}:[0-9]{1}", $res['ergebnis']) ||
						!ereg("[0-9]{1}:[0-9]{1}", $res['tipp']))
						continue;
					$gameTime = timeStamp($res['zeit']);
					$currentTime = time();
					if ($currentTime > $gameTime)
						$points += berechnePunkte($res['tipp'], $res['ergebnis'], $pointAlloc);
				}
				$this->data[$i][2] = sizeof($results);
			}
			$this->data[$i][0] = $user['name'];
			$this->data[$i][1] = $points;
			$this->data[$i][3] = "<img src=\"".$user['picture']."\"/>";
		}
		
		function cmp_points($a, $b)
		{
			if ($a[1] == $b[1])
				return 0;
			return ($a[1] > $b[1]) ? -1 : 1; 	
		}
		
		usort($this->data, 'cmp_points');
	}
	
	private function fillRow()
	{
		
	}
	
	public function get($row, $col)
	{
		return $this->data[$row][$col];
	}
	
	public function columns()
	{
		return $this->columns;
	}
	
	public function rows()
	{
		return $this->rows;	
	}
	
	public function header()
	{
		return $this->header;
	}
}

class TippTable
{
	private $data = array(array());
	private $header = array("", "", "ergebnis");
	private $columns = 0;
	private $rows = 0;
	
	public function __construct($spieltagId)
	{	
		$this->load($spieltagId);
	}
	
	private function load($spieltagId)
	{
		if (!is_numeric($spieltagId))
			return FALSE;
		$spiele = loadSpiele($spieltagId);
		$users = loadAllUser();
		for ($i = 0; $i < sizeof($users); $i++){
			if ($users[$i]['role'] == 2)
				continue;
			array_push($this->header, $users[$i]['name']);
		}
		$this->columns = sizeof($this->header);
		$this->rows = sizeof($spiele);
		
		for($i = 0; $i < sizeof($spiele); $i++){
			$this->data[$i][0] = "<img src=\"".$spiele[$i]['t1w']."\"/>";
			$this->data[$i][1] = "<img src=\"".$spiele[$i]['t2w']."\"/>";
			$this->data[$i][2] = htmlentities($spiele[$i]['ergebnis']);

			for ($j = 0; $j < sizeof($users); $j++){
				if ($users[$j]['role'] == 2)
					continue;
				$tipp = loadTippFromUser($spiele[$i]['id'], $users[$j]['id']);
				$currentTime = time();
				$gameTime = timeStamp($spiele[$i]['zeit']);
				
				$tippStr = "";
				if ($users[$j]['name'] == $_SESSION['benutzer'])
					$tippStr = $tipp['ergebnis'];
				else if ($currentTime > $gameTime )
					$tippStr = $tipp['ergebnis'];
				$this->data[$i][$j + 3] = $tippStr;
			}
		}
	}
	
	private function fillRow($game, $data)
	{
		
	}
	
	public function get($row, $col)
	{
		return $this->data[$row][$col];
	}
	
	public function columns()
	{
		return $this->columns;
	}
	
	public function rows()
	{
		return $this->rows;	
	}
	
	public function header()
	{
		return $this->header;
	}
}
?>