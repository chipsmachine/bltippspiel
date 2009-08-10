<?php
if (!PersistencyManager::instance()->connect())
	die("Error");

$spieltage = loadSpieltage();
echo "<h3>Spieltag</h3>";
echo "<form name=spieltagForm method=post action=admin_edit.php>";
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
	$spiele = loadSpiele($spieltag['id']);
	echo "Spieltag: ".$_POST['spieltage']."<br>";
	echo "<form name=spieleForm method=post action=admin_edit.php>";
	echo "<table>";
	echo "<tr><th></th>".
		  "<th>Heim</th>".
		  "<th>Auswärts</th>".
		  "<th>Ergebnis</th>".
		  "<th>Zeit [YYYY-MM-DD HH:MM:SS]</th>".
	 "</tr>";
	for ($i = 0; $i < sizeof($spiele); $i++){
		$spiel = $spiele[$i];

		echo "<tr>" .
		"<td class=produkt>" . 
			"<input type=hidden name=spielid[] value=" . $spiel['id'] . " />" . "</td>" .
		"<td class=produkt>" . 
			"<input type=text name=heim[] value="."'".htmlentities($spiel['t1'])."'"."></input>" . "</td>" .
		"<td class=produkt>" . 
			"<input type=text name=ausw[] value="."'".htmlentities($spiel['t2'])."'"."></input>" . "</td>" .
		"<td class=produkt>" . 
			"<input type=text name=erg[] value="."'".htmlentities($spiel['ergebnis'])."'"."></input>" . "</td>" .
		"<td class=produkt>" . 
			"<input type=text name=zeit[] size=30 value="."'".htmlentities($spiel['zeit'])."'"."></input>" . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<input type=submit name=Submit value=Sichern />";
	echo "</form>";
}
else if (isset($_POST['spielid'])){
	$spielId = $_POST['spielid'];
	$heim = $_POST['heim'];
	$ausw = $_POST['ausw'];
	$erg = $_POST['erg'];
	$zeit = $_POST['zeit'];
	for ($i = 0; $i < sizeof($spielId); $i++){
		if (empty($heim[$i]) || empty($ausw[$i]) || empty($erg[$i]) || empty($zeit[$i]))
			continue;	
		updateSpiel($spielId[$i], utf8_decode($heim[$i]), utf8_decode($ausw[$i]), $erg[$i], $zeit[$i]);
	}
	echo "Spiele aktualisiert<br>";
	echo "<a href=admin.php>zurück</a></div>";	
}
?>