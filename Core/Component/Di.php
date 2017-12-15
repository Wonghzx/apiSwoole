<?php
/**
 * Created by PhpStorm.
 * User: Wong
 * Date: 2017/12/6/006
 * Time: 10:07
 */

namespace Core\Component;
class Di
{
    protected static $instance;

    protected $container = [];

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * set  [设置]
     * @param $key
     * @param $obj
     * @param array ...$arg
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return $this
     */
    function set($key, $obj, ...$arg)
    {
        if (count($arg) == 1 && is_array($arg[0])) {
            $arg = $arg[0];
        }
        /*
         * 注入的时候不做任何的类型检测与转换
         */
        $this->container[$key] = [
            "obj" => $obj,
            "params" => $arg,
        ];
        return $this;
    }

    /**
     * delete  [删除]
     * @param $key
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    function delete($key)
    {
        unset($this->container[$key]);
    }

    /**
     * clear  [清空 IoC 容器的所有内容。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    function clear()
    {
        $this->container = [];
    }

    /**
     * @param $key
     * @return mixed
     */
    function get($key)
    {
        if (isset($this->container[$key])) {

            $result = $this->container[$key];

            if (is_object($result['obj'])) {

                return $result['obj'];

            } else if (is_callable($result['obj'])) {

                $ret = call_user_func_array($result['obj'], $result['params']);

                $this->container[$key]['obj'] = $ret;

                return $this->container[$key]['obj'];

            } else if (is_string($result['obj']) && class_exists($result['obj'])) {

                $reflection = new \ReflectionClass ($result['obj']);

                $ins = $reflection->newInstanceArgs($result['params']);

                $this->container[$key]['obj'] = $ins;

                return $this->container[$key]['obj'];

            } else {

                return $result['obj'];
            }

        } else {

            return null;
        }
    }
}