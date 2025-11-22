<?php
define('BASE_PATH', __DIR__ . '/');

require_once BASE_PATH . 'app/core/DBConnection.php';
require_once BASE_PATH . 'app/core/SessionManager.php';
require_once BASE_PATH . 'libs/Controller.php';
require_once BASE_PATH . 'libs/View.php';
require_once BASE_PATH . 'libs/Model.php';
require_once BASE_PATH . 'libs/App.php';

$app = new App();

?>