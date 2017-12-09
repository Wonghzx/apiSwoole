<?php
/**
 * [Launcher.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 09/12/17
 * Time: 下午11:16
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core\Swoole\HttpServer;

class Launcher
{
    protected static $instance;

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

    }

}