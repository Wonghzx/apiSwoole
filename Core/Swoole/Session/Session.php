<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14/014
 * Time: 18:28
 */

namespace Core\Swoole\Session;

class Session extends \AbstractSession
{
    private static $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Session();
        }
        return self::$instance;
    }


    /**
     * start  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function start()
    {
        // TODO: Implement start() method.
    }

    /**
     * initialization  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function initialization()
    {
        // TODO: Implement initialization() method.
    }

    /**
     * set  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function set()
    {
        // TODO: Implement set() method.
    }
}