<?php
$config= require "wb_config_inc.php";
$array = array(
	 //'URL_ROUTER_ON' => false, // 开启路由转换
	 'URL_MODEL'      => 0, 
	 'HTML_CACHE_ON'=>false,
	 'DB_CHARSET'            => '', 
);
return array_merge($config,$array);
?>