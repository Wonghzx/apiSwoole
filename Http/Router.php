<?php

/**
 * [Router.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 10/12/17
 * Time: 下午2:09
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Http;

use Core\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{

    function register(RouteCollector $routeCollector)
    {
        // TODO: Implement register() method.
        $routeCollector->addRoute(['GET', 'POST'], "/Index", "/Index/index");
    }
}
