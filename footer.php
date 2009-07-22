
<?php 
	if(isset($_SESSION['benutzer'])){
		$benutzer = $_SESSION['benutzer'];
		echo "Benutzer: " . $benutzer . "<br>";
	}
?>