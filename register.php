<?php
	session_start();
	include("persistenz.php");
	
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
	
	$dbUrl = "127.0.0.1:3306";
	$persManager = PersistenzManager::instance();
	if (!$persManager->connect($dbUrl, "root", "tipp"))
		echo "keine Verbindung zur Datenbank hergestellt";
	
	$benutzerManager = BenutzerManager::instance();
	$benutzerManager->setPersistenzManager($persManager);
	
	$benutzer = new Benutzer($username, $password);
	if (!$benutzerManager->saveBenutzer($benutzer))
		echo "konnte Benutzer nicht in DB anlegen";
	
	$_SESSION['username'] = $username;
?>

