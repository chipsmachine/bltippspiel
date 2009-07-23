<?php
	session_start();
	include("/src/persistenz.php");
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	$repassword = $_POST["repassword"];
	
	if (!isset($username) || !isset($password)){
		header('location:/pages/register.html');
		echo "Benutzername oder Passwort fehlen";
		exit;
	}
	
	if ($password != $repassword){
		header('location:/pages/register.html');
		echo "Tippfehler bei Passworteingabe";
		exit;
	}
	header('location:/pages/main.php');
	
	$connection = PersistenzManager::instance()->connect();
	if (!$connection)
		$_SESSION['error'] =  "keine Verbindung zur Datenbank hergestellt";
	$benutzer = new Benutzer($username, $password);
	if (!saveBenutzer($benutzer))
		$_SESSION['error'] = "konnte Benutzer nicht in DB anlegen";
	
	$_SESSION['benutzer'] = $username;
	$_SESSION['dbcon'] = $connection;
?>

