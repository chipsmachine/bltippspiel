<?php
session_start();
include 'persistenz.php';
if (!isset($_SESSION['benutzer'])){
	exit;	
}
//PersistenzManager::instance()->close();
session_destroy();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
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