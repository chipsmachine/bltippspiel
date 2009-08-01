
<div id="menueintrag"><a href="/pages/tabelle.php">Tabelle</a></div>
<div id="menueintrag"><a href="/pages/spieltag.php">Spieltag</a></div>
<div id="menueintrag"><a href="/pages/logout.php">Logout</a></div>
<?php
	if (isset($_SESSION['role'])){
		if ($_SESSION['role'] == 2){
			echo "<br><br>";
			echo "<div id=" . "'" . "menueintrag" . "'" . "><a href=admin.php>Admin</a></div>"; 
		}
	}
?>
