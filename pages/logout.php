<?php
session_start();
include '../src/persistenz.php';
if (!isset($_SESSION['benutzer'])){
	exit;	
}
header('location:login.php');
//PersistencyManager::instance()->close();
session_destroy();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Logout</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
	<div id="page">
		<div id="header">
			<h1>Bundesliga Tippspiel</h1>
		</div>
		<div id="menu">
		</div>
		<div id="content">
			Und tschüss
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>