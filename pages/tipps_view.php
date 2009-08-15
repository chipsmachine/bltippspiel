<?php
require_once('../src/views.php');
PersistencyManager::instance()->connect();
$spieltage = loadSpieltage();

echo "<h3>Alle Tipps des Spieltags ansehen</h3>";
echo "<form name=spieltagForm method=post action=tipps.php>";
echo "<select name=spieltage>";

for ($i = 0; $i < sizeof($spieltage); $i++){
	$spieltag = $spieltage[$i];
	echo "<option>" . $spieltag['nr'] . "</option>";
}

echo "</select><br>";
echo "<input type=submit name=Submit value=laden />"; 
echo "</form><br><br>";

if (isset($_POST['spieltage'])){
	$spieltag = loadSpieltag($_POST['spieltage']);
	$users = loadAllUser();
	$spiele = loadSpiele($spieltag['id']);
	
	$tippTable = new TippTable($spieltag['id']);
	$view = new TippTableView($tippTable);
	$view->show();
}
	
?>