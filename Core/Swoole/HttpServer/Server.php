<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8/008
 * Time: 11:21
 */

namespace Core\Swoole\HttpServer;
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
        $this->serverApi->set($conf->getWorkerSetting()); //设置运行时参数
        $this->pipeMessage();
        $this->requestEvent();
        $this->onTaskEvent();
        $this->onFinishEvent();
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
        $this->serverApi->on('request', function (\swoole_http_request $request,\swoole_http_response $response) {
            $a = json_encode($response);
            $a = json_decode($a,true);
            $response->end("<h1>Hello Swoole. #" . $a['fd'] . "</h1>");
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
     * onFinishEvent  [description]
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
}