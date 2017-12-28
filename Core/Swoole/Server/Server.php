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
        $this->serverApi->on('start', [$this, 'onStart']);
        $this->serverApi->on('workerStart', [$this, 'onWorkerStart']);
        $this->serverApi->on('workerStop', [$this, 'onWorkerStop']);
        $this->serverApi->on('task', [$this, 'onTask']);
        $this->serverApi->on('finish', [$this, 'onFinish']);
        $this->serverApi->on('request', [$this, 'onRequest']);
        $this->serverApi->on('pipeMessage', [$this, 'onPipeMessage']);

        $this->serverApi->start();
    }


    /**
     * onConnect  [有新的连接进入时，在worker进程中回调。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onConnect()
    {
        $this->serverApi->on('Connect', function (\swoole_server $server, $fd, $reactorId) {
        });
    }


    /**
     * onReceive  [接收到数据时回调此函数，发生在worker进程中。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onReceive()
    {
        $this->serverApi->on('Receive', function (\swoole_server $server, $fd, $reactor_id, $data) {

        });
    }


    /**
     * onClose  [TCP客户端连接关闭后，在worker进程中回调此函数。函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onClose()
    {
        $this->serverApi->on('Close', function (\swoole_server $server, $fd, $reactorId) {

        });
    }

    /**
     * workerStartEvent  [此事件在Worker进程/Task进程启动时发生。这里创建的对象可以在进程生命周期内使用。原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function workerStartEvent()
    {
        $this->serverApi->on("workerStart", function (\swoole_server $server, $workerId) {

        });
    }


    /**
     * onTaskEvent  [
     * 在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。
     * 当前的Task进程在调用onTask回调函数时会将进程状态切换为忙碌，
     * 这时将不再接收新的Task，当onTask函数返回时会将进程状态切换为空闲然后继续接收新的Task
     * ]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onTaskEvent()
    {
        $this->serverApi->on('task', function (\swoole_server $server, $task_id, $data) {
//            print_r($server);
        });
    }

    /**
     * onFinishEvent  [当worker进程投递的任务在task_worker中完成时，task进程会通过swoole_server->finish()方法将任务处理的结果发送给worker进程。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onFinishEvent()
    {
        $this->serverApi->on('finish', function (\swoole_server $server, $taskId, $taskObj) {
//            print_r($server);
        });
    }

    public function onStart()
    {
        $this->serverApi->start();
    }


}
