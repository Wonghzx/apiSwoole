<?php
/**
 * [Launcher.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 09/12/17
 * Time: 下午11:16
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core\Swoole\HttpServer;

use Core\AbstractInterface\AbstractController;
use Core\AbstractInterface\AbstractRouter;
use Core\Event;
use Core\Swoole\HttpServer\Storage\Response;
use Core\Swoole\HttpServer\Storage\Request;
use FastRoute\Dispatcher\GroupCountBased;


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

    /**
     *[dispatch void]
     * @author  Wongzx <[842687571@qq.com]>
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    public function dispatch()
    {
//        $pathInfo = UrlParser::pathInfo(); // url = /Index/index
        $pathInfo = Request::getInstance()->getUri()->getPath();

        if ($pathInfo === '/' || $pathInfo === '/Index') {
            $pathInfo = '/Index/index';
        }
        $method = Request::getInstance()->getMethod();
        $routeInfo = $this->doFastRouter($pathInfo, $method);
        if ($routeInfo !== false) {
            switch ($routeInfo[0]) {
                case \FastRoute\Dispatcher::NOT_FOUND:
                    Response::getInstance()->withStatus(404);
                    Response::getInstance()->assign(file_get_contents(ROOT . '/Http/Views/404.html'));
                    break;
                case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
//                    Response::getInstance()->withStatus(Status::CODE_METHOD_NOT_ALLOWED);
//                    echo "405 Method Not Allowed";
                    break;
                case \FastRoute\Dispatcher::FOUND:
                    $handler = $routeInfo[1];
                    $vars = $routeInfo[2];
                    $this->callHandler($handler, $vars);
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
        $pathInfo = ltrim($pathInfo, "/");
        if (isset($this->controllerMap[$pathInfo])) {
            $finalClass = $this->controllerMap[$pathInfo]['finalClass']; // 控制器
            $actionName = $this->controllerMap[$pathInfo]['actionName']; // 方法名称
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
            $controlMaxDepth = getConf('controller_max_depth');
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
        }
        if (class_exists($finalClass)) {
            if ($this->useControllerPool) {
                if (isset($this->controllerPool[$finalClass])) {
                    $controller = $this->controllerPool[$finalClass];
                } else {
                    $controller = new $finalClass;
                    $this->controllerPool[$finalClass] = $controller;
                }
            } else {
                $controller = new $finalClass;
            }
            if ($controller instanceof AbstractController) {
                Event::getInstance()->onDispatcher(Request::getInstance(), Response::getInstance(), $finalClass, $actionName);
                //预防在进控制器之前已经被拦截处理
                if (!Response::getInstance()->isEndResponse()) {
                    $controller->__call($actionName, null);
                }
            } else {
                Response::getInstance()->withStatus(404); //404
            }
        }
//        $controller = [$finalClass, $actionName];
//        call_user_func($controller, $vars);
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
     * @param $pathInfo /Index/index
     * @param $requestMethod  GET & POST
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