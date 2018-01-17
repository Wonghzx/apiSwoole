<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16/016
 * Time: 12:04
 */

namespace Core\Swoole\Async;

abstract class AsyncPool implements IAsyncPool
{
    protected $pool;

    protected $commands;

    protected $clients;

    protected $client_count;

    protected $client_max_count;

    public function __construct()
    {
        $this->pool = new \SplQueue();
        $this->commands = new \SplQueue();
    }

    public function shiftFromPool($data)
    {
        if ($this->pool->count() == 0) {
            $this->commands->push($data);
            return false;
        } else {
            $client = $this->pool->shift();
            $this->clients[$data['token']] = $client;
            return $client;
        }
    }

    public function pushToPool($client)
    {
        $this->pool->unshift($client);
        if (count($this->commands) > 0) {//有残留的任务
            $command = $this->commands->shift();
            $this->execute($command);
        }
    }

    /**
     * 准备一个
     */
    public function prepareOne()
    {
        if ($this->client_count >= $this->client_max_count) {
            return false;
        }
        $this->client_count++;
        return true;
    }
}