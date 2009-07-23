<?php
session_start();
if (!isset($_SESSION['benutzer'])){
	header('location:/pages/login.php');	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Main</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
	<div id="page">
		<div id="header">
			<?php include('/pages/header.php');?>
		</div>
		<div id="menu">
			<?php include('/pages/menu.php');?>
		</div>
		<div id="content">

		</div>
		<div id="footer">
			<?php include('/pages/footer.php');?>
		</div>
	</div>
</body>
</html>