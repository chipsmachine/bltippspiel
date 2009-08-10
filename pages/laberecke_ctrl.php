<h2>Stammtisch</h2>
<form name="spieltagForm" method="post" action="laberecke.php">
<input type="text" name="gbtext" />
<input type="submit" value="blubber"/>
</form>
<br>
<br></br>
<?php
	require_once('../src/gbuch.php');
	
	PersistencyManager::instance()->connect();
	print_r($_POST);
	if (isset($_POST['gbtext'])){
		$id = loadUserId($_SESSION['benutzer']);
		$dateTime = date("Y-m-d H:i:s", time());
		$message = new Message($id, $_POST['gbtext'], $dateTime);
		GBuch::instance()->write($message);	
	} 
	GBuch::instance()->load();
	$view = new GBuchDivView(GBuch::instance());
	$view->show();
?>