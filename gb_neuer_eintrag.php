<?php

$gesendet = $_POST['gesendet'];

//Wird beim Senden des Formulars ausgefuehrt
if($gesendet) {

  //Die uebergebenen Variablen
  $name  = $_POST['name'];
  $email = "";
  $text  = $_POST['text'];

  //Gibt es Worte mit |x|>80 ?
  $text_array = preg_split("# |(\r\n)#", $text);
  foreach($text_array as $wort) {
    if(strlen($wort) > 80) $anzahl_langer_worte++;
  }

  //Fehlerabfang
  if($name === "" || $text === "") {
    echo "Keinen Namen oder keinen Text eingegeben!";
    exit;
  }

  $datum = date("d.m.Y H:i");

  //Bestimme die neue Eintragsnummer
  $anzahl_zeilen  = count(file("gb.txt"));
  $eintrag_nummer = (($anzahl_zeilen - 1) / 5) + 1;

  //Entferne Leerzeilen & Zeilenumbrueche am Anfang und Ende der Strings
  $name  = trim($name);
  $email = trim($email);
  $text  = trim($text);

  //Entferne die php-Slashes
  $name  = stripslashes($name);
  $email = stripslashes($email);
  $text  = stripslashes($text);

  //Setze die Entities (geaendert)
  $name  = htmlentities($name);
  $email = htmlentities($email);
  //$text  = htmlentities($text);
  $text = htmlentities($text, ENT_QUOTES, "UTF-8");

  //Link- und Emailerkennung im Text
  $text = preg_replace("#([a-z0-9][a-z0-9._-]*@[a-z0-9._-]*\.[a-z0-9]+)#i",
                       "<a href=\"mailto:$1\">$1</a>",
                       $text);
  $text = preg_replace("#((http|ftp)://[a-z0-9._-]+\.[a-z0-9/~._-]+)#i",
                       "<a href=\"$1\" target=\"_blank\">$1</a>",
                       $text);
  $text = preg_replace("#((?<!http://|ftp://|stud)www\.[a-z0-9_-]+\.[a-z0-9/~._-]+)#i",
                       "<a href=\"http://$1\" target=\"_blank\">$1</a>",
                       $text);


  //Zeilenumbrueche im Text in html wandeln und Anzahl <=2
  $text = str_replace("\r\n", "<br>", $text);
  while(substr_count($text, "<br><br><br>") > 0) {
    $text = str_replace("<br><br><br>", "<br><br>", $text);
  }

  //Gesamter Eintrag
  $eintrag = $eintrag_nummer."\n".$name."\n".$datum."\n".$email."\n".$text."\n";

  //Oeffne die Datei zum Schreiben ("a" fuer Hintenanhaengen)
  $dateizeiger = fopen("gb.txt", "a");

  if($dateizeiger != NULL) {

    //Schreibe den Eintrag
    fwrite($dateizeiger, $eintrag);

    //Schliesse die Datei
    fclose($dateizeiger);
  }

  header("Location: gb.php");
  exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title></title>
</head>

<body style="width:500px;" onLoad="document.neuer_eintrag.name.focus();">

<h1>Diskussion: Neuer Eintrag</h1>

<form action="gb_neuer_eintrag.php" name="neuer_eintrag" method="post">

<table>
<tr>
	<td>Name</td>
	<td>
		<select name="name" size="1">
			<option value=""></option>
			<option value="Stefan">Stefan</option>
			<option value="Ben">Ben</option>
			<option value="Marcel">Marcel</option>
		</select>
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td>Eintrag&nbsp;</td>
	<td><textarea name="text" style="width:350px;" rows="8"></textarea></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" name="gesendet" value="OK"></td>
</tr>
</table>


</form>
</body>
</html>