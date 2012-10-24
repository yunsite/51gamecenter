<?php
class core_cache
{
    //memcache缺省生存期
    const MEMCACHE_LIFETIME = 3600;

    //超时
    const MEMCACHE_TIMEOUT = 3;

    //cache名
    public $name;

    //服务器列表 三台线上wap机器
    public $serverlist = array(
        '10.10.8.154:11556' => 1,
        '10.10.8.157:11556' => 1,
        //'10.10.8.158:11556' => 1
    );

    public $currserver;

    public $memObj;

    public function __construct($name = '')
    {
    	if (is_string($name) && strlen($name)) {
        	$this->name = $name;
        }
    }

    public function __destruct()
    {
    	$this->close();
    }

    function setname($name)
    {
        $this->name = $name;
    }

    protected function getServer($name = '')
    {
        $hashkey = hexdec(substr(md5($name), 0, 2));
        $index = $hashkey % count($this->serverlist);
        $serverlist = array_keys($this->serverlist);
        return $serverlist[$index];
    }

    function connect2Server()
    {
        while (!empty($this->serverlist)) {
            $this->currserver = $this->getServer($this->name);
            $server = explode(':', $this->currserver);

        	//先关闭前一次连接
        	$this->close();
        	$this->memObj = new Memcache;
            $ret = $this->memObj->connect($server[0], $server[1], self::MEMCACHE_TIMEOUT);

            if ($ret) {
            	//连接成功
	            return array(
	                'flag' => 0,
	                'reason' => 'valid memcache server',
	                'server' => $this->currserver
	            );
            } else {
            	//连接失败，尝试另外一台
                unset($this->serverlist[$this->currserver]);
            }
        }
        return array(
            'flag' => 1,
            'reason' => 'all memcache servers down'
        );
    }

    function get($key_array)
    {
        if (empty($key_array) || (count($key_array) == 0)) {
            return array(
                'flag' => 1,
                'reason' => 'invalid keys'
            );
        }

        return array(
            'flag' => 0,
            'reason' => $this->memObj->get($key_array),
            'server' => $this->currserver
        );
    }

    function set($content_array)
    {
        if (!isset($content_array['key']) || !isset($content_array['value']) || is_resource($content_array['value'])) {
            return array(
                'flag' => 1,
                'reason' => 'invalid options'
            );
        }

        if (!is_numeric($content_array['expire_time']) || 0 >= $content_array['expire_time']) {
            $content_array['expire_time'] = self::MEMCACHE_LIFETIME;
        }

        $ret = $this->memObj->set($content_array['key'], $content_array['value'], 0, intval($content_array['expire_time']));

        if (!$ret) {
            return array(
                'flag' => 1,
                'reason' => $content_array['key'] . ' -> ' . $content_array['value'] . ' set failed.',
                'server' => $this->currserver
            );
        } else {
            return array(
                'flag' => 0,
                'reason' => 'set succ.',
                'server' => $this->currserver
            );
        }
    }

    function add($content_array)
    {
        if (empty($content_array['key']) || empty($content_array['value']) || is_resource($content_array['value'])) {
            return array(
                'flag' => 1,
                'reason' => 'invalid options'
            );
        }
        $ret = $this->memObj->add($content_array['key'], $content_array['value'], 0, intval($content_array['expire_time']));

        if (!$ret) {
            return array(
                'flag' => 1,
                'reason' => 'already exists the key, add failed.',
                'server' => $this->currserver
            );
        } else {
            return array(
                'flag' => 0,
                'reason' => 'add succ.',
                'server' => $this->currserver
            );
        }
    }

    function delete($content_array)
    {
        $ret = $this->memObj->delete($content_array['key'], intval($content_array['expire_time']));

        if (!$ret) {
            return array(
                'flag' => 1,
                'reason' => 'delete failed.',
                'server' => $this->currserver
            );
        } else {
            return array(
                'flag' => 0,
                'reason' => 'delete succ.',
                'server' => $this->currserver
            );
        }
    }

    function flush()
    {
        $ret = $this->memObj->flush();

        if (!$ret) {
            return array(
                'flag' => 1,
                'reason' => 'flush failed.',
                'server' => $this->currserver
            );
        } else {
            return array(
                'flag' => 0,
                'reason' => 'flush succ.',
                'server' => $this->currserver
            );
        }
    }

    function replace($content_array)
    {
        if (empty($content_array['key']) || empty($content_array['value']) || is_resource($content_array['value'])) {
            return array(
                'flag' => 1,
                'reason' => 'invalid options'
            );
        }
        $ret = $this->memObj->replace($content_array['key'], $content_array['value'], 0, intval($content_array['expire_time']));

        if (!$ret) {
            return array(
                'flag' => 1,
                'reason' => $content_array['key'] . ' does not exists, replace failed.',
                'server' => $this->currserver
            );
        } else {
            return array(
                'flag' => 0,
                'reason' => 'replace succ.',
                'server' => $this->currserver
            );
        }
    }

    function close()
    {
        if (is_resource($this->memObj)) {
            $this->memObj->close();
        }
    }
}