<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4/004
 * Time: 10:54
 */

namespace Core\Swoole\Timer;

interface ITimer
{

    /**
     * addTimer  [定时器]
     * @param $second  秒
     * @param \Closure $func 任务 （闭包）
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function addTimer(int $second, \Closure $func);


    /**
     * addAfter  [一次性定时器 执行完成后就会销毁]
     * @param $second 秒
     * @param \Closure $func 任务 （闭包）
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function addAfter(int $second, \Closure $func);


    /**
     * clearTimer  [删除]
     * @param $timerId
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function clearTimer($timerId);
}