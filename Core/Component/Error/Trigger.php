<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 14:53
 */

namespace Core\Component\Error;

use Conf\Config;
use Core\AbstractInterface\AbstractErrorHandler;
use Core\AbstractInterface\AbstractExceptionHandler;
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
        $debug = Config::getInstance()->getConf('DEBUG');

        if ($trace == null) {
            $trace = debug_backtrace(); //产生一条回溯跟踪(函数生成一个 backtrace。)
        }
        $handler = Di::getInstance()->get(ERROR_HANDLER);
        if (!$handler instanceof AbstractErrorHandler) {
            $handler = new ErrorHandler();
        }
        $handler->handler($msg, $file, $line, $errorCode, $trace);

        /**
         * 判断是否页面输出 错误
         */
        if ($debug['DISPLAY_ERROR'] == true) {
            $handler->display($msg, $file, $line, $errorCode, $trace);
        }

        /**
         * 判断是否记录错误Log文件
         */
        if ($handler['LOG'] == true) {
            $handler->log($msg, $file, $line, $errorCode, $trace);
        }
    }


    public static function exception(\Exception $exception)
    {

        $debug = Config::getInstance()->getConf('DEBUG');

        $handler = Di::getInstance()->get(EXCEPTION_HANDLER);
        if (!$handler instanceof AbstractExceptionHandler) {
            $handler = new ExceptionHandler();
        }
        $handler->handler($exception);

        if ($debug['DISPLAY_ERROR'] == true) {
            $handler->display($exception);
        }

        if ($debug['LOG'] == true) {
            $handler->log($exception);
        }

    }
}