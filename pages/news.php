<?php
include('../src/gbuch.php');
$news = new News("../config/news.txt");
echo "<h2>Meldungen</h2>";
echo $news->load();
?>