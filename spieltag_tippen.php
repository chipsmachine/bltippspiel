<?php
PersistenzManager::instance()->connect();
$spieltage = loadSpieltage();

echo "<h3>Spieltag</h3>";
echo "<form name=spieltagForm method=post action=spieltag.php>";
echo "<select name=spieltage>";

for ($i = 0; $i < sizeof($spieltage); $i++){
	$spieltag = $spieltage[$i];
	echo "<option>" . $spieltag['nr'] . "</option>";
}

echo "</select><br>";
echo "<input type=submit name=Submit value=laden />"; 
echo "</form><br><br>";

if (isset($_POST['spieltage'])){
	$spiele = loadSpiele($_POST['spieltage']);
	
	echo "<form name=spieleForm method=post action=spieltag.php>";
	echo "<table>";
	echo "<tr>" .
	"<th> </th" .
	"<th>Team 1</th>" .
	"<th>Team 2</th>" .
	"<th>Ergebnis</th>" .
	"<th>Tipp</th>" .
	"</tr>";
	for ($i = 0; $i < sizeof($spiele); $i++){
		$spiel = $spiele[$i];
		echo "<tr>" .
		"<td class=produkt>" . "<input type=hidden name=spielid[] value=" . $spiel['id'] . " />" .
		"<td class=produkt>" . htmlentities($spiel['t1']) . "</td>" .
		"<td class=produkt>" . htmlentities($spiel['t2']) . "</td>" .
		"<td class=produkt>" . htmlentities($spiel['ergebnis']) . "</td>";
		$tipps = tippBezeichner();
		echo "<td class=produkt>" . "<input type=text size=5 maxlength=5 name=tippergebnis[] />" .
		"</td>";	
		echo "</tr>";
	}
	echo "</table>";
	echo "<input type=submit name=Submit value=Tippen />";
	echo "</form>";
}
else if (isset($_POST['tippergebnis'])){
	$tippErgebnis = $_POST['tippergebnis'];
	$spielId = $_POST['spielid'];
	echo "<table>";
	echo "<th>Nr</th>";
	echo "<th></th>";
	echo "<th></th>";
	echo "<th>Tipp</th>";
	for ($i = 0; $i < sizeof($tippErgebnis); $i++){
		if ($tippErgebnis[$i] != NULL){
			$spiel = loadSpiel($spielId[$i]);
			echo "<tr>";			
			echo "<td class=produkt>" . ($i + 1) . "</td>";
			echo "<td class=produkt>" . htmlentities($spiel['t1']) . "</td>";
			echo "<td class=produkt>" . htmlentities($spiel['t2']) . "</td>";
			echo "<td class=produkt>" . $tippErgebnis[$i] . "</td>";
			echo "</tr>";	
		}
	}
	echo "</table>";
}
?>