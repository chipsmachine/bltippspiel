<?php
session_start();
if (!isset($_SESSION['benutzer'])){
	header('location:login.php');	
}
require_once('../src/persistenz.php');
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
		<?php include('header.php');?>
		<div id="menu">
			<?php include('menu.php');?>
		</div>
		<div id="content">
			<?php include('benutzer_view.php');?>
		</div>
		<?php include('footer.php');?>
	</div>
</body>
</html>