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

    private $stdClass;

    private $className;

    private static $i = 0;

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
    }


    /**
     * create  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return \ReflectionClass
     */
    public function create()
    {
        $this->stdClass = new \ReflectionClass(self::$classMap[0]);
        return $this->stdClass;
    }

    /**
     * getMethodsDoc  [遍历所有的方法注释]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function parseMethodsDoc()
    {
        $methods = $this->create()->getMethods(
            \ReflectionMethod::IS_PUBLIC +
            \ReflectionMethod::IS_PROTECTED +
            \ReflectionMethod::IS_PRIVATE
        );


        $call = [];
        $doc = [];
        //遍历所有的方法
        foreach ($methods As $method) {
            //获取注释
//            array_push($doc, $method->getDocComment());
            $info = DocParser::getInstance()->analysis($method->getDocComment());
            //解析方法中注释
            $call[] = [
                'class' => $method->class,
                'method' => $method->name,
                'meta' => $info
            ];
        }
//        print_r($call);

//        $this->selectAnalysis($doc);

//        print_r($methods);
    }



}