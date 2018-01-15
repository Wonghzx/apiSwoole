<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15/015
 * Time: 15:59
 */

namespace Core\Component\Bean;

use Http\Service\Dispatcher;
use Pimple\Container AS con;

class Container
{
    protected static $instance;

    protected static $container = [
        'dispatcherService'
    ];

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new con();
        }
        return self::$instance;
    }

    /**
     * getDispatcherService  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function getDispatcherService()
    {
        if (!in_array(self::$instance->keys(), self::$container)) {
            self::$instance->offsetSet('dispatcherService', new Dispatcher());
        }
        return self::$instance->offsetGet('dispatcherService');
    }

}