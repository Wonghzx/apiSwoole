<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/19/019
 * Time: 16:36
 */

namespace Core\Component\Ioc;

class Ioc
{

    private static $classMap = [];

    private static $instance;

    public static function getInstance($className)
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($className);
        }
        return self::$instance;
    }

    public function __construct($className)
    {
        self::$classMap[] = $className;
        $this->create();
    }


    public function create()
    {
        return (new \ReflectionClass(self::$classMap[0]));
    }

}