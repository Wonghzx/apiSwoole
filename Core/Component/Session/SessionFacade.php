<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/23/023
 * Time: 14:17
 */

namespace Core\Component\Session;

use Core\Swoole\HttpServer\Storage\Response;

class SessionFacade extends Session
{
    /**
     * set  [description]
     * @param $name
     * @param null $value
     */
    static public function set($name, $value = null)
    {
        $sessionInstance = Session::getInstance();
        try {
            if (!$sessionInstance->isStart()) {
                $sessionInstance->start();
            }
            $data = $sessionInstance->read();
            $data = unserialize($data);
            if (!is_array($data)) {
                $data = [];
            }
            $data[$name] = $value;
            return $sessionInstance->write(serialize($data));
        } catch (\Exception $exception) {
            return false;
        }
    }


    /**
     * find  [description]
     * @param $key
     * @param null $value
     */
    static public function find($key, $value = null)
    {
        $sessionInstance = Session::getInstance();
        try {
            if (!$sessionInstance->isStart()) {
                $sessionInstance->start();
            }
            $data = $sessionInstance->read();
            $data = unserialize($data);
            if (is_array($data)) {
                if (isset($data[$key])) {
                    return $data[$key];
                } else {
                    return $value;
                }
            } else {
                return $value;
            }
        } catch (\Exception $exception) {
            return false;
        }
    }


    /**
     * has  [检查会话存在]
     * @param $key
     */
    static public function has($key): bool
    {
        return static::find($key, null) !== null;
    }

    /**
     * delete  [description]
     * @param $key
     */
    static public function delete($key)
    {
        return static::set($key, null);
    }

    /**
     * clear  [description]
     */
    static public function clear()
    {
        $response = Response::getInstance();
        $sessionInstance = Session::getInstance();
        $sessionInstance->read();
        $sessionInstance->destroy();
        $response->setCookies($sessionInstance->sessionName(), null, 0);
    }

    /**
     * getId  [description]
     */
    static public function id()
    {
        $sessionInstance = Session::getInstance();
        if (!$sessionInstance->isStart()) {
            $sessionInstance->start();
        }
        return $sessionInstance->sessionId();
    }


}