<?php
PersistencyManager::instance()->connect();
$benutzer = loadAllUser();
if ($benutzer != NULL){
	// Punktetabelle berechnen
	$tabelle = array();
	$tipp_count = array();
	for ($i = 0; $i < sizeof($benutzer); $i++){
		$row = $benutzer[$i];
		if ($row['role'] == 1){
			$ergebnisse = loadTippAndErgebnis($row['id']);
			$punkte = 0;
			for ($j = 0; $j < sizeof($ergebnisse); $j++){
				$res = $ergebnisse[$j];
				$spiel_zeit = $res['zeit'];
				$curr = time();
				if ($curr > $spiel_zeit){
					$punkte += berechnePunkte($res['tipp'], $res['ergebnis']);
				}
			}
			$tabelle[$row['name']] = $punkte;
			$tipp_count[$row['name']] = sizeof($ergebnisse);
		}
	}
	arsort($tabelle, SORT_NUMERIC);
	
	echo "<table>";
	echo "<tr>";
	echo "<th></th>";
	echo "<th>Spieler</th>";
	echo "<th>Punkte</th>";
	echo "<th>Tipps</th>";
	echo "<th>Punkte/Tipp</th>";
	echo "</tr>";
	for ($i = 0; $i < sizeof($tabelle); $i++){
		echo "<tr>";
		echo "<td class=produkt>" . ($i+1) . "</td>";
		$name = key($tabelle);
		if ($name == $_SESSION['benutzer'])
			echo "<td class=produkt><em>".$name."</em></td>";
		else
			echo "<td class=produkt>".$name."</td>";
		echo "<td class=produkt>" . current($tabelle) . "</td>";
		echo "<td class=produkt>" . $tipp_count[key($tabelle)] . "</td>";
		$punkte_tipp_ratio = 0;
		if ($tipp_count[key($tabelle)] > 0)
			$punkte_tipp_ratio = current($tabelle) / $tipp_count[key($tabelle)];
		echo "<td class=produkt>" . $punkte_tipp_ratio . "</td>";
		echo "</tr>";
		next($tabelle);
	}
	echo "</table>";
}
?>