<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5/005
 * Time: 11:46
 */

namespace Core\AbstractInterface;

abstract class AbstractEvent
{
    protected static $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * initialize  [初始化框架前]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract function initialize();


    /**
     * initializeEd  [初始化框架后]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract function initializeEd();
}