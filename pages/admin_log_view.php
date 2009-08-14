<?php
require('../src/log.php');
require('../src/views.php');
PersistencyManager::instance()->connect();
Log::instance()->load();
$view = new LogTableView(Log::instance());
$view->show();
?>