<?php
$pkte_config = readPunkteConfig("../config/punkte.conf");

if (isset($_POST['keys'])){
	$pkte_config = array();
	for ($i = 0; $i < sizeof($_POST['keys']); $i++){
		if (empty($_POST['punkte'][$i]) || !is_numeric($_POST['punkte'][$i]))
			continue;
		$pkte_config[$_POST['keys'][$i]] = $_POST['punkte'][$i];
	}
	print_r($pkte_config);
	if (savePunkteConfig("../config/punkte.conf", $pkte_config) == FALSE)
		echo "oje net gesichert<br>";
	$pkte_config = readPunkteConfig("../config/punkte.conf");
}
else {
	
}
echo "<form method=POST action=admin_pkte.php>";
echo "<table>";
echo "<tr>".
     "<th>Typ</th>".
	 "<th>Punkte</th>".
	 "</tr>";
do{
	echo "<tr>";
	echo "<td class=produkt><input type=text name=keys[] readonly value=".key($pkte_config)."></input></td>";
	echo "<td class=produkt>".
		 "<input type=text name=punkte[] size=1 maxsize=2 value=".current($pkte_config)." ></input></td>";
	echo "</tr>";	
}while(next($pkte_config));

echo "</table>";
echo "<input type=submit name=sichern></input>";
echo "</form>"
?>
