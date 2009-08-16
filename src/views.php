<?php

/*class TableModel
{
	private $data = array(array());   // 2d array
	private $header; // array
	private $columns;
	private $rows;
	
	public function __construct($rows, $columns)
	{
		$this->columns = $columns;
		$this->rows = $rows;
	}

	protected function setHeader($header)
	{
		$this->header = $header;
	}
	
	public function header()
	{
		return $this->header;
	}
	
	public function columns()
	{
		return $this->columns;
	}
	
	public function rows()
	{
		return $this->rows;
	}

	public function at($row, $col)
	{
		if (($row >= $this->rows && $row < 0) || ($col >= $this->col && $col < 0))
			return NULL;
		$row = $this->data[$row];
		return $row[$col];	
	}
	
	public function row($index)
	{
		if ($index >= $this->rows && $index < 0)
			return NULL;
		return $this->data[$index];
	}
}*/

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
				$points = $this->sumPoints($results);
			}
			$this->fillRow($user, $points);
		}
		
		function cmp_points($a, $b)
		{
			if ($a[1] == $b[1])
				return 0;
			return ($a[1] > $b[1]) ? -1 : 1; 	
		}
		
		usort($this->data, 'cmp_points');
	}
	
	private function sumPoints($results)
	{
		for ($j = 0; $j < sizeof($results); $j++){
			$res = $results[$j];
			if (!ereg("[0-9]{1}:[0-9]{1}", $res['ergebnis']) ||
			!ereg("[0-9]{1}:[0-9]{1}", $res['tipp']))
			continue;
			$gameTime = timeStamp($res['zeit']);
			$currentTime = time();
			if ($currentTime > $gameTime)
			$points += berechnePunkte($res['tipp'], $res['ergebnis']);
		}
	}
	
	private function fillRow($user, $points)
	{
		$this->data[$i][0] = $user['name'];
		$this->data[$i][1] = $points;
		$this->data[$i][2] = "<img src=\"".$user['picture']."\"/>";		
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
						$points += berechnePunkte($res['tipp'], $res['ergebnis']);
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

class PlayerTableView
{
	private $table = NULL;
	
	public function __construct($table)
	{
		$this->table = $table;
	}
	
	public function show()
	{
		$header = $this->table->header();
		echo "<table>";
		echo "<tr>";
		echo "<th></th>";
		for ($i = 0; $i < sizeof($header); $i++){
			echo "<th>".$header[$i]."</th>";
		}
		echo "</tr>";
		for ($i = 0; $i < $this->table->rows(); $i++){
			echo "<tr>";
			echo "<td class=\"produkt\">".($i+1)."</td>";
			for ($j = 0; $j < $this->table->columns(); $j++){
				echo "<td class=\"produkt\">".$this->table->get($i, $j)."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
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

class TippTableView
{
	private $table = NULL;
	
	public function __construct($table)
	{
		$this->table = $table;	
	}
	
	public function show()
	{
		echo "<table>";
		echo "<tr>";
		$header = $this->table->header();
		for ($i = 0; $i < sizeof($header); $i++){
			echo "<th>".$header[$i]."</th>";
		}
		echo "</tr>";
		for ($i = 0; $i < $this->table->rows(); $i++){
			echo "<tr>";
			for ($j = 0; $j < $this->table->columns(); $j++){
				echo "<td class=\"produkt\">".$this->table->get($i, $j)."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
}

class GBuchDivView
{
	private $gbuch = NULL;
	const MSG_PER_PAGE = 5;
	private $picPath = NULL;
	private $pageCount = 1;
	private $page = array(array());
	private $currentPage = 0;
	
	public function __construct($gbuch)
	{
		$this->gbuch = $gbuch;
		$this->fillPages();
	}
	
	private function fillPages()
	{
		$messages = $this->gbuch->messages();
		$msgCount = sizeof($messages);
		$this->pageCount = ceil($msgCount / self::MSG_PER_PAGE);
		for ($i = 0, $current = -1, $j = 0; $i < $msgCount; $i++){
			if ($i % self::MSG_PER_PAGE == 0){
				$current++;
				$j = 0;
			}
			$this->page[$current][$j] = $messages[$i];
			$j++;
		}
	}
	
	private function showMessage($message)
	{
		$user = loadUserFromId($message->user());
		echo "<div id=\"box\">";
		echo "<div id=\"pic\">";
		echo "<img src=\"".$user['picture']."\"/>";
		echo $user['name']."<br>";
		echo "</div>";
		echo "<div id=\"msg\">";
		echo $message->message();
		echo "</div>";
		echo "<div id=\"box2\">";
		echo $message->date();
		echo "</div>";
		echo "</div>";
	}
	
	public function setPage($index)
	{
		if (!is_numeric($index))
			return FALSE;
		$this->currentPage = $index;
	}
	
	public function pages()
	{
		return $this->pageCount;
	}
	
	public function show()
	{
		$messages = $this->page[$this->currentPage];
		if (!is_array($messages))
			return FALSE;
		for ($i = 0; $i < sizeof($messages); $i++){
			$this->showMessage($messages[$i]);
		}
	}
	
}



class LogTableView
{
	private $log = NULL;
	private $header = array('Message','Line','Function','File','Date');
	
	public function __construct($log)
	{
		$this->log = $log;
	}
	
	private function tableHeader()
	{
		echo "<tr>";
		for ($i = 0; $i < sizeof($this->header); $i++)
			echo "<th>".$this->header[$i]."</th>";
	 	echo "</tr>";
	}
	
	private function showLogEntry($entry)
	{
		echo "<tr>";
		echo "<td>".$entry['msg']."</td>";
		echo "<td>".$entry['line']."</td>";
		echo "<td>".$entry['function']."</td>";
		echo "<td>".$entry['file']."</td>";
		echo "<td>".$entry['time']."</td>";
		echo "</tr>";
	}
	
	public function show()
	{
		$entries = $this->log->entries();
		if (!is_array($entries))
			return FALSE;
		echo "<table>";
		$this->tableHeader();
		for ($i = 0; $i < sizeof($entries); $i++){
			$this->showLogEntry($entries[$i]);
		}
		echo "</table>";
	}
}


class TableView
{
	private $attribute = array();
	private $model;
	
	public function __construct($model)
	{
		$this->model = $model;
	}
		
	public function setAttribute($key, $value)
	{
		$this->attribute[$key] = $value;
	}
	
	public function draw()
	{
		echo "rows:".$this->model->rows()."<br>";
		echo "cols:".$this->model->columns();
		echo "<table>";
		echo "<tr>";
		$header = $this->model->header();
		for ($i = 0; $i < sizeof($header); $i++){
			echo "<th>".$header[$i]."</th>";
		}
		echo "</tr>";
		for ($i = 0; $i < $this->model->rows(); $i++){
			echo "<tr>";
			for ($j = 0; $j < $this->model->columns(); $j++){
				$row = $this->model->row($i);
				if (empty($this->attribute['td_class']))
					echo "<td>".$row[$j]."</td>";
				else
					echo "<td class=".$this->attribute['td_class'].">".$row[$j]."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
	}
}
?>