<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 14:50
 */

namespace Core\Component\Error;

use Core\AbstractInterface\AbstractErrorHandler;
use Core\Component\Logger;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Response;

class ErrorHandler extends AbstractErrorHandler
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
    public function handler($msg, $file = null, $line = null, $errorCode = null, $trace)
    {
        // TODO: Implement handler() method.
    }

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
    public function display($msg, $file = null, $line = null, $errorCode = null, $trace)
    {
        // TODO: Implement display() method.
        //判断是否在HTTP模式下
        if (Request::getInstance()) {

            Response::getInstance()->write(nl2br($msg) . " in file {$file} line {$line}");

        } else {
            Logger::getInstance('error')->console($msg . " in file {$file} line {$line}", false);
        }

    }

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
    public function log($msg, $file = null, $line = null, $errorCode = null, $trace)
    {
        // TODO: Implement log() method.
        Logger::getInstance('error')->log($msg . " in file {$file} line {$line}");
    }
}