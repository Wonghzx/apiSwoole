<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6/006
 * Time: 11:22
 */

namespace Core\Swoole;


use Core\AbstractInterface\AbstractAsyncTask;
use Core\Component\SuperClosure;
use Core\Swoole\Async\Redis\PubSubRedis;
use Noodlehaus\Exception;

class Server
{
    protected static $instance;

    private $serverApi;

    private $conf;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->conf = getDi('conf');

        $serverType = $this->conf->get('setting.server_type');

        switch ($serverType) {
            case 'SERVER_TYPE_SERVER':
                $ip = $this->conf->get('tcp.host');
                $port = $this->conf->get('tcp.port');
                $runMode = $this->conf->get('tcp.model');
                $socketType = $this->conf->get('tcp.type');

                $this->serverApi = new \swoole_server($ip, $port, $runMode, $socketType);
                $this->gotoTasks();
                $this->swooleServer();
                break;
            case 'SERVER_TYPE_WEB':
                $ip = $this->conf->get('http.host');
                $port = $this->conf->get('http.port');
                $runMode = $this->conf->get('http.model');

                $this->serverApi = new \swoole_http_server($ip, $port, $runMode);
                $this->gotoTasks();
                $this->swooleHttpServer();
                break;
            case 'SERVER_TYPE_WEB_SOCKET':

                $ip = $this->conf->get('socket.host');
                $port = $this->conf->get('socket.port');
                $runMode = $this->conf->get('socket.model');
                $this->serverApi = new \swoole_websocket_server($ip, $port, $runMode);
                $this->gotoTasks();
                $this->swooleWebSocketServer();
                break;
            default : {
                exit('server type error');
            }

        }
    }

    /**
     * gotoTasks  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function gotoTasks()
    {
        $this->serverApi->on('start', [$this, 'onStart']);

        if ($this->conf->get('setting.task_worker_num')) {
            $this->serverApi->on('task', [$this, 'onTask']);
            $this->serverApi->on('finish', [$this, 'onFinish']);
        }
    }


    /**
     * swooleServer  [
     * 创建一个异步服务器程序，支持TCP、UDP、UnixSocket 3种协议，支持IPv4和IPv6，
     * 支持SSL/TLS单向双向证书的隧道加密。使用者无需关注底层实现细节，仅需要设置网络事件的回调函数即可。
     * ]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function swooleServer()
    {
        \Core\Swoole\Server\Server::getInstance()->serverStart($this->getServerApi(), $this->conf);
    }


    /**
     * swooleHttpServer  [swoole-1.7.7增加了内置Http服务器的支持，通过几行代码即可写出一个异步非阻塞多进程的Http服务器。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function swooleHttpServer()
    {
        \Core\Swoole\HttpServer\Server::getInstance()->serverStart($this->getServerApi(), $this->conf);
    }


    /**
     * swooleWebSocketServer  [swoole-1.7.9 增加了内置的websocket服务器支持，通过几行PHP代码就可以写出一个异步非阻塞多进程的WebSocket服务器。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function swooleWebSocketServer()
    {
        \Core\Swoole\WebSocket\Server::getInstance()->serverStart($this->getServerApi(), $this->conf);
    }


    /**
     * onStart  [description]
     * @param $server
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onStart($server)
    {
//        print_r($server);
    }


    /**
     * onTask  [description]
     * @param $server
     * @param $task_id
     * @param $data
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onTask($ser, $task_id, $from_id, $taskObj)
    {
        try {
            if (is_string($taskObj) && class_exists($taskObj)) {
                $taskObj = new $taskObj();
            }
            if ($taskObj instanceof AbstractAsyncTask) {

                return $taskObj->handler($ser, $task_id, $from_id);

            } elseif ($taskObj instanceof SuperClosure) {

                return $taskObj($ser, $task_id);
            }
            return null;
        } catch (Exception $exception) {
            return trigger_error($exception);
        }
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
        try {
            //仅仅接受AbstractTask回调处理
            if ($taskObj instanceof AbstractAsyncTask) {
                $taskObj->finishCallBack($server, $taskId, $taskObj->getDataForFinishCallBack());
            }
        } catch (\Exception $exception) {
            trigger_error($exception);
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


}