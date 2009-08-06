<?php
session_start();
if (!isset($_SESSION['benutzer'])){
	header('location:login.php');	
}
include('../src/persistenz.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Admin</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
	<div id="page">
		<?php include('header.php');?>
		<div id="menu">
			<?php include('menu.php');?>
		</div>
		<div id="content">
			<?php include('new_spieltag.php');?>
		</div>
		<?php include('footer.php');?>
	</div>
</body>
</html>