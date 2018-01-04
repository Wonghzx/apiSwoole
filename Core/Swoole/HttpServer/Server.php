<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8/008
 * Time: 11:21
 */

namespace Core\Swoole\HttpServer;

use Core\Component\Error\Trigger;
use Core\Event;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Response;
use Swoole\Http\Request AS Req;
use Swoole\Http\Response AS Res;


class Server
{
    private $serverApi;

    protected static $instance;


    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function serverStart($server, $conf)
    {
        $this->serverApi = $server;

        $this->serverApi->set($conf->get('setting')); //设置运行时参数

        // 设置事件监听
        $this->serverApi->on('workerStart', [$this, 'onWorkerStart']);
        $this->serverApi->on('workerStop', [$this, 'onWorkerStop']);
        $this->serverApi->on('task', [$this, 'onTask']);
        $this->serverApi->on('finish', [$this, 'onFinish']);
        $this->serverApi->on('request', [$this, 'onRequest']);
        $this->serverApi->on('pipeMessage', [$this, 'onPipeMessage']);

        Event::getInstance()->onSetServer($this->serverApi);
        $this->serverApi->start();
    }


    /**
     * onWorkerStart  [worker进程启动前初始化]
     * @param $server
     * @param int $workerId
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onWorkerStart($server, int $workerId)
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
    public function onWorkerStop($server, int $workerId)
    {
        Event::getInstance()->onWorkerStop($server, $workerId);
    }


    /**
     * onRequest  [监听http请求]
     * @param Req $request
     * @param Res $response
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onRequest(Req $request, Res $response)
    {

        $requests = Request::getInstance($request); //请求
        $responses = Response::getInstance($response);  //响应
        try {
            Event::getInstance()->onRequest($requests, $responses);
            Launcher::getInstance()->dispatch();
            Event::getInstance()->onResponse($requests, $responses);
        } catch (\Exception $exception) {

            Trigger::exception($exception);
        }
        $responses->send(true);
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


    /**
     * onPipeMessage  [
     * 此函数可以向任意worker进程或者task进程发送消息。
     * 在非主进程和管理进程中可调用。收到消息的进程会触发onPipeMessage事件。
     * ]
     * @param $server
     * @param $src_worker_id
     * @param $data
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onPipeMessage($server, int $fromWorkerId, string $message)
    {

    }


}