<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6/006
 * Time: 11:34
 */

namespace Core\Swoole;

use Conf\Config AS conf;

class Config extends \Core\AbstractInterface\AbstractSwooleServerConf
{
    private $listenIp;

    private $listenPort;

    private $workerSetting;

    private $workerNum;

    private $taskWorkerNum;

    private $serverName;

    private $runMode;

    private $serverType;

    private $socketType;

    private static $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->listenIp = conf::getInstance()->getConf("SERVER.LISTEN");
        $this->listenPort = conf::getInstance()->getConf("SERVER.PORT");
        $this->workerSetting = conf::getInstance()->getConf("SERVER.CONFIG");
        $this->workerNum = conf::getInstance()->getConf("SERVER.CONFIG.worker_num");
        $this->taskWorkerNum = conf::getInstance()->getConf("SERVER.CONFIG.task_worker_num");
        $this->serverName = conf::getInstance()->getConf("SERVER.SERVER_NAME");
        $this->runMode = conf::getInstance()->getConf("SERVER.RUN_MODE");
        $this->serverType = conf::getInstance()->getConf("SERVER.SERVER_TYPE");
        $this->socketType = conf::getInstance()->getConf("SERVER.SOCKET_TYPE");
    }

    /**
     * listenIp  [description]
     * @copyright Copyright (c)
     */
    public function getListenIp()
    {
        // TODO: Implement listenIp() method.
        return $this->listenIp;
    }

    /**
     * listenPort  [description]
     * @copyright Copyright (c)
     */
    public function getListenPort()
    {
        // TODO: Implement listenPort() method.
        return $this->listenPort;
    }

    /**
     * workerSetting  [description]
     * @copyright Copyright (c)
     */
    public function getWorkerSetting()
    {
        // TODO: Implement workerSetting() method.
        return $this->workerSetting;
    }

    /**
     * workerNum  [description]
     * @copyright Copyright (c)
     */
    public function getWorkerNum()
    {
        // TODO: Implement workerNum() method.
        return $this->workerNum;
    }

    /**
     * taskWorkerNum  [description]
     * @copyright Copyright (c)
     */
    public function getTaskWorkerNum()
    {
        // TODO: Implement taskWorkerNum() method.
        return $this->taskWorkerNum;
    }

    /**
     * serverName  [description]
     * @copyright Copyright (c)
     */
    public function getServerName()
    {
        // TODO: Implement serverName() method.
        return $this->serverName;
    }

    /**
     * runMode  [description]
     * @copyright Copyright (c)
     */
    public function getRunMode()
    {
        // TODO: Implement runMode() method.
        return $this->runMode;
    }

    /**
     * serverType  [description]
     * @copyright Copyright (c)
     */
    public function getServerType()
    {
        // TODO: Implement serverType() method.
        return $this->serverType;
    }

    /**
     * socketType  [description]
     * @copyright Copyright (c)
     */
    public function getSocketType()
    {
        // TODO: Implement socketType() method.
        return $this->socketType;
    }
}