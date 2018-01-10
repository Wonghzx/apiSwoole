<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 17:30
 */

namespace Core\Swoole\Server;

use Core\Event;
use Core\Swoole\Process\MainProcess;

class Server
{
    private static $instance;

    private $serverApi;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    public function serverStart($server, $conf)
    {
        $this->serverApi = $server;
        $this->serverApi->set($conf->get('setting'));

        // 设置事件监听
        $this->serverApi->on('connect', [$this, 'onConnect']);
        $this->serverApi->on('receive', [$this, 'onReceive']);
        $this->serverApi->on('close', [$this, 'onClose']);

        Event::getInstance()->onSetServer($this->serverApi);
        $this->serverApi->start();
    }


    /**
     * onConnect  [有新的连接进入时，在worker进程中回调。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onConnect($server, $fd, $reactorId)
    {

    }


    /**
     * onReceive  [接收到数据时回调此函数，发生在worker进程中。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onReceive($server, $fd, $reactor_id, $data)
    {
       print_r($reactor_id);
    }


    /**
     * onClose  [TCP客户端连接关闭后，在worker进程中回调此函数。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onClose($server, $fd, $reactorId)
    {

    }
}
