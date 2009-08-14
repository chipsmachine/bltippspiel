<h2>Stammtisch</h2>
<form name="spieltagForm" method="post" action="laberecke.php">
<input type="text" name="gbtext" />
<input type="submit" value="blubber"/>
</form>
<br>
<br></br>
<?php
	require_once('../src/persistenz.php');
	require_once('../src/gbuch.php');
	require_once('../src/views.php');
	
	PersistencyManager::instance()->connect();
	if (isset($_POST['gbtext'])){
		$id = loadUserId($_SESSION['benutzer']);
		$dateTime = date("Y-m-d H:i:s", time());
		$message = new Message($id, $_POST['gbtext'], $dateTime);
		GBuch::instance()->write($message);	
	} 
	GBuch::instance()->load();
	$view = new GBuchDivView(GBuch::instance());
	echo "<form name=\"gbuchForm\" method=\"post\" action=\"laberecke.php\">";
	echo "<select name=\"pages\">";
	for ($i = 0; $i < $view->pages(); $i++){
		echo "<option>".$i."</option>";
	}
	echo "</select>";
	echo "<input type=\"submit\" value=\"laden\"/>";
	echo "</form>";
	if (isset($_POST['pages'])){
		$view->setPage($_POST['pages']);
	}
	$view->show();
	echo "<br clear=\"all\">"; //floating abschalten
?>