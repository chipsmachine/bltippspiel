<?php
function printSpieltag($spiele, $benutzerId)
{
	echo "<table>";
	echo "<tr>" .
	"<th> </th>" .
	"<th>Team 1</th>" .
	"<th </th>" .
	"<th>Team 2</th>" .
	"<th>Ergebnis</th>" .
	"<th>Tipp</th>" .
	"</tr>";
	
	for ($i = 0; $i < sizeof($spiele); $i++){
		$spiel = $spiele[$i];
		
		echo "<tr>" .
		//"<td class=produkt>" . "<input type=hidden name=spielid[] value=" . $spiel['id'] . " />" .
		"<input type=hidden name=spielid[] value=" . $spiel['id'] . " />".
		"<td class=produkt>" . "<img src='".$spiel['t1w']."'/>"."</td>".
		"<td class=produkt>" . htmlentities($spiel['t1']) . "</td>" .
		"<td class=produkt>" . "<img src='".$spiel['t2w']."'/>" . "</td>" .
		"<td class=produkt>" . htmlentities($spiel['t2']) . "</td>" .
		"<td class=produkt>" . htmlentities($spiel['ergebnis']) . "</td>";
		
		$tipp = loadTippFromUser($spiel['id'], $benutzerId);
		if (!isTippExpired($spiel['id'])){
			if ($tipp == FALSE){
				echo "<td class=produkt>" . 
				"<input type=text size=5 maxlength=5 name=tippergebnis[] />" . "</td>";			
			}	
			else{
				echo "<td class=produkt>" . 
				"<input type=text size=5 maxlength=5 name=tippergebnis[] value=".$tipp['ergebnis']." />" . "</td>";	
			}
		}
		else {
			echo "<td class=produkt>" . $tipp['ergebnis'] . "</td>";
		}
		echo "</tr>";
	}
	echo "</table>";	
}
PersistencyManager::instance()->connect();
$spieltage = loadSpieltage();

/*
 * state = 1 Spieltagauswahl
 * state = 2 Spieltagauswahl + Spiele + Tipps
 * state = 3 Tipps sichern
 */
$state = 0;

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
	$state = 2;
	$spieltagNr = $_POST['spieltage'];
}
else if (isset($_POST['tippergebnis']))
	$state = 3;

if ($state == 2){
	$spieltag = loadSpieltag($spieltagNr);
	$spiele = loadSpiele($spieltag['id']);
	$benutzer_id = loadUserId($_SESSION['benutzer']);

	echo "<form name=spieleForm method=post action=spieltag.php>";
	printSpieltag($spiele, $benutzer_id);
	echo "<input type=submit name=Submit value=Tippen />";
	echo "</form>";
	
	$_SESSION['spieltag'] = $spieltag['id'];
}
else if ($state == 3){
	
	$tippErgebnis = $_POST['tippergebnis'];
	$spielId = $_POST['spielid'];
	$benutzerId = loadUserId($_SESSION['benutzer']);
	
	for ($i = 0; $i < sizeof($tippErgebnis); $i++){
		if ($tippErgebnis[$i] != NULL){
			$spiel = loadSpielWithNames($spielId[$i]);
			if (!ereg("[0-9]{1}:[0-9]{1}", $tippErgebnis[$i]))
				continue;
			saveTipp($benutzerId, $spielId[$i], $tippErgebnis[$i]);
		}
	}
	$spiele = loadSpiele($_SESSION['spieltag']);
	
	echo "<form name=spieleForm method=post action=spieltag.php>";
	printSpieltag($spiele, $benutzerId);
	echo "<input type=submit name=Submit value=Tippen />";
	echo "</form>";
}
?>