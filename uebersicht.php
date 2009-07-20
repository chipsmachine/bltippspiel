<html>
<head>
<title>EM 2008</title>
</head>

<body>

<?php include("menue.php"); ?>

<div style="width:300px;">

<?php
	$log = file("log.txt");
	foreach($log as $item) {
		echo $item."<br>";
	}
?>
</div>
</body>
</html>