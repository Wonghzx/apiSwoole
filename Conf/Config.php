<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5/005
 * Time: 10:20
 */

namespace Conf;

use Core\Component\Spl\SplArray;

class Config
{
    private static $instance;

    private $conf;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * sysConf  [swoole服务配置参数]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    private function sysConf()
    {
        return [
            'SERVER' => [
                'LISTEN' => '0.0.0.0',
                'SERVER_NAME' => '',
                'PORT' => 9501,
                'RUN_MODE' => SWOOLE_PROCESS, //不建议更改此项
                'SERVER_TYPE' => SERVER_TYPE_WEB,
                'SOCKET_TYPE' => SWOOLE_TCP,
                'CONFIG' => [ //设置运行时参数
                    'task_worker_num' => 8, //异步任务进程
                    'task_max_request' => 10,
                    'max_request' => 5000,//强烈建议设置此配置项
                    'worker_num' => 8
                ]
            ],
            'DEBUG' => [
                'LOG' => true,
                'DISPLAY_ERROR' => true,
                'ENABLE' => true,
            ],
            'CONTROLLER_POOL' => true //web或wap socket模式有效
        ];
    }


    /**
     * userConf  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    private function userConf()
    {
        return [

        ];
    }

    function __construct()
    {
        $conf = $this->sysConf() + $this->userConf();
        $this->conf = new SplArray($conf);
    }


    /**
     * setSysConf  [框架启动后，无法添加动态配置参数 ，修改配置参数（进程数据独立）]
     * @param $keyPath key
     * @param $data   data
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setSysConf($keyPath, $data)
    {
        $this->conf->set($keyPath, $data);
    }


    public function getConf($pathKey)
    {
        return $this->conf->get($pathKey);
    }


}