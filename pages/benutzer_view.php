<h2>Bild hochladen</h2>
(an alle noobs Bilder > 100x100 px sprengen das Design!!!)
<form action="benutzer.php" method="post" enctype="multipart/form-data">
  <input type="file" name="att"></input>
  <input type="submit" value="submit"></input>
</form> 

<?php
PersistencyManager::instance()->connect();
$path = "../res/img/user";

if (!empty($_FILES['att']['name'])){
	if (move_uploaded_file($_FILES['att']['tmp_name'], $path . "/".$_FILES['att']['name'])){
		$userId = loadUserId($_SESSION['benutzer']);
		if (updateUserPicture($path . "/".$_FILES['att']['name'], $userId))
			echo "Datei erfolgreich hochgeladen";
		else
			echo "Datei konnte nicht gespeichert werden";
	}
	else
		echo "Datei konnte nicht hochegeladen werden";
}

echo "<h2>Benutzernamen</h2>";
?>