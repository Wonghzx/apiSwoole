<?php
/**
 * [Launcher.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 09/12/17
 * Time: 下午11:16
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core\Swoole\HttpServer;

use Core\AbstractInterface\AbstractRouter;
use FastRoute\Dispatcher\GroupCountBased;
use Core\Swoole\HttpServer\Storage\Request;

class Launcher
{
    protected static $instance;

    protected $fastRouterDispatcher;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Launcher();
        }

        return self::$instance;
    }


    /**
     *[dispatch void]
     * @author  Wongzx <[842687571@qq.com]>
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    public function dispatch()
    {
        $pathInfo = UrlParser::pathInfo(); // url = /index/index
        $routeInfo = $this->doFastRouter($pathInfo, Request::getInstance()->getMethod());
        if ($routeInfo !== false) {
            switch ($routeInfo[0]) {
                case \FastRoute\Dispatcher::NOT_FOUND:
                    echo "... 404 NdoDispatcherot Found";
                    break;
                case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
//                    Response::getInstance()->withStatus(Status::CODE_METHOD_NOT_ALLOWED);
                    echo "405 Method Not Allowed";
                    break;
                case \FastRoute\Dispatcher::FOUND:
                    $handler = $routeInfo[1];
                    $vars = $routeInfo[2];
                    $this->callHandler($handler, $vars);
//                    if (is_callable($handler)) {
//                        call_user_func_array($handler, $vars);
//                    } else if (is_string($handler)) {
//                        $data = Request::getInstance()->getRequestParam();
//                        Request::getInstance()->withQueryParams($vars + $data);
//                        $pathInfo = UrlParser::pathInfo($handler);
//                        Request::getInstance()->getUri()->withPath($pathInfo);
//                    }
                    break;
            }
        }
    }


    /**
     *[callHandler void]
     * @author  Wongzx <[842687571@qq.com]>
     * @param $handler
     * @param $vars
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    public function callHandler($handler, $vars)
    {
        print_r($handler);
        print_r($vars);
    }


    private function intRouterInstance()
    {
        try {
            /*
             * if exit Router class in App directory
             * 类报告了一个类的有关信息。
             */
            $ref = new \ReflectionClass("Http\\Router");
            $router = $ref->newInstance();
            if ($router instanceof AbstractRouter) {
                $this->fastRouterDispatcher = new GroupCountBased($router->getRouteCollector()->getData());
            }
        } catch (\Exception $exception) {
            //没有设置路由
            $this->fastRouterDispatcher = false;
        }
    }


    /**
     *[doFastRouter array|bool]
     * @author  Wongzx <[842687571@qq.com]>
     * @param $pathInfo
     * @param $requestMethod  GET & POSt
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    private function doFastRouter($pathInfo, $requestMethod)
    {
        if (!isset($this->fastRouterDispatcher)) {
            $this->intRouterInstance();
        }
        if ($this->fastRouterDispatcher instanceof GroupCountBased) {
            return $this->fastRouterDispatcher->dispatch($requestMethod, $pathInfo);
        } else {
            return false;
        }
    }

}