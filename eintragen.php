<?php

if(isset($_POST['ok'])) {

	//Logdatei einlesen	
	$log = file("log.txt");
	for($i=0; $i<count($log); $i=$i+5) {
		if (strpos($log[$i],':') > 0) {
			$ergebnis = trim(substr($log[$i], strlen($log[$i]) - 4));
			$spiel = trim(substr($log[$i], 0, strlen($log[$i]) - 4));
		} 
		else {
			$ergebnis = "";
			$spiel = trim(substr($log[$i], 0, strlen($log[$i])));
		}

		if (strpos($log[$i+1],':') > 0) {
			$tipp_stefan = trim(substr($log[$i+1], strlen($log[$i+1]) - 4));
		} 
		else { 
			$tipp_stefan = "";
		}
	
		if (strpos($log[$i+2],':') > 0) {
			$tipp_benny = trim(substr($log[$i+2], strlen($log[$i+2]) - 4));
		} 
		else { 
			$tipp_benny = "";
		}
		
		if (strpos($log[$i+3],':') > 0) {
			$tipp_marcel = trim(substr($log[$i+3], strlen($log[$i+3]) - 4));
		} 
		else { 
			$tipp_marcel = "";
		}

		$tipps [$i/5][0] = $spiel;
		$tipps [$i/5][1] = $ergebnis;
		$tipps [$i/5][2] = 'Stefan';
		$tipps [$i/5][3] = $tipp_stefan;
		$tipps [$i/5][4] = 'Ben';
		$tipps [$i/5][5] = $tipp_benny;
		$tipps [$i/5][6] = 'Marcel';
		$tipps [$i/5][7] = $tipp_marcel;
	}
	//print_r($tipps);	


	//Neuen Eintrag uebernehmen	
	$_POST['ok'] = "alles ok";
	foreach($_POST as $element) {
		if($element == "") {
			echo "Nicht alle Felder ausgefuellt!<br><br>";
			exit(0);
		}
	}

	$spielPostId  = $_POST['spielPostId'];
	$e1		= $_POST['e1'];
	$e2		= $_POST['e2'];
	$name   = $_POST['name'];
	
	//echo $spielPostId;
	//print_r($tipps);
	//$tipps [$spielPostId][0] = $spiele[$spielPostId];
	if ($name=="Stefan"){
		//$tipps [$spielPostId][2] = 'Stefan';
		$tipps [$spielPostId][3] = $e1 . ":" . $e2;
	}
	if ($name=="Benny"){
		//$tipps [$spielPostId][4] = 'Ben';
		$tipps [$spielPostId][5] = $e1 . ":" . $e2;	
	}
	if ($name=="Marcel"){
		//$tipps [$spielPostId][6] = 'Marcel';
		$tipps [$spielPostId][7] = $e1 . ":" . $e2;	
	}
	if ($name=="Ergebnis"){
		$tipps [$spielPostId][1] = $e1 . ":" . $e2;	
	}
	//print_r($tipps);
		
	//Logeintrag
	for($i=0; $i<count($tipps); $i++) {
		$log_eintrag .= $tipps[$i][0] . " " . $tipps[$i][1] . "\n"
			. $tipps[$i][2]." " . $tipps[$i][3] . "\n"
			. $tipps[$i][4]." " . $tipps[$i][5] . "\n"
			. $tipps[$i][6]." " . $tipps[$i][7] . "\n\n";
	}
	
	$dateizeiger = fopen("log.txt", "w");
	if($dateizeiger != NULL) {
		fwrite($dateizeiger, $log_eintrag);
		fclose($dateizeiger);
	}
	echo "Eintrag hinzugefuegt: <br>";
	echo $tipps[$spielPostId][0] ." ". $e1 . ":" . $e2 . " (" . $name . ")<br><br>";
	//echo "Eintrag hinzugefuegt!<br><br>";
}

//Drop-down Array fuer Spielauswahl
$log = file("log.txt");
$spiele = array();
$spielId = array();
for($i=0; $i<count($log); $i=$i+5) {
	if(strpos($log[$i],':') === false) {
		array_push($spiele, $log[$i]);
		array_push($spielId, $i/5);
	}
}

?>
<html>
<head><title>EM 2008</title>
<style>
td	{border:1px solid #999999;}
tr.g	{background-color:#CCCCCC;}
</style>

</head>

<body>
<?php include("menue.php"); ?>
<form action="eintragen.php" name="em" method="post">

<table cellpadding="3" cellspacing="0" border="0">
	<colgroup>
		<col width="100">
		<col width="150">
		<col width="100">
		<col width="100">
	</colgroup>
	<tr>
		<td>Spiel</td>
		<td>Tipp/Ergebnis</td>
		<td>Name</td>
	</tr>
	<tr class="g">
		<td>
			<select name="spielPostId" size="1">
				<?php
					for ($i=0; $i<count($spiele); $i++) {
						echo "<option value=\"" . $spielId[$i]  . "\">"
							. $spiele[$i] . "</option>\n";
					}
				?>
			</select>
		</td>
		<td style="text-align:center">
			<input type="text" name="e1" size="1" maxlength="1"> :
			<input type="text" name="e2" size="1" maxlength="1">
		</td>
		<td style="text-align:center">
			<select name="name" size="1">
				<option value=""></option>
				<option value="Stefan">Stefan</option>
				<option value="Benny">Benny</option>
				<option value="Marcel">Marcel</option>
				<option value="Ergebnis">Ergebnis</option>
			</select>
		</td>
		<td style="text-align:center">
			<input type="submit" name="ok" value="OK">
		</td>
	</tr>	
</table>

</form>


</body>
</html>

