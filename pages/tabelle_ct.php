<?php
require_once('../src/views.php');
PersistencyManager::instance()->connect();

echo "<form name=spieltagForm method=post action=tabelle.php>";
echo "<select name=spieltage>";
echo "<option>Gesamt</option>";
$spieltage = loadSpieltage();
for ($i = 0; $i < sizeof($spieltage); $i++){
	$spieltag = $spieltage[$i];
	echo "<option>" . $spieltag['nr'] . "</option>";
}
echo "</select><br>";
echo "<input type=submit name=Submit value=laden />"; 
echo "</form>";

$table = NULL;
$view = NULL;
if (isset($_POST['spieltage'])){
	if ($_POST['spieltage'] == "Gesamt")
		$table = new PlayerTable();
	else 
		$table = new PlayerMatchDayTable($_POST['spieltage']);
}
else
	$table = new PlayerTable();

$view = new PlayerTableView($table);
$view->show();
?>