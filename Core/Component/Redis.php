<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21/021
 * Time: 15:22
 */

namespace Core\Component;

use Conf\Config;

/**
 * Class Redis
 * @package Core\Component
 */
class Redis
{
    private $con;

    protected static $instance;

    protected $tryConnectTimes = 0;

    protected $maxTryConnectTimes = 3; //尝试连接3次

    public function __construct()
    {
        $this->connect();
    }


    /**
     * connect  [连接]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function connect()
    {
        $this->tryConnectTimes++;

        $conf = Config::getInstance()->getConf('REDIS');
        $this->con = new \Redis();
        $this->con->connect($conf['HOST'], $conf['PORT']);
//        $this->con->auth($conf['AUTH']);

        if (!$this->ping()) {
            if ($this->tryConnectTimes <= $this->maxTryConnectTimes) {
                return $this->connect();
            } else {
                trigger_error("redis connect fail");
                return null;
            }
        }
        $this->con->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP); //使用内置序列化序列化/
    }

    static function getInstance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new Redis();
        }
        return self::$instance;
    }


    /**
     * rPush  [description]
     * Redis Rpush 命令用于将一个或多个值插入到列表的尾部(最右边)。
     * 如果列表不存在，一个空列表会被创建并执行 RPUSH 操作。 当列表存在但不是列表类型时，返回一个错误。
     * @param $key
     * @param $val
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function rPush($key, $val)
    {
        try {
            return $this->con->rpush($key, $val);
        } catch (\Exception $exception) {
            $this->connect();
            //尝试再值插入到列表
            if ($this->tryConnectTimes <= $this->maxTryConnectTimes) {
                return $this->rPush($key, $val);
            } else {
                return false;
            }
        }
    }


    /**
     * lPop  [Redis Lpop 命令用于移除并返回列表的第一个元素。]
     * @param $key
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function lPop($key)
    {
        try {
            return $this->con->lPop($key);
        } catch (\Exception $exception) {
            //尝试再移除并返回列表
            $this->connect();
            if ($this->tryConnectTimes <= $this->maxTryConnectTimes) {
                return $this->lPop($key);
            } else {
                return false;
            }
        }
    }


    /**
     * lSize  [返回的列表的长度。如果列表不存在或为空，该命令返回0。如果该键不是列表，该命令返回]
     * @param $key
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function lSize($key)
    {
        try {
            return $this->con->lSize($key);
        } catch (\Exception $exception) {
            //尝试再返回的列表的长度
            $this->connect();
            if ($this->tryConnectTimes <= $this->maxTryConnectTimes) {
                return $this->lSize($key);
            } else {
                return false;
            }
        }

    }

    public function getRedisConnect()
    {
        return $this->con;
    }

    /**
     * ping  [判断是否连接成功]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    private function ping()
    {
        try {
            $ret = $this->con->ping();
            if (!empty($ret)) {
                $this->tryConnectTimes = 0;
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }


}