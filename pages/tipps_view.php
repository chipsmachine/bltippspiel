<?php
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
	//print_r($users);
	$spiele = loadSpiele($spieltag['id']);
	echo "<table>";
	echo "<tr>".
		 "<th> </th>" .
		 "<th </th>" .
		 "<th>Ergebnis</th>"; 
	for ($i = 0; $i < sizeof($users); $i++){
		if ($users[$i]['role'] == 2)
			continue;
		echo "<th>".$users[$i]['name']."</th>";
	}
	echo "</tr>";
	for($i = 0; $i < sizeof($spiele); $i++){
		echo "<tr>".
		"<td class=produkt>" . "<img src='".$spiele[$i]['t1w']."'/>"."</td>".
		"<td class=produkt>" . "<img src='".$spiele[$i]['t2w']."'/>" . "</td>" .
		"<td class=produkt>" . htmlentities($spiele[$i]['ergebnis']) . "</td>";
		for ($j = 0; $j < sizeof($users); $j++){
			if ($users[$j]['role'] == 2)
				continue;
			$tipp = loadTippFromUser($spiele[$i]['id'], $users[$j]['id']);
			echo "<td class=produkt>".$tipp['ergebnis']."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
	
?>