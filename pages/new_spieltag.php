<?php
function createVereineList($vereine, $name)
{
	echo "<select name='".$name."[]'>";
	for ($i = 0; $i < sizeof($vereine); $i++){
		$verein = $vereine[$i];
		echo "<option>".utf8_encode($verein['name'])."</option>";
	}
	echo "</select>";
}

if (!PersistencyManager::instance()->connect())
	die("Error");

if (!isset($_POST['heim'])){	
	$seasons = loadSeasons();
	echo "<form name=spieleForm method=post action=admin_new.php>";
	echo "<select name=saison>";
	for ($i = 0; $i < sizeof(seasons); $i++){
		$season = $seasons[$i];
		echo "<option>".$season['id']."</option>";
	}
	echo "</select>";
	$currentDate = date("Y-m-d") . " 00:00:00";
	$vereine = loadVereine();
	echo "Datum: <input type=text name=spieltagDate value='".$currentDate."'></input>";
	echo "<table>";
	echo "<tr><th></th>".
		  "<th>Heim</th>".
		  "<th>Ausw</th>".
		  "<th>Datum [YYYY-MM-DD]</th>".
		  "<th>Zeit [HH:MM:SS]</th>".
	 "</tr>";
	$date = date("Y-m-d");
	$time = date("H:i:s");
	for ($i = 0; $i < 9; $i++){
		echo "<tr>";
		echo "<td class=produkt>".($i+1)."</td>";
		echo "<td class=produkt>";
		createVereineList($vereine, "heim");
		echo "</td>";
		echo "<td class=produkt>";
		createVereineList($vereine, "ausw");
		echo "</td>";
		echo "<td class=produkt>".
			 "<div id=year>".
			 "<input type=text name=date[] value=".$date."></input>".
			 "</td>";
		echo "<td class=produkt>".
			 "<input type=text name=zeit[] value=".$time."></input>".
			 "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<input type=submit name=Submit value=Sichern />";
	echo "</form>";
}
else {
	$heimTeams = $_POST['heim'];
	$auswTeams = $_POST['ausw'];
	
	$date = $_POST['date'];
	$time = $_POST['zeit'];
	
	echo "<table>";
	echo "<tr>".
		 "<th>Spiel</th>".
		 "<th>Heim</th>".
		 "<th>Ausw.</th>".
		 "<th>Datum</th>".
		 "<th>Zeit</th>".
		 "</tr>";
	for ($i = 0; $i < sizeof($heimTeams); $i++){
		echo "<tr>";
		echo "<td class=produkt>".($i+1)."</td>";
		echo "<td class=produkt>".$heimTeams[$i]."</td>";
		echo "<td class=produkt>".$auswTeams[$i]."</td>";
		echo "<td class=produkt>".$date[$i]."</td>";
		echo "<td class=produkt>".$time[$i]."</td>";
		echo "</tr>";
	}
	echo "</table>";
	
	$spiele = array();		
	$spieltage = loadSpieltage();
	
	$last_spieltag = NULL;
	if (is_array($spieltage))
		$last_spieltag = end($spieltage);
	
	$nr = 1;
	if (is_array($last_spieltag))
		if (!empty($last_spieltag['nr']))
			$nr = $last_spieltag['nr'] + 1;
		
	if (saveSpieltag($nr, $_POST['saison'], $_POST['spieltagDate'])){
		$spieltag = loadSpieltag($nr);
		
		for ($i = 0; $i < sizeof($heimTeams); $i++){
			$deadline = $date[$i]." ".$time[$i];
			
			$heimId = loadVereinId(utf8_decode($heimTeams[$i]));
			$auswId = loadVereinId(utf8_decode($auswTeams[$i]));
			
			saveSpiel($spieltag['id'], $heimId, $auswId,"-:-", $deadline);
		}
		echo "<h2>Spieltag eingetragen</h2>";
	}
}

?>