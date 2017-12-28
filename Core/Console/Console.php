<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22/022
 * Time: 15:33
 */

namespace Core\Console;

use Core\Console\Input\Input;
use Core\Swoole\Server;

/**
 * Class Console 控制室
 * @package Core\Console
 */
class Console
{

    private static $_instance;

    private static $version = '1.0.0';

    const DEFAULT_CMD = [
        'start', //启动
        'stop',  //停止
        'reload', //重载服务
        'update', //升级系统
        'help' //帮助
    ];

    /**
     * 参数输入
     * @var Input
     */
    private $input;

    /**
     * 参数输出
     * @var Output
     */
    private $output;

    /**
     * 每个命令唯一ID
     * @var ID
     */
    private static $pid;


    static public function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }


    public function run()
    {
        // 默认命令解析
        $cmd = Input::getInstance()->getCommand();

        try {
            $this->commandHandler($cmd);
        } catch (\Throwable $e) {

        }
    }


    /**
     * runCommand  [运行命令]
     * @param string $cmd
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function commandHandler(string $cmd)
    {
        // 默认命令处理
        switch ($cmd) {
            case 'start': //启动
                $this->startServer();
                break;
            case 'stop': //停止
                $this->stopServer();
                break;
            case 'reload': //重载服务
                $this->reloadServer();
                break;
            case 'update': //升级系统
                break;
            case 'version': //版本号
                echo "";
                break;
            case 'help': //帮助
            default: {
                $this->help();
            }
        }
    }


    /**
     * startServer  [启动]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function startServer()
    {
        echo $this->iconLogo() . "\n";

        $this->printParameters();

        Server::getInstance();
    }


    /**
     * stopServer  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool|void
     */
    private function stopServer()
    {


        $pidFile = getConf('setting.pid_file');

        if (!file_exists($pidFile)) {
            echo "pid file :{$pidFile} not exist \n";
            return false;
        }

        $pid = file_get_contents($pidFile);

        /**
         * 可以检测进程是否存在，不会发送信号
         */
        if (!posix_kill($pid, 0)) {
            echo "pid :{$pid} not exist \n";
            return;
        }
        sleep(1);
        if (posix_kill($pid, SIGTERM)) {
            echo "server stop at " . date("y-m-d h:i:s") . "\n";
            if (is_file($pidFile)) {
                unlink($pidFile);
            }
        } else {
            echo "stop server fail try again \n";
        }


    }


    /**
     * reloadServer  [重载服务]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function reloadServer()
    {
        $pidFile = getConf('setting.pid_file');

        if (!file_exists($pidFile)) {
            echo "pid file :{$pidFile} not exist \n";
            return false;
        }

        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
        }
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }


        self::$pid = file_get_contents($pidFile);
        posix_kill(self::$pid, SIGUSR1);
        echo "send server reload command at " . date("y-m-d h:i:s") . "\n";
    }


    /**
     * iconLogo  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    private function iconLogo(): string
    {
        $string = <<<STRING
   ___    _ __    _     ___                          _           
  /   \  | '_ \  (_)   / __| __ __ __ ___    ___    | |    ___   
  | - |  | .__/  | |   \__ \ \ V  V // _ \  / _ \   | |   / -_)  
  |_|_|  |_|__  _|_|_  |___/  \_/\_/ \___/  \___/  _|_|_  \___|  
_|"""""||"""""||"""""||"""""||"""""||"""""||"""""||"""""||"""""| 
"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-' 
STRING;
        return $string;
    }


    /**
     *[printParameters void]
     * @author  Wongzx <[842687571@qq.com]>
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    private function printParameters()
    {
        $conf = getDi('conf');

        //IP
        echo 'listen address       ' . "\033[32m " . $conf->get('http.host') . " \033[0m" . "\n";

        //端口
        echo 'listen port          ' . "\033[32m " . $conf->get('http.port') . " \033[0m" . "\n";

        //进程数
        echo 'worker num           ' . "\033[32m " . $conf->get('setting.worker_num') . " \033[0m" . "\n";

        //异步任务进程数
        echo 'task worker num      ' . "\033[32m " . $conf->get('setting.task_worker_num') . " \033[0m" . "\n";

    }


    /**
     * help  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function help(): string
    {

        echo $this->iconLogo() . "\n\n";

        echo "\033[36m ApiSwoole \033[0mversion \033[33m" . self::$version . " \033[0m" . date('Y-m-d H:i:s') . "\n\n";

        $helpString = <<<HELPSTRING
\033[32m start \033[0m           启动服务
\033[32m stop \033[0m            停止服务
\033[32m update \033[0m          升级系统
\033[32m reload \033[0m          重装服务
HELPSTRING;
        echo $helpString . "\n";
    }


}