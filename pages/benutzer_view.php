
<form action="benutzer.php" method="post" enctype="multipart/form-data">
  <input type="file" name="att"></input>
  <input type="submit" value="submit"></input>
</form> 

<?php
//PersistencyManager::instance()->connect();
$path = "../res/img/user";
print_r($_POST);
if (isset($submit) && $submit == "submit"){
	echo "da kam was";
}
else {
	echo "kam nix";
}
?>