<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5/005
 * Time: 11:46
 */

namespace Core\AbstractInterface;

use Core\Swoole\HttpServer\Storage\Response;
use Core\Swoole\HttpServer\Storage\Request;

abstract class AbstractEvent
{
    protected static $instance;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * initialize  [初始化框架前]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function initialize();


    /**
     * initializeEd  [初始化框架后]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function initializeEd();

    abstract public function onDispatcher(Request $request, Response $response, $targetControllerClass, $targetAction);

    abstract public function onRequest(Request $request, Response $response);

    abstract public function onResponse(Request $request, Response $response);

    abstract public function onWorkerStart(\swoole_server $server, $workerId);

    abstract public function onWorkerStop(\swoole_server $server, $workerId);

    abstract public function onSetServer(\swoole_server $server);

    abstract public function onGetServer();
}