<?php
namespace WebIM\Store;

class Redis
{
    /**
     * @var \redis
     */
    protected $redis;

    static $prefix = "webim_";

    function __construct($host = '127.0.0.1', $port = 6379, $timeout = 0.0)
    {
        $redis = new \redis;
        $redis->connect($host, $port, $timeout);
        $this->redis = $redis;
    }

    function login($client_id, $info)
    {
        $this->redis->set(self::$prefix.'client_'.$client_id, serialize($info));
        $this->redis->sAdd(self::$prefix.'online', $client_id);
    }

    function getUser($uid)
    {
        $key = self::$prefix . 'user_' . $uid;
        $ret = $this->redis->get($key);
        $info = unserialize($ret);
        return $info;
    }
}
