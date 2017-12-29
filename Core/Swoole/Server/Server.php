<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 17:30
 */

namespace Core\Swoole\Server;

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
        $this->serverApi->on('task', [$this, 'onTask']);
        $this->serverApi->on('finish', [$this, 'onFinish']);

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

    }


    /**
     * onClose  [TCP客户端连接关闭后，在worker进程中回调此函数。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onClose($server, $fd, $reactorId)
    {

    }


    /**
     * onTask  [description]
     * @param $server
     * @param $task_id
     * @param $data
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onTask($server, $task_id, $data)
    {

    }


    /**
     * onFinish  [description]
     * @param $server
     * @param $taskId
     * @param $taskObj
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onFinish($server, $taskId, $taskObj)
    {

    }


}
