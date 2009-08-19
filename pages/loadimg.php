<?php
include '../../src/persistenz.php';
PersistencyManager::instance()->connect();
if (isset($_GET['user'])){
	print "user";
}
else if (isset($_GET['verein'])){
	print "verein";
}
?>