<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15/015
 * Time: 14:54
 */

namespace Http\Service;

use Core\Component\Error\Trigger;
use Core\Swoole\Async\Redis\RedisAsyncPool;
use Core\Swoole\Async\Redis\RedisClient;
use Core\Swoole\Async\Redis\RedisRoute;
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
//            $redis = new \Redis();
//            $redis->connect('127.0.0.1', '6379');
//            $redis->auth('123456');
//            $redis->subscribe(['test'], function ($client, $result, $data) use ($server, $fd) {
//                if (!empty($data)) {
//                }
//            });
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
                $redis = RedisAsyncPool::getInstance();

//                $redis->execute(['get'], function ($client, $result) {
//
//                });
//                $redis->redisPool->get('key', function ($client, $result){
//                    print_r($result);
//                });
//                $redis = RedisClient::getInstance('127.0.0.1');
//                $redis->psubscribe('test1', function ($ins, $pattern, $channel, $data) {
//                    $taskData = array(
//                        'cmd' => 'pushToClient',
//                        'val' => $data,
//                    );
//                    //请注意，taskwait是同步阻塞的，所以改脚本并不是全异步非阻塞的
//                    AsyncTaskManager::getInstance()->addTaskWait(function () use ($taskData) {
//                        print_r($taskData);
//                    });
//                });
            }
        }

    }


    public function sendTask($ins, $pattern, $channel, $data)
    {

    }

}

