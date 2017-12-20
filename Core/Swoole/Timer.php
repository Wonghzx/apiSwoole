<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 18:12
 */

namespace Core\Swoole;

/**
 * Class Timer 定时器
 * @package Core\Swoole
 */
class Timer
{

    /**
     * addTimer  [定时器]
     * @param $second
     * @param \Closure $func
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function addTimer($second, \Closure $func)
    {
        return Server::getInstance()->getServerApi()->tick($second, $func);
    }


    /**
     * addAfter  [一次性定时器 执行完成后就会销毁]
     * @param $second
     * @param \Closure $func
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function addAfter($second, \Closure $func)
    {
        Server::getInstance()->getServerApi()->after($second, $func);
    }


    /**
     * clearTimer  [删除]
     * @param $timerId
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function clearTimer($timerId)
    {
        Server::getInstance()->getServerApi()->clearTimer($timerId);
    }

}