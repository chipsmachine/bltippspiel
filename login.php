<?php
	session_start();
	include 'persistenz.php';
	
	$login = $_POST['login'];
	
	if (isset($login)){
		$username = $_POST["username"];
		$password = $_POST["password"];

		if (!isset($username) || !isset($password)){
			header('location:login.php');
			print("kein Benutzername oder Passwort angegeben");
			exit;
		}
		//TODO: Benutzerdaten aus DB holen und mit Eingabe vergleichen!!
		$dbUrl = "127.0.0.1:3306";
		$persManager = PersistenzManager::instance();
		$persManager->connect($dbUrl, "bltippdb", "root", "");

		//$benutzerManager = BenutzerManager::instance();
		//$benutzerManager->setPersistenzManager($persManager);

		//$benutzer = $benutzerManager->loadBenutzer($username);
		
		$return = $persManager->query("SELECT * FROM benutzer");
		
		if (!is_array($return)){
			echo "kein array";
		}
		//$_SESSION['error'] = $benutzer;
		/*if ($benutzer){
		 if ($password == $benutzer->password){
			header('location:main.php');
			$_SESSION['username'] = $username;
			}
			header('location:login.php');
			$_SESSION['error'] = "Passwort falsch";
			}
			else {
			header('location:login.php');
			$_SESSION['error'] = "konnte Benutzer nicht laden";
			}*/
	}
	//echo "Benutzer $username hat sich mit Passwort $password eingeloggt";	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Login</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
	<div id="page">
		<div id="header">
			<h1>Bundesliga Tippspiel</h1>
		</div>
		<div id="content">
			<form action="login.php" method="post" name="loginForm">
				Benutzername:<br>
				<input type="text" size="20" maxlength="30" name="username" value="" />
				<br>
				Passwort:<br>
				<input type="password" size="20" maxlength="30" name="password" value="" />
				<br>
				<input type="submit" name="login" value="Login" />
			</form>
			<a href="register.html">Registrieren</a>	
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>

