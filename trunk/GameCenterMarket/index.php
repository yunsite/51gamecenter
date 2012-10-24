<?php
define("ROOT_PATH", realpath($_SERVER['DOCUMENT_ROOT']));
define('THINK_PATH', './ThinkPHP');
define('APP_NAME', 'Manages');
define('APP_PATH', './Manages');
define('RUNTIME_PATH','./Cache/');
require(THINK_PATH."/ThinkPHP.php");
APP::run();
?>