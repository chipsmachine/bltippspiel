<?php 
	include('../src/gbuch.php');
?>
<form medthod="post" action="admin_news.php">
	<h3>News Meldung bearbeiten</h3>
	<textarea cols="40" rows="20" name="ns">
	<?php 
		$news = new News("../config/news.txt");
		echo $news->load();
	?>
	</textarea>
	<input type="submit" value="ändern"/>
</form>
<?php 
	//ka warum hier ums verrecken kein post funzt ?
	if (isset($_GET['ns'])){
		$news = new News("../config/news.txt");
		$news->write($_GET['ns']);
	}
?>


