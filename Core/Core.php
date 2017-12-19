<?php
/**
 * [Core.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 02/12/17
 * Time: 下午10:53
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core;

use Conf\Config;
use Core\Component\Di;
use Core\Component\Error\Trigger;
use Core\Component\File;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Response;
use Core\Swoole\Server;
use Blade\Blade;

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

        defined('ROOT') or define("ROOT", realpath(__DIR__ . '/../'));
        $this->registerAutoLoader();
        Event::getInstance()->initialize();//初始化框架前
        $this->sysDirectoryInit();//系统目录初始化
        Event::getInstance()->initializeEd();//初始化框架后
        $this->registerErrorHandler();//错误处理程序
        return $this;
    }


    private function sysDirectoryInit()
    {
        //创建Runtime目录
        $tempDir = Di::getInstance()->get(TEMP_DIRECTORY);
        if (empty($tempDir)) {
            $tempDir = ROOT . '/Runtime';
            Di::getInstance()->set(TEMP_DIRECTORY, $tempDir);
        }

        if (!File::createDir($tempDir)) {
            die("create Temp Directory:{$tempDir} fail");
        } else {
            //创建默认Session存储目录
            $path = $tempDir . "/Session";
            File::createDir($path);
            Di::getInstance()->set(SESSION_SAVE_PATH, $path);
        }

        $logDir = Di::getInstance()->get(LOG_DIRECTORY);
        if (empty($logDir)) {
            $logDir = $tempDir . "/Log";
            Di::getInstance()->set(LOG_DIRECTORY, $logDir);
        }
        if (!File::createDir($logDir)) {
            die("create log Directory:{$logDir} fail");
        }
        Config::getInstance()->setSysConf('SERVER.CONFIG.log_file', $logDir . '/swoole.log');
        Config::getInstance()->setSysConf('SERVER.CONFIG.pid_file', $logDir . '/pid.pid');

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

        /**
         * 加载第三方依赖组件
         */
        $autoload->addNamespace('FastRoute', 'Core/Package/FastRoute');//路由
        $autoload->addNamespace('SuperClosure', 'Core/Package/SuperClosure'); //用于序列化闭包和匿名函数的PHP库。
        $autoload->addNamespace('Illuminate', 'Core/Package/Illuminate'); //
        $autoload->addNamespace('Latte', 'Core/Package/Latte'); //
        $autoload->addNamespace('Blade', 'Core/Package/Blade'); //


        /**
         * 加载自定义的函数
         */
        $autoload->requireFile('/Core/Package/Illuminate/Support/helpers.php');
        $autoload->requireFile('/Conf/Helpers.php');
//        $autoload->requireFile('/Core/Package/latte.php');
        return $this;

    }


    /**
     * registerErrorHandler  [错误处理程序]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function registerErrorHandler()
    {
        $debug = Config::getInstance()->getConf('DEBUG');
        if ($debug['ENABLE'] === true) {
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


}