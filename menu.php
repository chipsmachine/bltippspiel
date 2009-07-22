
<div id="menueintrag"><a href="tabelle.php">Tabelle</a></div>
<div id="menueintrag"><a href="spieltag.php">Spieltag</a></div>
<div id="menueintrag"><a href="benutzer.php">Benutzer</a></div>
<div id="menueintrag"><a href="logout.php">Logout</a></div>
<?php
	if (isset($_SESSION['role'])){
		if ($_SESSION['role'] == 2){
			echo "<br><br>";
			echo "<div id=" . "'" . "menueintrag" . "'" . "><a>Admin</a></div>"; 
		}
	}
?>
