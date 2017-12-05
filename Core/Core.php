<?php
/**
 * [Core.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 02/12/17
 * Time: 下午10:53
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core;
class Core
{
    protected static $instance;

    private $preCall;


    function __construct($preCall)
    {
        $this->preCall = $preCall;
    }

    static function getInstance(callable $preCall = null)
    {

        if (!isset(self::$instance)) {
            self::$instance = new static($preCall);
        }
        return self::$instance;
    }


    /**
     * run  [开启框架]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function run()
    {

    }

    /**
     * initialize  [初始化]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function initialize()
    {
        if (phpversion() < 5.5)
            die('您的PHP版本低于5.5 ，该框架需要PHP版本5.5 或 > 5.5^');

        defined('ROOT') or define("ROOT", realpath(__DIR__ . '/../'));
        $this->registerAutoLoader();
        Event::getInstance()->initialize();//初始化框架前
        Event::getInstance()->initializeEd();//初始化框架后
        $this->registerErrorHandler();//错误处理程序
        return $this;
    }


    /**
     * registerAutoLoader  [创建自动加载机制]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function registerAutoLoader()
    {
        include_once 'DumpAutoload.php';
        $autoload = DumpAutoload::getInstance();
        /*
         * PSR-4 自动加载机制
         */
        $autoload->addNamespace('Http', 'Http');
        $autoload->addNamespace('Core', 'Core');
        $autoload->addNamespace('Conf', 'Conf');

        return $this;

    }


    /**
     * registerErrorHandler  [错误处理程序]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function registerErrorHandler()
    {
    }


}