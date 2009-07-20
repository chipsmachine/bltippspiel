<?php
  $script = trim(basename($_SERVER['PHP_SELF']));
?>

<a href="index.php" style="color:blue; text-decoration:none;<?php if($script == "index.php") echo " font-weight:bold" ?>">Ranking</a><br>
<a href="eintragen.php" style="color:blue; text-decoration:none;<?php if($script == "eintragen.php") echo " font-weight:bold;" ?>">Tipp abgeben</a><br>
<a href="uebersicht.php" style="color:blue; text-decoration:none;<?php if($script == "uebersicht.php") echo " font-weight:bold;" ?>">&Uuml;bersicht</a><br>
<a href="gb.php" style="color:blue; text-decoration:none;<?php if($script == "gb.php") echo " font-weight:bold;" ?>">Diskussion</a><br>
<br><br>
