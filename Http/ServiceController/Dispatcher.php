<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15/015
 * Time: 14:54
 */

namespace Http\ServiceController;

use Core\Component\Error\Trigger;
use Core\Swoole\Async\Redis\PubSubRedis;
use Core\Swoole\AsyncTaskManager;

class Dispatcher implements IDispatcher
{


    /**
     * doDispatcher  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doDispatcher(...$params)
    {
        // TODO: Implement doDispatcher() method.

        list($server, $fd, $fromId, $data) = $params;

        try {



//            DataStream::getInstance($data);
        } catch (\Exception $exception) {

            Trigger::exception($exception);

        } finally {

            $server->send($fd, 'xxxxx');
        }
    }

    /**
     * onConnect  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doConnect(...$params)
    {
        // TODO: Implement doConnect() method.
        list($server, $fd, $reactorId) = $params;
    }

    /**
     * doClose  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doClose(...$params)
    {
        // TODO: Implement doClose() method.
        list($server, $fd, $reactorId) = $params;
    }

    /**
     * onWorkerStart  [description]
     * @param $server
     * @param int $workerId
     * @return mixed
     */
    public function doWorkerStart($server, int $workerId)
    {
        // TODO: Implement doWorkerStart() method.
        $jobType = $server->taskworker ? 'Tasker' : 'Worker';
        $GLOBALS['server'] = $server;
        if ($jobType == 'Worker') {
            if ($workerId === 0) {

            }
        }

    }


    public function sendTask($ins, $pattern, $channel, $data)
    {

    }

}

