<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14/014
 * Time: 18:32
 */
abstract class AbstractSession
{
    /**
     * start  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function start();

    /**
     * initialization  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function initialization();

    /**
     * set  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function set();
}