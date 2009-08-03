<?php
PersistencyManager::instance()->connect();
$benutzer = loadAllUser();
echo "<table>" . "<tr>" . 
	 "<th>Spieler</th><th>Tipps insgesamt</th><th>Punkte</th></tr>";
for ($i = 0; $i < sizeof($benutzer); $i++){
	$data = $benutzer[$i];
	if ($data['role'] == 1){
		$tipps = loadAllTippsFromUser($data['id']);
		echo "<tr>";
		$punkte = 0;
		for ($j = 0; $j < sizeof($tipps); $j++){
			$tipp = $tipps[$j];
			$punkte += $tipp['punkte'];
		}
		echo "<td class=produkt>" . $data['name'] . "</td>";
		echo "<td class=produkt>" . sizeof($tipps) . "</td>";
		echo "<td class=produkt>" . $punkte . "<td>";
		echo "</tr>";
	}	
}
echo "</table>";
?>