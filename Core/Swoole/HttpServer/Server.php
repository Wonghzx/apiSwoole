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
//        print_r($conf);die;
        $this->serverApi = $server;
        $this->serverApi->set($conf->getWorkerSetting()); //设置运行时参数
//        $this->pipeMessage();
        $this->requestEvent();
        $this->onTaskEvent();
        $this->onFinishEvent();
        $this->workerStartEvent();
        $this->workerStopEvent();
        $this->startEvent();//开启
    }


    /**
     * pipeMessage  [此函数可以向任意worker进程或者task进程发送消息。在非主进程和管理进程中可调用。收到消息的进程会触发onPipeMessage事件。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function pipeMessage()
    {
        $this->serverApi->on('pipeMessage', function ($server, $src_worker_id, $data) {
            print_r($server);
        });
    }

    /**
     * request  [监听http请求]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function requestEvent()
    {
        $this->serverApi->on('request', function (\swoole_http_request $request, \swoole_http_response $response) {
            $requests = Request::getInstance($request);    //请求
            $responses = Response::getInstance($response);  //响应
            try {
                Event::getInstance()->onRequest($requests, $responses);
                Launcher::getInstance()->dispatch();
                Event::getInstance()->onResponse($requests, $responses);
            } catch (\Exception $exception) {

                Trigger::exception($exception);
            }
            $responses->end(true);
        });


    }


    /**
     * onTaskEvent  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function onTaskEvent()
    {
        $this->serverApi->on('task', function (\swoole_server $server, $task_id, $data) {
            print_r($server);
        });
    }

    /**
     * onFinishEvent  [此函数用于在task进程中通知worker进程，投递的任务已完成。此函数可以传递结果数据给worker进程]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function onFinishEvent()
    {
        $this->serverApi->on('finish', function (\swoole_server $server, $taskId, $taskObj) {
            print_r($server);
        });
    }


    private function startEvent()
    {
        $this->serverApi->start();
    }


    private function workerStartEvent()
    {
        $this->serverApi->on("workerStart", function (\swoole_server $server, $workerId) {
            Event::getInstance()->onWorkerStart($server, $workerId);
        });
    }

    private function workerStopEvent()
    {
        $this->serverApi->on("workerStop", function (\swoole_server $server, $workerId) {
            Event::getInstance()->onWorkerStop($server, $workerId);
        });
    }
}