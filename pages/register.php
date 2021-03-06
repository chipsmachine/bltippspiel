<?php
	session_start();
	include("../src/persistenz.php");
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	$repassword = $_POST["repassword"];
	
	if (!isset($username) || !isset($password)){
		header('location:register.html');
		echo "Benutzername oder Passwort fehlen";
		exit;
	}
	
	if ($password != $repassword){
		header('location:register.html');
		echo "Tippfehler bei Passworteingabe";
		exit;
	}
	header('location:main.php');
	
	if (!PersistencyManager::instance()->connect())
		$_SESSION['error'] =  "keine Verbindung zur Datenbank hergestellt";
	$benutzer = new Benutzer($username, $password);
	if (!saveUser($benutzer))
		$_SESSION['error'] = "konnte Benutzer nicht in DB anlegen";
	
	$_SESSION['benutzer'] = $username;
?>

