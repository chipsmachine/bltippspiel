<?php
echo "<table>";
echo "<tr>";
echo "<th></th>";
echo "<th>Spieler</th>";
echo "<th>Punkte</th>";
echo "<th>Tipps</th>";
echo "<th> </th>";
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

	if (!empty($pictures[$name])){
		echo "<td class=produkt>" . "<img src='".$pictures[$name]."'/>"."</td>";
	}
	else
	echo "<td class=produkt></td>";
	echo "</tr>";
	next($tabelle);
}
echo "</table>";
?>