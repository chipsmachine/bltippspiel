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
		//TODO: Datenbank login aus config Datei lesen
		/*$dbUrl = "127.0.0.1:3306";
		$connection = PersistenzManager::connect($dbUrl, "bltippdb", "root", "");*/
		PersistenzManager::instance()->connect();
		
		$benutzer = loadBenutzer($username);
		
		if ($benutzer != NULL){
			if ($benutzer->password == $password){
				header('location:main.php');
				$_SESSION['benutzer'] = $benutzer->name;
				$_SESSION['role'] = $benutzer->role;
				$_SESSION['dbcon'] = $connection;
			}
			else{
				header('location:login.php');
				$_SESSION['error'] = "Passwort falsch";
			}
		}
		else{
			header('location:login.php');
			$_SESSION['error'] = "konnte Benutzer nicht laden";
		}
	}
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
			<?php
				if (isset($_SESSION['error']))
					echo $_SESSION['error'];
			?>
		</div>
	</div>
</body>
</html>

