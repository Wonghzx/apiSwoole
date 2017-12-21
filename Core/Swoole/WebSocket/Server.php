<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21/021
 * Time: 18:04
 */

namespace Core\Swoole\WebSocket;
class Server
{
    private static $instance;

    private $conf;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function serverStart($server, $conf)
    {
        $this->conf = $conf;
        $server->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode' => 1
        ]);

        $server->on('open', [$this, 'onOpen']);
        $server->on('message', [$this, 'onMessage']);
        $server->on('close', [$this, 'onClose']);

        $server->start();
    }

    public function onOpen($server, $request)
    {
        echo 123;
    }

    public function onMessage($server, $frame)
    {

    }


    public function onClose($server, $frame)
    {

    }
}