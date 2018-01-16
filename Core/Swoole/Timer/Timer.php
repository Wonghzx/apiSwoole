<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 18:12
 */

namespace Core\Swoole\Timer;

use Core\Event;
use Noodlehaus\Exception;


/**
 * Class Timer 定时器
 * @package Core\Swoole
 */
class Timer implements ITimer
{

    /**
     * addTimer  [定时器]
     * @param $second  秒
     * @param \Closure $func 任务 （闭包）
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function addTimer(int $second, \Closure $func)
    {
        // TODO: Implement addTimer() method.
        return Event::getInstance()->onGetServer()->tick($second, $func);
    }

    /**
     * addAfter  [指定的时间后执行函数 执行完成后就会销毁]
     * @param $second 秒
     * @param \Closure $func 任务 （闭包）
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function addAfter(int $second, \Closure $func)
    {
        // TODO: Implement addAfter() method.
        return Event::getInstance()->onGetServer()->after($second, $func);
    }

    /**
     * clearTimer  [删除]
     * @param $timerId
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function clearTimer($timerId)
    {
        // TODO: Implement clearTimer() method.
        return Event::getInstance()->onGetServer()->clearTimer($timerId);
    }
}