<div id="menueintrag"><a href="tipps.php">Tipps ansehen</a></div>
<div id="menueintrag"><a href="tabelle.php">Tabelle</a></div>
<div id="menueintrag"><a href="spieltag.php">Spieltag</a></div>
<div id="menueintrag"><a href="benutzer.php">Einstellungen</a></div>
<div id="menueintrag"><a href="laberecke.php">Laberecke</a></div>
<div id="menueintrag"><a href="logout.php">Logout</a></div>
<?php
	if (isset($_SESSION['role'])){
		if ($_SESSION['role'] == 2){
			echo "<br><br>";
			echo "<div id=" . "'" . "menueintrag" . "'" . "><a href=admin.php>Admin</a></div>"; 
		}
	}
?>
