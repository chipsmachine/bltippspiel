<?php
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
	$t = htmlentities("Auswärts");
	echo "</select>";
	echo "<table>";
	echo "<tr><th></th>".
		  "<th>Heim</th>".
		  "<th>".$t."</th>".
		  "<th>Zeit [YYYY-MM-DD HH:MM:SS]</th>".
	 "</tr>";
	for ($i = 0; $i < 9; $i++){
		echo "<tr>";
		echo "<td class=produkt>".($i+1)."</td>";
		echo "<td class=produkt><input type=text name=heim[]></input></td>";
		echo "<td class=produkt><input type=text name=ausw[]></input></td>";
		echo "<td class=produkt><input type=text name=zeit[]></input></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<input type=submit name=Submit value=Sichern />";
	echo "</form>";
}
else {
	$heimTeams = $_POST['heim'];
	$auswTeams = $_POST['ausw'];
	$deadline = $_POST['zeit'];
	
	$spiele = array();	
	$spieltage = loadSpieltage();
	$last_spieltag = NULL;
	if (is_array($spieltage))
		$last_spieltag = end($spieltage);
	
	$nr = 1;
	if (is_array($last_spieltag))
		if (!empty($last_spieltag['nr']))
			$nr = $last_spieltag['nr'] + 1;
		
	if (saveSpieltag($nr, $_POST['saison'])){
		for ($i = 0; $i < sizeof($heimTeams); $i++){
			if (empty($heimTeams[$i]) || empty($auswTeams[$i]) || empty($deadline[$i]))
			continue;
			$spieltag = loadSpieltag($nr);
			saveSpiel($spieltag['id'], utf8_decode($heimTeams[$i]), utf8_decode($auswTeams[$i]),"-:-", $deadline[$i]);
		}
		echo "<h2>Spieltag eingetragen</h2>";
	}
}

?>