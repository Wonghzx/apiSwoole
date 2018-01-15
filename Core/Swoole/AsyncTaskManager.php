<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13/013
 * Time: 18:35
 */

namespace Core\Swoole;

use Core\Component\SuperClosure;
use Core\Event;

/**
 * Class AsyncTaskManager 异步进程任务管理器
 * @package Core\Swoole
 */
class AsyncTaskManager
{

    const TASK_DISPATCHER_TYPE_RANDOM = -1;

    /**
     * @var 单例
     */
    private static $instance;


    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    /**
     * addTask  [添加非阻塞等待的任务]
     * @param $taskCallable
     * @param int $workerId
     * @param null $finishCallBack
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */

    public function addTask($taskCallable, $finishCallBack = null, $workerId = self::TASK_DISPATCHER_TYPE_RANDOM)
    {
        if ($taskCallable instanceof \Closure) {
            try {
                $taskCallable = new SuperClosure($taskCallable);
            } catch (\Exception $exception) {
                trigger_error("async task serialize fail ");
                return false;
            }
            return Event::getInstance()->onGetServer()->task($taskCallable, $workerId, $finishCallBack);
        }
    }


    /**
     * addTaskWait  [添加阻塞等待的任务]
     * taskwait与task方法作用相同，用于投递一个异步的任务到task进程池去执行。与task不同的是taskwait是阻塞等待的，直到任务完成或者超时返回。
     * $result为任务执行的结果，由$serv->finish函数发出。如果此任务超时，这里会返回false。
     * @param $callable
     * @param float $timeout
     * @param int $workerId
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */

    public function addTaskWait($callable, $timeout = 0.5, $workerId = self::TASK_DISPATCHER_TYPE_RANDOM)
    {
        if ($callable instanceof \Closure) {
            try {
                $callable = new SuperClosure($callable);
            } catch (\Exception $exception) {
                trigger_error("async task serialize fail ");
                return false;
            }
        }
        return Event::getInstance()->onGetServer()->taskwait($callable, $timeout, $workerId);
    }


}