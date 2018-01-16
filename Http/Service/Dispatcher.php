<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15/015
 * Time: 14:54
 */

namespace Http\Service;

use Core\Component\Error\Trigger;
use Core\Swoole\Async\Redis\RedisClient;
use Core\Swoole\Async\Redis\RedisConnection;

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
        $res = new RedisClient('127.0.0.1');
        $res->subscribe('test1', function ($instance, $channelName, $message) {
            echo $channelName, "==>", $message, PHP_EOL;
        });

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
        if ($jobType == 'Worker') {
            if ($workerId === 0) {
                $redis = RedisClient::getInstance('127.0.0.1');
                $a = $redis->get('test', function ($result, $success) {
//                  print_r($result);
                });
            }
        }

    }


    public function sendTask($ins, $pattern, $channel, $data)
    {
        echo 123;
    }

}

