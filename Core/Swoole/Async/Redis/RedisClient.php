<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16/016
 * Time: 11:31
 */

namespace Core\Swoole\Async\Redis;

class RedisClient
{

    public $host;

    public $port;

    public $debug = false;

    /**
     * 空闲连接池
     * @var array
     */
    public $pool = [];

    public function __construct($host = 'localhost', $port = 6379, $timeout = 0.1)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function trace($msg)
    {
        echo "-----------------------------------------\n" . trim($msg) . "\n-----------------------------------------\n";
    }

    public function stats()
    {
        $stats = "Idle connection: " . count($this->pool) . "<br />\n";
        return $stats;
    }

    public function hmset($key, array $value, $callback)
    {
        $lines[] = "hmset";
        $lines[] = $key;
        foreach ($value as $k => $v) {
            $lines[] = $k;
            $lines[] = $v;
        }
        $connection = $this->getConnection();
        $cmd = $this->parseRequest($lines);
        $connection->command($cmd, $callback);
    }

    public function hmget($key, array $value, $callback)
    {
        $connection = $this->getConnection();
        $connection->fields = $value;

        array_unshift($value, "hmget", $key);
        $cmd = $this->parseRequest($value);
        $connection->command($cmd, $callback);
    }

    public function parseRequest($array)
    {
        $cmd = '*' . count($array) . "\r\n";
        foreach ($array as $item) {
            $cmd .= '$' . strlen($item) . "\r\n" . $item . "\r\n";
        }
        return $cmd;
    }

    public function __call($method, array $args)
    {
        $callback = array_pop($args);
        array_unshift($args, $method);
        $cmd = $this->parseRequest($args);
        $connection = $this->getConnection();
        $connection->command($cmd, $callback);
    }

    /**
     * 从连接池中取出一个连接资源
     * @return RedisConnection
     */
    protected function getConnection()
    {
        if (count($this->pool) > 0) {
            /**
             * @var $connection RedisConnection
             */
            foreach ($this->pool as $k => $connection) {
                unset($this->pool[$k]);
                break;
            }
            return $connection;
        } else {
            return new RedisConnection($this);
        }
    }

    function lockConnection($id)
    {
        unset($this->pool[$id]);
    }

    function freeConnection($id, RedisConnection $connection)
    {
        $this->pool[$id] = $connection;
    }

}