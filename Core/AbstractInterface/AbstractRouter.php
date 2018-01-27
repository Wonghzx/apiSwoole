<?php
/**
 * [AbstractRouter.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 10/12/17
 * Time: 下午2:10
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core\AbstractInterface;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;

abstract class AbstractRouter
{
    protected $isCache = false;

    protected $cacheFile;

    private $routeCollector;

    function __construct()
    {
        $this->routeCollector = new RouteCollector(new Std(), new GroupCountBased());
        $this->register($this->routeCollector);
    }

    abstract function register(RouteCollector $routeCollector);

    function getRouteCollector()
    {
        return $this->routeCollector;
    }

    function request()
    {
        return Request::getInstance();
    }

    function response()
    {
        return Response::getInstance();
    }
}