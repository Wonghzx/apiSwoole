<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22/022
 * Time: 15:33
 */

namespace Core\Console;

use Core\Component\Di;
use Core\Console\Input\Input;
use Core\Core;
use Core\Swoole\Server;
use Dotenv\Dotenv;

/**
 * Class Console 控制室
 * @package Core\Console
 */
class Console
{

    private static $_instance;


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
    private static $id;


    static public function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }


    public function __construct()
    {
        self::$id = time();
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
                stopServer();
                break;
            case 'reload': //重载服务
                reloadServer();
                break;
            case 'update': //升级系统
                break;
            case 'version': //版本号
                echo "";
                break;
            case 'help': //帮助
            default: {
                help();
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
     * stopServer  [停止]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function stopServer()
    {

    }


    /**
     * reloadServer  [重载服务]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function reloadServer()
    {

    }


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
        $conf = Di::getInstance()->get('conf');

        //IP
        echo 'listen address       ' . "\033[32m " . $conf->get('http.host') . " \033[0m" . "\n";

        //端口
        echo 'listen port          ' . "\033[32m " . $conf->get('http.port') . " \033[0m" . "\n";

        //进程数
        echo 'worker num           ' . "\033[32m " . $conf->get('setting.worker_num') . " \033[0m" . "\n";

        //异步任务进程数
        echo 'task worker num      ' . "\033[32m " . $conf->get('setting.task_worker_num') . " \033[0m" . "\n";

    }


}