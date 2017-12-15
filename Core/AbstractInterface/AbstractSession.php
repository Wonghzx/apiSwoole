<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14/014
 * Time: 18:32
 */

namespace Core\AbstractInterface;
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
     * set  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function set($string);


    /**
     * sessionId  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function sessionId();


    /**
     * read  [当执行read的时候，要求上锁]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function read();


    /**
     * destroy  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function destroy();


    /**
     * close  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function close();


    /**
     * sessionName  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function sessionName($name = null);


    /**
     * savePath  [description]
     * @param null $savePath
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function savePath($savePath = null);


    /**
     * isStart  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function isStart();

}