<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6/006
 * Time: 11:22
 */

namespace Core\Swoole;

use Core\Event;

class Server
{
    protected static $instance;

    private $serverApi;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    function __construct()
    {
        $conf = Config::getInstance();
        $serverType = $conf->getServerType();
        $ip = $conf->getListenIp();  //ip
        $port = $conf->getListenPort(); //端口
        $runMode = $conf->getRunMode();
        $socketType = $conf->getSocketType();
        switch ($serverType) {
            case SERVER_TYPE_SERVER:
                $this->serverApi = new \swoole_server($ip, $port, $runMode, $socketType);
                break;
            case SERVER_TYPE_WEB:
                $this->serverApi = new \swoole_http_server($ip, $port, $runMode);
                break;
            case SERVER_TYPE_WEB_SOCKET:
                $this->serverApi = new \swoole_websocket_server($ip, $port, $runMode);
                break;
            default : {
                exit('server type error');
            }

        }

    }

    /**
     * getServerApi  [获取swoole 服务]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function getServerApi()
    {
        return $this->serverApi;
    }


    /**
     * startServer  [开启swoole服务]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function startServer()
    {
        $conf = Config::getInstance();
        $this->getServerApi()->set($conf->getWorkerSetting()); //设置运行时参数
        $this->pipeMessage();
        $this->request();
        $this->start();//开启

    }

    /**
     * beforeWorkerStart  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function beforeWorkerStart()
    {
        Event::getInstance()->onSet($this->getServerApi());
    }

    /**
     * pipeMessage  [此函数可以向任意worker进程或者task进程发送消息。在非主进程和管理进程中可调用。收到消息的进程会触发onPipeMessage事件。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function pipeMessage()
    {
        $this->getServerApi()->on('pipeMessage', function ($server, $src_worker_id, $data) {
            print_r($server);
        });
    }

    /**
     * request  [监听http请求]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function request()
    {
        $this->getServerApi()->on('request', function ($request, $response) {
            $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
        });
    }


    private function start()
    {
        $this->getServerApi()->start();
    }


}