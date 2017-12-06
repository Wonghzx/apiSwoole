<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6/006
 * Time: 11:22
 */

namespace Core\Swoole;

class Server
{
    protected static $instance;

    private $serverApi;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    function __construct()
    {
        $conf = Config::getInstance();
        $serverType = $conf->getServerType();
        $ip = $conf->getListenIp();  //ip
        $port = $conf->getListenPort(); //端口
        $runMode = $conf->getRunMode();
        $socketType = $conf->getSocketType();
        print_r($serverType);
        switch ($serverType) {
            case SERVER_TYPE_SERVER:
                $this->serverApi = new \swooleServer($ip,$port,$runMode,$socketType);
                break;
            case SERVER_TYPE_WEB:
                $this->serverApi = new \swooleServer($ip,$port,$runMode,$socketType);
                break;
            case SERVER_TYPE_WEB_SOCKET:
                break;
            default : {
                exit('server type error');
            }

        }

    }

    public function startServer()
    {

    }
}