<?php
PersistencyManager::instance()->connect();
$logs = Log::instance()->load();
echo "<table>";
echo "<tr>".
	 "<th>Message</th>".
	 "<th>Line</th>".
	 "<th>Function</th>".
	 "<th>File</th>".
	 "<th>Date</th>".
	 "</tr>";
if (is_array($logs)){
	for ($i = 0; $i < sizeof($logs); $i++){
		$log = $logs[$i];
		echo "<tr>";
		echo "<td>".$log['msg']."</td>";
		echo "<td>".$log['line']."</td>";
		echo "<td>".$log['function']."</td>";
		echo "<td>".$log['file']."</td>";
		echo "<td>".$log['time']."</td>";
		echo "</tr>";
	}
}
echo "</table>";

?>