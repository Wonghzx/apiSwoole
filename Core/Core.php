<?php
/**
 * [Core.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 02/12/17
 * Time: 下午10:53
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core;

use Core\Component\Di;
use Core\Component\Error\Trigger;
use Core\Component\File;
use Core\Console\Console;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Response;
use Core\Swoole\Server;
use Dotenv\Dotenv;
use Noodlehaus\Config;

/**
 * Class Core 应用简写类
 * @package Core
 */
class Core
{
    protected static $instance;

    private $preCall;


    private $loadConf;

    /**
     * 应用对象
     * @var Application
     */
    private $app;



    static function getInstance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new static();
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

        Server::getInstance();
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
        $this->defineSysConf();
        $this->registerAutoLoader();
        /**
         * 加载 Conf
         */
        Di::getInstance()->set('conf', Config::load(ROOT . '/Conf'));
        $this->loadConf = getDi('conf');

        /**
         * 初始化
         */
        Event::getInstance()->initialize();//初始化框架前
        $this->sysDirectoryInit();//系统目录初始化
        Event::getInstance()->initializeEd();//初始化框架后
        $this->registerErrorHandler();//错误处理程序

        Console::getInstance()->run(); //执行命令行
        return $this;
    }


    private function sysDirectoryInit()
    {
        //创建Runtime目录
        $tempDir = $this->loadConf->get('common.runtime_directory');


        if (!File::createDir($tempDir)) {
            die("create Temp Directory:{$tempDir} fail");
        } else {
            //创建默认Session存储目录
            $path = $tempDir . "/Session";
            File::createDir($path);
        }

        $logDir = $tempDir . $this->loadConf->get('common.runtime_logs');

        if (!File::createDir($logDir)) {
            die("create log Directory:{$logDir} fail");
        }

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


        $autoload->requireFile('/Bin/Helpers.php');

        return $this;

    }


    /**
     * registerErrorHandler  [错误处理程序]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function registerErrorHandler()
    {
        $debug = $this->loadConf->get('debug');
        if ($debug['enable'] === true) {
            ini_set("display_errors", "On");
            error_reporting(E_ALL | E_STRICT);
            set_error_handler(function ($errorCode, $description, $file = null, $line = null) {
                Trigger::error($description, $file, $line, $errorCode, debug_backtrace());
            });
            register_shutdown_function(function () {
                $error = error_get_last(); //获取最后发生的错误
                if (!empty($error)) {
                    Trigger::error($error['message'], $error['file'], $error['line'], E_ERROR, debug_backtrace());
                    //HTTP下，发送致命错误时，原有进程无法按照预期结束链接,强制执行end
                    if (Request::getInstance()) {
                        Response::getInstance()->end(true);
                    }
                }
            });
        }
    }


    /**
     * defineSysConf  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function defineSysConf()
    {
        defined('ROOT') or define("ROOT", realpath(__DIR__ . '/../'));
        $dotenv = new Dotenv(ROOT);
        $dotenv->load();
    }


}