<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 14:53
 */

namespace Core\Component\Error;

use Core\AbstractInterface\AbstractExceptionHandler;
use Core\AbstractInterface\AbstractErrorHandler;
use Core\Component\Di;

class Trigger
{
    /**
     * error  [description]
     * @param $msg  信息
     * @param null $file 当前的文件名。
     * @param null $line 当前的行号。
     * @param int $errorCode
     * @param null $trace
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public static function error($msg, $file = null, $line = null, $errorCode = E_USER_ERROR, $trace = null)
    {
        $conf = Di::getInstance()->get('conf');
        $debug = $conf->get('debug');
        if ($trace == null) {
            $trace = debug_backtrace(); //产生一条回溯跟踪(函数生成一个 backtrace。)
        }
        $handler = $debug['error_handler'];
        if (!$handler instanceof AbstractErrorHandler) {
            $handler = new ErrorHandler();
        }
        $handler->handler($msg, $file, $line, $errorCode, $trace);

        /**
         * 判断是否页面输出 错误
         */
        if ($debug['display_error'] == true) {
            $handler->display($msg, $file, $line, $errorCode, $trace);
        }

        /**
         * 判断是否记录错误Log文件
         */
        if ($debug['log'] == true) {
            $handler->log($msg, $file, $line, $errorCode, $trace);
        }
    }


    public static function exception(\Exception $exception)
    {

        $conf = Di::getInstance()->get('conf');
        $debug = $conf->get('debug');

        $handler = $debug['exception_handler'];
        if (!$handler instanceof AbstractExceptionHandler) {
            $handler = new ExceptionHandler();
        }
        $handler->handler($exception);

        if ($debug['display_error'] == true) {
            $handler->display($exception);
        }

        if ($debug['log'] == true) {
            $handler->log($exception);
        }

    }
}