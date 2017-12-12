<?php
/**
 * [Launcher.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 09/12/17
 * Time: 下午11:16
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core\Swoole\HttpServer;

use Conf\Config;
use Core\AbstractInterface\AbstractRouter;
use FastRoute\Dispatcher\GroupCountBased;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Component\Di;

class Launcher
{
    protected static $instance;

    protected $fastRouterDispatcher; //路由调试

    protected $controllerMap = [];

    protected $useControllerPool = false; //使用控制器池

    protected $controllerPool = [];

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Launcher();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->useControllerPool = Config::getInstance()->getConf("CONTROLLER_POOL");
    }

    /**
     *[dispatch void]
     * @author  Wongzx <[842687571@qq.com]>
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    public function dispatch()
    {
        $pathInfo = UrlParser::pathInfo(); // url = /Index/index
        $routeInfo = $this->doFastRouter($pathInfo, Request::getInstance()->getMethod());
        if ($routeInfo !== false) {
            switch ($routeInfo[0]) {
                case \FastRoute\Dispatcher::NOT_FOUND:
//                    echo "... 404 NdoDispatcherot Found";
                    break;
                case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
//                    Response::getInstance()->withStatus(Status::CODE_METHOD_NOT_ALLOWED);
//                    echo "405 Method Not Allowed";
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
        $pathInfo = UrlParser::pathInfo($handler);
        //去除为fastRouter预留的左边斜杠
        $pathInfo = ltrim($handler, "/");
//        print_r($pathInfo);
        if (isset($this->controllerMap[$pathInfo])) {
            $finalClass = $this->controllerMap[$pathInfo]['finalClass'];
            $actionName = $this->controllerMap[$pathInfo]['actionName'];
        } else {
            /*
            * 此处用于防止URL恶意攻击，造成Launcher缓存爆满。
            */
            if (count($this->controllerMap) > 50000) {
                $this->controllerMap = [];
            }
            $list = explode("/", $pathInfo);
            $controllerNameSpacePrefix = "Http\\Controller";
            $actionName = null; //控制器名称
            $finalClass = null; //方法
            $controlMaxDepth = Di::getInstance()->get(CONTROLLER_MAX_DEPTH);
            if (intval($controlMaxDepth) <= 0) {
                $controlMaxDepth = 3;
            }
            $maxDepth = count($list) < $controlMaxDepth ? count($list) : $controlMaxDepth;
            while ($maxDepth > 0) {
                $className = '';
                for ($i = 0; $i < $maxDepth; $i++) {
                    $className = $className . "\\" . ucfirst($list[$i]);//为一级控制器Index服务
                }
                if (class_exists($controllerNameSpacePrefix . $className)) {
                    //尝试获取该class后的actionName
                    $actionName = empty($list[$i]) ? 'index' : $list[$i];
                    $finalClass = $controllerNameSpacePrefix . $className;
                    break;
                } else {
                    //尝试搜搜index控制器
                    $temp = $className . "\\Index";
                    if (class_exists($controllerNameSpacePrefix . $temp)) {
                        $finalClass = $controllerNameSpacePrefix . $temp;
                        //尝试获取该class后的actionName
                        $actionName = empty($list[$i]) ? 'index' : $list[$i];
                        break;
                    }
                }
                $maxDepth--;
            }
            if (empty($finalClass)) {
                //若无法匹配完整控制器   搜搜Index控制器是否存在
                $finalClass = $controllerNameSpacePrefix . "\\Index";
                $actionName = empty($list[0]) ? 'index' : $list[0];
            }
            $this->controllerMap[$pathInfo]['finalClass'] = $finalClass;
            $this->controllerMap[$pathInfo]['actionName'] = $actionName;

            if (class_exists($finalClass)) {
                if ($this->useControllerPool) {
                    if (isset($this->controllerPool[$finalClass])) {
                        $controller = $this->controllerPool[$finalClass];
                    } else {
                        $controller = new $finalClass;
                        $this->controllerPool[$finalClass] = $controller;
                        print_r( $this->controllerPool);
                    }
                } else {
                    $controller = new $finalClass;
                }
            }
        }
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