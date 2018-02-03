<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/17/017
 * Time: 10:59
 */

namespace Core\Swoole\Async\Redis;

use Core\Component\Error\Trigger;

class PubSubRedis
{
    private static $instance;

    private $client;

    public $redisPool;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PubSubRedis();
        }
        return self::$instance;
    }

//
    public function __construct()
    {
        $this->reconnect();
    }

    public function reconnect($client = null)
    {
        if ($this->client === null) {
            $this->client = new \swoole_redis();
        }

        $host = getConf('redis.host', '127.0.0.1');
        $port = getConf('redis.port', 6379);

        $this->client->on('message', [$this, 'onMessage']);
        $this->client->on('close', [$this, 'onClose']);
        $this->client->connect($host, $port, [$this, 'connectCallback']);
    }


    /**
     * connectCallback  [连接成功后回调的函数]
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
                        $this->redisPool = $client;
                        $this->redisPool->psubscribe('*');
                    });

                } else {
                    $client->isClose = false;
                    $this->redisPool = $client;
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
                    $this->redisPool = $client;
                    $this->redisPool->psubscribe('*');
                });
            } else {
                $client->isClose = false;
                $this->redisPool = $client;
            }
        }

    }


    public function onMessage(\swoole_redis $redis, array $message)
    {
//        print_r($message);
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
}