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
use Core\Swoole\HttpServer\Storage\Response;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{

    public function register(RouteCollector $routeCollector)
    {
        // TODO: Implement register() method.
        $router = $this->routerController();
        foreach ($router AS $value => $item) {
            $routeCollector->addGroup($item['controller'], function ($routeCollector) use ($item) {
                foreach ($item['action'] AS &$value) {
                    $action = explode('@', $value);
                    $requestMethod = explode(',', $action[0]);
                    $routeCollector->addRoute($requestMethod, "/{$action[1]}", $action[1]);
                }
            });
        }
    }


    private function routerController()
    {
        /**
         * 路由文件
         * 常用请求方法的Shorcut方法
         * GET，POST，PUT，PATCH，DELETE，HEAD
         */
        return [
            //默认访问
            [
                'controller' => '/Index',
                'action' => [
                    "GET,POST@index",
                    "GET,POST@taskManager",
                ]
            ],

        ];
    }


}
