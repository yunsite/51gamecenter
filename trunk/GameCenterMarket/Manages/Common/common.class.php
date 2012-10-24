<?php
class Common{
	//memcache instance
	private static $_oWapCache;
	//加载内核文件，私有
	static private function _loadCore($filepath)
	{
		require_once ROOT_PATH . '/Manages/Common/'.$filepath.'.php';
	}
	//cache初始化，私有
	static private function _initCache($cacheName)
	{
		self::_loadCore('cache');
		if (!is_object(self::$_oWapCache)) {
			self::$_oWapCache = new core_cache();
		}
		self::$_oWapCache->setname($cacheName);
		self::$_oWapCache->connect2Server();
		return $cacheName . '_key';
	}
	
	//取cache
	static public function getCache($cacheName)
	{
		$keyName = self::_initCache($cacheName);
		error_log($keyName."\n", 3, ROOT_PATH.'/log/cache_key.log');
		$cache = self::$_oWapCache->get($keyName);
		self::$_oWapCache->close();
		if (0 == $cache['flag'] && false !== $cache['reason']) {
			return $cache['reason'];
		} else {
			return false;
		}
	}
	
	//写cache
	static public function setCache($cacheName, $value, $expire_time = NULL)
	{
		$keyName = self::_initCache($cacheName);
		//未设置超时,则默认cache至当天0点
		if (!isset($expire_time)) {
			$expire_time = strtotime(date('Y-m-d')) + 86400 - time();
		}
		self::$_oWapCache->set(array(
				'key' => $keyName,
				'value' => $value,
				'expire_time' => $expire_time
		));
		self::$_oWapCache->close();
	}
	
	//清cache
	static public function delCache($cacheName)
	{
		$keyName = self::_initCache($cacheName);
		self::$_oWapCache->delete(array(
				'key' => $keyName
		));
		self::$_oWapCache->close();
	}
}

?>