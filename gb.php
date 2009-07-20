<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title></title>
<style type="text/css">
p            {text-align:justify;}
span.name    {font-size:14px; font-weight:bold; white-space:nowrap;}
span.normal  {font-weight:normal;}
a            {color:blue; text-decoration:none;}
</style>
</head>

<body>

<?php include("menue.php"); ?>

<div style="width:500px; font-size:13px;">

<?php

$aktueller_link = $_GET['link'];
if(!isset($aktueller_link)) $aktueller_link = 1;

//Zeilenweises Einlesen der Textdatei in ein Array
$array = file("gb.txt");

//Berechne die Anzahl an Navigations-Links
$anzahl_eintraege = (count($array) - 1) / 5;
$anzahl_links = $anzahl_eintraege / 10;
settype($anzahl_links, "integer");
if(($anzahl_eintraege % 10) > 0) $anzahl_links++;

//Gaestebuch-Navigation (alles wird angezeigt)
if($aktueller_link === 1) $navigation = "[<a href=\"gb.php\">1</a>]";
  else $navigation = "<a href=\"gb.php\">1</a>";

for($k=2; $k<=$anzahl_links; $k++) {

  if($aktueller_link == $k) {
    $navigation .= " <span class=\"normal\">&middot;</span>\n[<a href=\"gb.php?link=".$k."\">".$k."</a>]";
  }
  else {
    $navigation .= " <span class=\"normal\">&middot;</span>\n<a href=\"gb.php?link=".$k."\">".$k."</a>";
  }
}

/*//Gaestebuch-Navigation (immer 7 sichtbare Links)
//Erster Link
if($aktueller_link == 1) $navigation = "[<a href=\"gb.php\">1</a>]";
  else $navigation = "<a href=\"gb.php\">1</a>";

//Ggf. "..."
if($aktueller_link > 4)
  $navigation .= " <span>&middot;</span>\n<span>...</span>";

//Schleifenstart und -abbruch den Umstaenden anpassen
$start = $aktueller_link-2; 
$stop  = $start + 4;
while($start < 2) {
  $start++;
  $stop++;
}
while($stop > $anzahl_links-1){
  $start--;
  $stop--;
}

//Erzeuge die Links
for($x=$start; $x<=$stop; $x++) {
  if($x == $aktueller_link)
    $navigation .= " <span>&middot;</span>\n[<a href=\"gb.php?link=".$x."\">".$x."</a>]";
  if($x != $aktueller_link)
    $navigation .= " <span>&middot;</span>\n<a href=\"gb.php?link=".$x."\">".$x."</a>";
}

//Ggf. "..."
if($anzahl_links - $aktueller_link > 3)
  $navigation .= " <span>&middot;</span>\n<span>...</span>";

//Letzter Link
if($aktueller_link == $anzahl_links) {
  $navigation .= " <span>&middot;</span>\n[<a href=\"gb.php?link=".$anzahl_links."\">".$anzahl_links."</a>]";
} else {
  $navigation .= " <span>&middot;</span>\n<a href=\"gb.php?link=".$anzahl_links."\">".$anzahl_links."</a>";
}*/
?>

<div style="float:left; font-size:14px; font-weight:bold;">
	<?php echo $navigation."\n"; ?>
</div>

<div style="text-align:right; font-size:14px;">
	[&nbsp;<a style="font-weight:bold; text-decoration:none;" href="gb_neuer_eintrag.php">Neuer Eintrag</a>&nbsp;]
</div>

<div style="clear:left;"></div>

<?php

$leerzeile = "<br>\n\n";

//i ist die letzte Zeile der Textdatei, die jetzt angezeigt wird
$i = count($array) - 1 - ($aktueller_link - 1) * 50;
$stop = $i - 50;

//Erzeuge die Tabelleneintraege
for($i; $i>$stop; $i=$i-5) {

  if($i <= 4) break;

  //Entferne Zeilenumbrueche der file()-Funktion
  $array[$i-4] = trim($array[$i-4]); //Nummer
  $array[$i-3] = trim($array[$i-3]); //Name
  $array[$i-2] = trim($array[$i-2]); //Datum & Uhrzeit
  $array[$i-1] = trim($array[$i-1]); //Email
  $array[$i  ] = trim($array[$i  ]); //Text

  //Nummer, Name, Datum & Uhrzeit
  echo $leerzeile;
  echo "<div><span class=\"name\">".$array[$i-4].". | ".$array[$i-3]."</span>".
       " &middot; ".$array[$i-2]."</div>\n";

  //Text
  echo "<p>".$array[$i]."</p>\n\n";

}

?>
</div>
</body>
</html>
