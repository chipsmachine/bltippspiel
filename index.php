<?php

include("funktionen.php");

$log = file("log.txt");

//array=(Punkte, Rang, 2er, 3er, 4er, Name)
$stefan = array(0,0,0,0,0,'Stefan',0);
$benny = array(0,0,0,0,0,'Ben',0);
$marcel = array(0,0,0,0,0,'Marcel',0);

for($i=0; $i<count($log); $i=$i+5) {

	//nur Spiele mit Ergebnis beachten
	if(strpos($log[$i],':') === false) {
		break;
	}

	//Punkte ohne letztes Spiel fuer Ranking-Pfeile
	if(strpos($log[$i+5],':') === false) {
		$tendenz_array_punkte = array($stefan[0], $benny[0], $marcel[0]);
		$tendenz_array = array($stefan, $benny, $marcel);
	}

	$ergebnis = trim(substr($log[$i], strlen($log[$i]) - 4));
	$tipp_stefan = trim(substr($log[$i+1], strlen($log[$i+1]) - 4));
	$tipp_benny = trim(substr($log[$i+2], strlen($log[$i+2]) - 4));
	$tipp_marcel = trim(substr($log[$i+3], strlen($log[$i+3]) - 4));

	$punkte_stefan = punkteFuerTipp($ergebnis, $tipp_stefan);
	if($punkte_stefan > 0) {
    	$stefan[0] += $punkte_stefan;
		$stefan[$punkte_stefan]++;
	}

	$punkte_benny = punkteFuerTipp($ergebnis, $tipp_benny);
	if($punkte_benny > 0) {
		$benny[0] += $punkte_benny;
		$benny[$punkte_benny]++;
	}

	$punkte_marcel = punkteFuerTipp($ergebnis, $tipp_marcel);
	if($punkte_marcel > 0) {
		$marcel[0] += $punkte_marcel;
		$marcel[$punkte_marcel]++;
	}
}
	 
//aktueller Stand
$arr = array($stefan[0], $benny[0], $marcel[0]);
$arr2 = array($stefan,$benny,$marcel);
$erg = bubblesort($arr,$arr2);
array_shift($erg);
//print_r($erg);

//Tendenzpfeile
$tendenz_erg = bubblesort($tendenz_array_punkte, $tendenz_array);
array_shift($tendenz_erg);
//print_r($tendenz_erg);

//Platz 1
if($erg[2][5] == $tendenz_erg[2][5]) {
	$erg[2][6] = "-";
} else {
	$erg[2][6] = "&uarr;";
}

//Platz 2
if($erg[1][5] == $tendenz_erg[1][5]) {
	$erg[1][6] = "-";
} else {
	if($erg[1][5] == $tendenz_erg[2][5]) {
		$erg[1][6] = "&darr;";
	} else {
		$erg[1][6] = "&uarr;";
	}
}

//Platz 3
if($erg[0][5] == $tendenz_erg[0][5]) {
	$erg[0][6] = "-";	
} else {
	$erg[0][6] = "&darr;";
}


?>
<html>
<head>
<title>EM 2008</title>
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<style>
span	{font-family:"Arial Unicode MS",Arial,sans-serif;}
td	{border:1px solid #999999;}
tr.g	{background-color:#CCCCCC;}
td.z	{text-align:center;}
</style>
</head>

<body>

<div style="width:300px;">

<?php include("menue.php"); ?>

<table cellpadding="3" cellspacing="0" border="0" width="300">
<tr>
	<td>Rang</td>
	<td>Name</td>
	<td>2er</td>
	<td>3er</td>
	<td>4er</td>
	<td>Punkte</td>
</tr>
	
<tr class="g">
	<td>1.&nbsp;&nbsp;<span><?php echo $erg[2][6] ?></span></td>
	<td><?php print_r($erg[2][5]) ?></td>
	<td class="z"><?php print_r($erg[2][2]) ?></td>
	<td class="z"><?php print_r($erg[2][3]) ?></td>
	<td class="z"><?php print_r($erg[2][4]) ?></td>
	<td class="z"><?php print_r($erg[2][0]) ?></td>
</tr>
<tr>
	<td>2.&nbsp;&nbsp;<span><?php echo $erg[1][6] ?></span></td>
	<td><?php print_r($erg[1][5]) ?></td>
	<td class="z"><?php print_r($erg[1][2]) ?></td>
	<td class="z"><?php print_r($erg[1][3]) ?></td>
	<td class="z"><?php print_r($erg[1][4]) ?></td>
	<td class="z"><?php print_r($erg[1][0]) ?></td>
</tr>
<tr class="g">
	<td>3.&nbsp;&nbsp;<span><?php echo $erg[0][6] ?><span></td>
	<td><?php print_r($erg[0][5]) ?></td>
	<td class="z"><?php print_r($erg[0][2]) ?></td>
	<td class="z"><?php print_r($erg[0][3]) ?></td>
	<td class="z"><?php print_r($erg[0][4]) ?></td>
	<td class="z"><?php print_r($erg[0][0]) ?></td>
</tr>

</table>
</div>
<br><br>

<img src="benny.jpg">

<!--
Kontrolle:
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>Name</td>
	<td>Punkte</td>
	<td>2er</td>
	<td>3er</td>
	<td>4er</td></tr>
	
<tr>
	<td>Stefan</td>
	<td><?php print_r($stefan[0]) ?></td>
	<td><?php print_r($stefan[2]) ?></td>
	<td><?php print_r($stefan[3]) ?></td>
	<td><?php print_r($stefan[4]) ?></td></tr>
<tr>
	<td>Ben</td>
	<td><?php print_r($benny[0]) ?></td>
	<td><?php print_r($benny[2]) ?></td>
	<td><?php print_r($benny[3]) ?></td>
	<td><?php print_r($benny[4]) ?></td></tr>
<tr>
	<td>Marcel</td>
	<td><?php print_r($marcel[0]) ?></td>
	<td><?php print_r($marcel[2]) ?></td>
	<td><?php print_r($marcel[3]) ?></td>
	<td><?php print_r($marcel[4]) ?></td></tr></table>
-->




</body>
</html>
