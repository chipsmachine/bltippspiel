<h2>Stammtisch</h2>
<form name=spieltagForm method=post action=laberecke.php>
<input type=text name=gbtext/>
<input type=submit value="blubber"/>
</form>
<br>
<br></br>
<?php
	if (isset($_POST['gbtext'])){
		
	} 
	include('laberecke_view.php');
?>