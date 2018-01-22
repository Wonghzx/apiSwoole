<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 13:37
 */

namespace Core\Component;

use Core\Swoole\HttpServer\Storage\Response;
use Core\Swoole\Session\Transmisor;

class SessionFacade
{

    /**
     * set  [设置session]
     * @param $name
     * @param string $value
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function set($name, $value = '')
    {
        $session = Transmisor::getInstance();
        if (is_array($name)) {
            try {
                foreach ($name as $sessionName => $sessionValue) {
                    $session->set($sessionName, $sessionValue);
                }
                return true;
            } catch (\Exception $exception) {
                return false;
            }
        } else {
            return $session->set($name, $value);
        }
    }


    /**
     * find  [检查session的存在]
     * @param $name
     * @param null $default
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function find($name, $default = null)
    {
        $session = Transmisor::getInstance();
        return $session->get($name, $default);
    }


    /**
     * has  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function has($name)
    {
        return static::find($name, null) !== null;
    }


    /**
     * delete  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function delete($name)
    {
        $session = Transmisor::getInstance();
        return $session->set($name, null);
    }


    /**
     * clear  [清空]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function clear()
    {
        $session = Transmisor::getInstance();
        $session->destroy();
        Response::getInstance()->setCookies($session->sessionName(), null, 0);
        return true;
    }

    /**
     * sessionId  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function sessionId()
    {
        $session = Transmisor::getInstance();
        return $session->sessionId();

    }


}