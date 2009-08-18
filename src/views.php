<?php
require('models.php');
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
		// 
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

class NewsView
{
	private $news = NULL;
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