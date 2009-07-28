<?php
	session_start();
	include '../src/persistenz.php';
	
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
				$_SESSION['id'] = $benutzer->id;
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
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

