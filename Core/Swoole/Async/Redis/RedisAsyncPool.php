<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/17/017
 * Time: 10:59
 */

namespace Core\Swoole\Async\Redis;

use Core\Component\Error\Trigger;
use Core\Swoole\Async\AsyncPool;

class RedisAsyncPool extends AsyncPool
{
    private $client;

    private static $instance;

    public $redisPool;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    public function __call($name, $arguments)
    {
        $callback = array_pop($arguments);
        $data = [
            'name' => $name,
            'arguments' => $arguments
        ];
    }

    public function reconnect($client = null)
    {
        if ($this->client === null) {
            $this->client = new \swoole_redis();
        }

        $this->client->on('message', [$this, 'onMessage']);
        $this->client->on('close', [$this, 'onClose']);
        $this->client->connect('127.0.0.1', 6379, [$this, 'connectCallback']);
    }


    /**
     * connectCallback  [$callback: 连接成功后回调的函数]
     * @param $client
     * @param $result
     */
    public function connectCallback(\swoole_redis $client, $result)
    {
        if (!$result) {
            Trigger::exception($client->errMsg);
        }

        $redisPassword = getConf('redis.password');

        if (getConf('redis.auth')) {
            $client->auth($redisPassword, function (\swoole_redis $client, $result) {
                if (!$result) {
                    $errMsg = $client->errMsg;
//                    unset($client);
                    Trigger::exception($errMsg);
                }
                //认证通过

                if (getConf()->has('redis.select')) {//存在select
                    $redisSelect = getConf('redis.select');
                    $client->select($redisSelect, function ($client, $result) {
                        if (!$result) {
                            Trigger::exception($client->errMsg);
                        }
                        $client->isClose = false;
                        $this->pushToPool($client);
                    });

                } else {
                    $client->isClose = false;
                    $this->pushToPool($client);
                }
            });
        } else {
            if (getConf()->has('redis.select')) {//存在select
                $redisSelect = getConf('redis.select');
                $client->select($redisSelect, function ($client, $result) {
                    if (!$result) {
                        Trigger::exception($client->errMsg);
                    }
                    $client->isClose = false;
                    $this->pushToPool($client);
                });
            } else {
                $client->isClose = false;
                $this->pushToPool($client);
            }
        }
    }


    public function onMessage(\swoole_redis $redis, array $message)
    {

    }


    /**
     * onConnect  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onClose($client)
    {
        $client->close();
    }

    public function execute($data)
    {
        // TODO: Implement execute() method.
    }

    public function abc()
    {
        print_r($this->pool);
    }
}