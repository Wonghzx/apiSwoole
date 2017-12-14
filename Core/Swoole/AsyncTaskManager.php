<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13/013
 * Time: 18:35
 */

namespace Core\Swoole;

use Core\Component\SuperClosure;

/**
 * Class AsyncTaskManager 异步任务管理器
 * @package Core\Swoole
 */
class AsyncTaskManager
{

    const TASK_DISPATCHER_TYPE_RANDOM = -1;

    private static $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    /**
     * addTask  [添加任务]
     * 投递一个异步任务到task_worker池中。此函数是非阻塞的，执行完毕会立即返回。Worker进程可以继续处理新的请求。
     * 使用Task功能，必须先设置 task_worker_num，并且必须设置Server的onTask和onFinish事件回调函数。
     * $data要投递的任务数据，可以为除资源类型之外的任意PHP变量
     * $workerId 可以制定要给投递给哪个task进程，传入ID即可，范围是0 - (serv->task_worker_num -1)
     * 未指定目标Task进程，调用task方法会判断Task进程的忙闲状态，底层只会向处于空闲状态的Task进程投递任务。
     * 如果所有Task进程均处于忙的状态，底层会轮询投递任务到各个进程。可以使用 server->stats 方法获取当前正在排队的任务数量。
     * 1.8.6版本增加了第三个参数，可以直接设置onFinish函数，如果任务设置了回调函数，Task返回结果时会直接执行指定的回调函数，不再执行Server的onFinish回调
     * @param $taskCallable
     * @param int $workerId
     * @param null $finishCallBack
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function addTask($taskCallable, $workerId = self::TASK_DISPATCHER_TYPE_RANDOM, $finishCallBack = null)
    {
        if ($taskCallable instanceof \Closure) {
            try {
                $taskCallable = new SuperClosure($taskCallable);
            } catch (\Exception $exception) {
                trigger_error("async task serialize fail ");
                return false;
            }
            Server::getInstance()->getServerApi()->task($taskCallable, $workerId, $finishCallBack);
        }
    }

}