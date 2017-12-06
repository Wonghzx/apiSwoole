<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6/006
 * Time: 11:33
 */

namespace Core\AbstractInterface;

abstract class AbstractSwooleServerConf
{

    /**
     * listenIp  [description]
     * @copyright Copyright (c)
     */
    abstract public function getListenIp();

    /**
     * listenPort  [description]
     * @copyright Copyright (c)
     */
    abstract public function getListenPort();

    /**
     * workerSetting  [description]
     * @copyright Copyright (c)
     */
    abstract public function getWorkerSetting();

    /**
     * workerNum  [description]
     * @copyright Copyright (c)
     */
    abstract public function getWorkerNum();

    /**
     * taskWorkerNum  [description]
     * @copyright Copyright (c)
     */
    abstract public function getTaskWorkerNum();

    /**
     * serverName  [description]
     * @copyright Copyright (c)
     */
    abstract public function getServerName();

    /**
     * runMode  [description]
     * @copyright Copyright (c)
     */
    abstract public function getRunMode();

    /**
     * serverType  [description]
     * @copyright Copyright (c)
     */
    abstract public function getServerType();

    /**
     * socketType  [description]
     * @copyright Copyright (c)
     */
    abstract public function getSocketType();
}