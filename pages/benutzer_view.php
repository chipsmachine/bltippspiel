<h3>Bild hochladen</h3>
(Bilder < 100x100 px)
<form action="benutzer.php" method="post" enctype="multipart/form-data">
  <input class="text" type="file" name="att"></input>
  <input type="submit" value="submit"></input>
</form> 

<?php
$maxSize = 20480; // Bytes
PersistencyManager::instance()->connect();
$path = "../res/img/user";
print_r($_FILES['att']);
if (!empty($_FILES['att']['name'])){
	if ($_FILES['att']['size'] > $maxSize)
		exit();
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
?>
<h3>Benutzernamen</h3>