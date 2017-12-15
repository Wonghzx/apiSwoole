<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 15:32
 */

namespace Core\AbstractInterface;
abstract class AbstractErrorHandler
{
    /**
     * handler  [description]
     * @param $msg
     * @param null $file
     * @param null $line
     * @param null $errorCode
     * @param $trace
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function handler($msg, $file = null, $line = null, $errorCode = null, $trace);


    /**
     * display  [页面输出]
     * @param $msg
     * @param null $file
     * @param null $line
     * @param null $errorCode
     * @param $trace
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function display($msg, $file = null, $line = null, $errorCode = null, $trace);


    /**
     * log  [记录Log文件]
     * @param $msg
     * @param null $file
     * @param null $line
     * @param null $errorCode
     * @param $trace
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function log($msg, $file = null, $line = null, $errorCode = null, $trace);
}