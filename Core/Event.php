<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5/005
 * Time: 11:45
 */

namespace Core;


use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Response;
use Illuminate\Database\Capsule\Manager AS Capsule;

//use Illuminate\Database\Capsule\Manager AS Capsule;

class Event extends \Core\AbstractInterface\AbstractEvent
{

    private $server;

    /**
     * initialize  [初始化框架前]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    /**
     * initializeEd  [初始化框架后]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function initializeEd()
    {
//        ShareMemory::getInstance()->clear();
//        include_once ROOT . '/vendor/autoload.php';
        // TODO: Implement initializeEd() method.

        $dbConf = getConf('database');
//        $capsule = new Manager();
        $capsule = new Capsule;
        $capsule->addConnection($dbConf);

        /*
         * Make this capsule instance available globally.
         * 设置全局静态可访问
         */
        $capsule->setAsGlobal();

        /*
         * Bootstrap Eloquent so it is ready for usage.
         * 启动Eloquent
         */
        $capsule->bootEloquent();
    }


    public function onDispatcher(Request $request, Response $response, $targetControllerClass, $targetAction)
    {
        // TODO: Implement onDispatcher() method.
    }

    public function onRequest(Request $request, Response $response)
    {
        // TODO: Implement onRequest() method.
    }

    public function onResponse(Request $request, Response $response)
    {
        // TODO: Implement onResponse() method.
    }


    public function onWorkerStart(\swoole_server $server, $workerId)
    {
        // TODO: Implement onWorkerStart() method.
        /*
         * 使用Reload机制实现代码重载入 ，确定 已经 PHP inotify 扩展
         *
         */
        if (function_exists('inotify_init')) {
            if ($workerId == 0) {
                $list = recursionDirFiles(ROOT . "/Http");
                // 为所有目录和文件添加inotify监视
                $notify = inotify_init();
                foreach ($list as $item) {
                    inotify_add_watch($notify, $item, IN_CREATE | IN_DELETE | IN_MODIFY);
                }
                // 加入EventLoop
                swoole_event_add($notify, function () use ($notify, $server) {
                    $events = inotify_read($notify);
                    if (!empty($events)) {
                        $server->reload();
                    }
                });
            }
        }
    }

    public function onWorkerStop(\swoole_server $server, $workerId)
    {
        // TODO: Implement onWorkerStop() method.
    }

    public function onSetServer(\swoole_server $server)
    {
        // TODO: Implement onSetServer() method.
        $this->server = $server;
    }

    public function onGetServer(): \swoole_server
    {
        // TODO: Implement onGetServer() method.
        return $this->server;
    }
}