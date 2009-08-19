<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Main</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
	<div id="page">
		<div id="content">
		<?php
		include '../src/persistenz.php';
		include '../src/views.php';

		PersistencyManager::instance()->connect();

		$data = array();

		$users = loadAllUser();

		$header = array('id', 'name', 'role');
		for ($i = 0; $i < sizeof($users); $i++){
			$user = array_values($users[$i]);
			$row = array();
			for ($j = 0; $j < sizeof($user); $j++){
				$row[$j] = $user[$j];
			}
			$data[$i] = $row;
		}

		$model = new TableModel(sizeof($users), sizeof($users[0]));
		$model->setData($data);
		$model->setHeader($header);

		$view = new TableView($model);
		$view->setAttribute("td_class","produkt");
		$view->draw();
		?>
		</div>
	</div>
</body>
</html>

