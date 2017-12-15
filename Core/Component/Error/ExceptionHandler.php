<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 15:55
 */

namespace Core\Component\Error;

use Core\AbstractInterface\AbstractErrorHandler;
use Core\Component\Logger;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Response;

class ExceptionHandler extends AbstractErrorHandler
{

    /**
     * handler  [description]
     * @param \Exception $exception
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function handler(\Exception $exception)
    {
        // TODO: Implement handler() method.
    }

    /**
     * display  [description]
     * @param \Exception $exception
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function display(\Exception $exception)
    {
        // TODO: Implement display() method.
        if (Request::getInstance()) {
            Response::getInstance()->write(nl2br($exception->getMessage() . $exception->getTraceAsString()));
        } else {
            Logger::getInstance('error')->console($exception->getMessage() . $exception->getTraceAsString(), false);
        }
    }

    /**
     * log  [description]
     * @param \Exception $exception
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function log(\Exception $exception)
    {
        // TODO: Implement log() method.
        Logger::getInstance('error')->log($exception->getMessage() . " " . $exception->getTraceAsString());
    }
}
