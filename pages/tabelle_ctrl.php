<?php
require_once('../src/views.php');
PersistencyManager::instance()->connect();
$benutzer = loadAllUser();
$table = new PlayerTable();
$view = new PlayerTableView($table);
$view->show();
/*if ($benutzer != NULL){
	// Punktetabelle berechnen
	// TODO: alles in ein Array stecken
	$tabelle = array();
	$tipp_count = array();
	$pictures = array();
	for ($i = 0; $i < sizeof($benutzer); $i++){
		$row = $benutzer[$i];
		if ($row['role'] == 1){ // User ist kein Admin
			$ergebnisse = loadTippAndErgebnis($row['id']);
			$punkte = 0;
			if ($ergebnisse != FALSE){ // es liegen Tipps vor
				for ($j = 0; $j < sizeof($ergebnisse); $j++){
					$res = $ergebnisse[$j];
					if (!ereg("[0-9]{1}:[0-9]{1}", $res['ergebnis']) ||
						!ereg("[0-9]{1}:[0-9]{1}", $res['tipp']))
						continue;	
					$spiel_zeit = timeStamp($res['zeit']);
					$curr = time();
					if ($curr > $spiel_zeit){
						$punkte += berechnePunkte($res['tipp'], $res['ergebnis']);
					}
				}
				$tipp_count[$row['name']] = sizeof($ergebnisse);
			}
			else
				$tipp_count[$row['name']] = 0;
				
			$tabelle[$row['name']] = $punkte;
			$pictures[$row['name']] = $row['picture'];
		}
	}
	arsort($tabelle, SORT_NUMERIC); 
	include('tabelle_view.php');
}*/
?>