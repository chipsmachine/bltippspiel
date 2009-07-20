<?php
	session_start();
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	if (!isset($username) || !isset($password)){
		echo "kein Benutzername oder Passwort angegeben";
		header('location:login.html');
	}
	
	//TODO: Benutzerdaten aus DB holen und mit Eingabe vergleichen!!
	
	if ($username == "bernd"){
		if ($password == "bs"){
			header('location:main.php');
			$_SESSION['username'] = $username;
		}
	}
	else {
		echo "Benutzername oder Passwort falsch";
		header('location:login.html');	
	}
	//echo "Benutzer $username hat sich mit Passwort $password eingeloggt";	
?>