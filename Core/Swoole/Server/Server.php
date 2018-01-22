<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 17:30
 */

namespace Core\Swoole\Server;

use Core\Component\Bean\Container;
use Core\Event;
use Swoole\Server AS Ser;

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
        $this->serverApi->on('workerStart', [$this, 'onWorkerStart']);
        $this->serverApi->on('workerStop', [$this, 'onWorkerStop']);
        $this->serverApi->on('pipeMessage', [$this, 'onPipeMessage']);

        Event::getInstance()->onSetServer($this->serverApi);
        $this->serverApi->start();
    }


    /**
     * onConnect  [有新的连接进入时，在worker进程中回调。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onConnect(Ser $server, $fd, $reactorId)
    {
        Container::getDispatcherService()->doConnect($server, $fd, $reactorId);
    }


    /**
     * onReceive  [接收到数据时回调此函数，发生在worker进程中。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onReceive(Ser $server, int $fd, int $fromId, string $data)
    {
        Container::getDispatcherService()->doDispatcher($server, $fd, $fromId, $data);
    }


    /**
     * onClose  [TCP客户端连接关闭后，在worker进程中回调此函数。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onClose(Ser $server, int $fd, int $reactorId)
    {
        Container::getDispatcherService()->doClose($server, $fd, $reactorId);
    }


    /**
     * onWorkerStart  [description]
     * @param $server
     * @param int $workerId
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onWorkerStart(Ser $server, int $workerId)
    {
        Event::getInstance()->onWorkerStart($server, $workerId);
    }

    /**
     * onWorkerStop  [description]
     * @param $server
     * @param int $workerId
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onWorkerStop(Ser $server, int $workerId)
    {
        Event::getInstance()->onWorkerStop($server, $workerId);
    }

    /**
     * onPipeMessage  [description]
     * @param $server
     * @param int $fromWorkerId
     * @param string $message
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onPipeMessage(Ser $server, int $fromWorkerId, string $message)
    {

    }
}
