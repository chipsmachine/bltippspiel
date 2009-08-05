<?php

class TableModel
{
	private $data;   // 2d array
	private $header; // array
	private $columns;
	private $rows;
	
	public function __construct($rows, $columns)
	{
		$this->columns = $columns;
		$this->rows = $rows;
	}
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	public function setHeader($header)
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