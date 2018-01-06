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
use Core\Console\Style\Style;

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
        'help', //帮助
    ];

    /**
     * 参数输入
     * @var Input
     */
    private $input = [];

    /**
     * @var $monitor
     */
    private $monitor = [];


    /**
     * 每个命令唯一ID
     * @var ID
     */
    private static $pid;

    /**
     * @var $serverType
     */
    private $serverType;

    static public function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }


    public function run()
    {
        try {
            // 默认命令解析
            $cmd = Input::getInstance()->getCommand();

            $this->input = explode(':', $cmd);
            $conf = getDi('conf');
            if (!in_array($this->input[0], self::DEFAULT_CMD)) {
                switch ($this->input[0]) {
                    case 'tcp':
                        $conf->set('setting.server_type', 'SERVER_TYPE_SERVER');
                        $this->monitor['host'] = $conf->get('tcp.host');
                        $this->monitor['port'] = $conf->get('tcp.port');
                        $this->serverType = 'Server';
                        break;
                    case 'socket':
                        $conf->set('setting.server_type', 'SERVER_TYPE_WEB_SOCKET');
                        $this->monitor['host'] = $conf->get('socket.host');
                        $this->monitor['port'] = $conf->get('socket.port');
                        $this->serverType = 'WebSocket';
                        break;
                    case 'http':
                        $conf->set('setting.server_type', 'SERVER_TYPE_WEB');
                        $this->serverType = 'HttpServer';
                        break;
                    default: {
                        $errorCommand = [
                            '               <warning>Warning: Information Panel</warning>     ',
                            '******************************************************************',
                            '<red>-bash:  ' . $this->input[0] . ':  command not found</red>',
                            '<yellow>Usage:</yellow>',
                            '<faintly>   php apiswoole help</faintly>',
                            '<yellow>Commands:</yellow>',
                            '<faintly>   You can input tcp:start to Start the Swoole_server</faintly>',
                            '<faintly>   You can input http:start to Start the HttpServer can start with direct start</faintly>',
                            '<faintly>   You can input socket:start to Start the WebSocket</faintly>',
                            '******************************************************************',
                        ];
                        $this->writeln(implode("\n", $errorCommand), true, true);

                    }
                }
                $cmd = $this->input[1];
            } else {
                $conf->set('setting.server_type', 'SERVER_TYPE_WEB');
                $this->serverType = 'HttpServer';
            }
            if (!$this->monitor) {
                $this->monitor['host'] = $conf->get('http.host');
                $this->monitor['port'] = $conf->get('http.port');
            }
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
            case  'help': //帮助
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
        $this->iconLogo();

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
    private function iconLogo()
    {

        $string = <<<ICONLOGO
        <normal>
         /\                         .-.                  .;     
     _  / |             .-. .;;;.`-'                    .;'     
    (  /  |  .`..:.     `-';;  (_)`;     .-.-.   .-.   .;  .-.  
     `/.__|_.' ;;  :   ;'  `;;;.  ;  ;   ;;   ;';   ;':: .;.-'  
 .:' /    |    ;;_.`_.;:._._   `: `.' `.' `;;'  `;;'_;;_.-`:::' 
(__.'     `-' .;'         (_.;;;'                               
</normal>
ICONLOGO;
        $defaultMenu = ' <info>ApiSwoole</info> Version <yellow>' . self::$version . '</yellow> ' . date('Y-m-d H:i:s') . "\n";
        $this->writeln($string);
        $this->writeln($defaultMenu);
    }


    /**
     *[printParameters void]
     * @author  Wongzx <[842687571@qq.com]>
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    private function printParameters()
    {
        $options = ' <yellow>Information Panel:</yellow>  ' . $this->serverType . ' version: <yellow>' . SWOOLE_VERSION . '</yellow>
      Listen Address:     <success>' . $this->monitor['host'] . '</success>
      Listen Port:        <success>' . $this->monitor['port'] . '</success>
      Worker Num:         <success>' . getConf('setting.worker_num') . '</success>
      Task Worker Num:    <success>' . getConf('setting.task_worker_num') . '</success>';
        $this->writeln($options);

    }


    /**
     * help  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function help(): string
    {
        $this->iconLogo();

        $helpString = <<<HELPSTRING
<info> start</info>          启动服务
<info> stop</info>           停止服务
<info> update</info>         升级系统
<info> reload</info>         重装服务
HELPSTRING;
        $this->writeln($helpString);
    }


    /**
     *[writeln void]
     * @author  Wongzx <[842687571@qq.com]>
     * @param string $messages 信息
     * @param bool $newline 是否换行
     * @param bool $quit 是否退出
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    private function writeln($messages = '', $newline = true, $quit = false)
    {
        // 文字里面颜色标签翻译
        Style::init();
        $messages = Style::t($messages);
        // 输出文字
        echo $messages;
        if ($newline) {
            echo "\n";
        }
        // 是否退出
        if ($quit) {
            exit();
        }
    }

}