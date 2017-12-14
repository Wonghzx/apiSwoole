<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13/013
 * Time: 10:42
 */

namespace Core\AbstractInterface;

use Core\Swoole\HttpServer\Storage\Response;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Status;

abstract class AbstractController
{
    protected $actionName = null;

    protected $callArgs = null;

    private function actionName($actionName = null)
    {
        if ($actionName === null) {
            return $this->actionName;
        } else {
            $this->actionName = $actionName;
        }
    }

    public function request()
    {
        return Request::getInstance();
    }

    public function response()
    {
        return Response::getInstance();
    }

    public function __call($actionName, $arguments)
    {
        // TODO: Implement __call() method.
        /*
           * 防止恶意调用
           * actionName、onRequest、actionNotFound、afterAction、request
           * response、__call
        */
        if (in_array($actionName, [
            '__call'
        ])) {
            $this->response()->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
            return;
        }
        //执行onRequest事件
        $this->actionName($actionName);
        //判断是否被拦截
        if (!$this->response()->isEndResponse()) {
            $realName = $this->actionName();
            if (method_exists($this, $realName)) {
                $this->$realName();
            } else {
                print_r('12312312');
            }
        }
    }
}