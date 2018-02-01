<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1/001
 * Time: 11:05
 */

namespace Core\Swoole\Async\Redis;
class RedisConnect
{

    private static $instance;

    /**
     * @var string redis server Ip
     */
    private $host = '127.0.0.1';

    /**
     * @var int 端口
     */
    private $port = 6379;

    /**
     * @var int 验证密码
     */
    private $password = 123456;

    /**
     * @var bool 短链接(同上)
     */
    private $open = false;

    /**
     * @var bool 长链接，本地host，端口为6379，超过60秒放弃链接
     */
    private $pconnect = 60;

    /**
     * @var bool 长链接(同上)
     */
    private $popen = false;

    /**
     * @var void 管理者
     */
    private $handler;


    static public function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new static();

        return self::$instance;
    }


    /**
     * handler  [description]
     */
    public function handler()
    {

        if ($this->handler === null) {
            $this->handler = $this->connect();
        }
        return $this->handler;
    }

    /**
     * connect  [创建 handler]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function connect()
    {

        $this->handler = new \Redis();

        $this->handler->connect($this->getHost(), $this->getPort());
        if (getConf('redis.auth')) {
            $this->handler->auth($this->getPassword());
        }

        //统一key前缀
        if (getConf()->has('redis.preKey')) {
            $preKey = getConf('redis.preKey');
            $this->handler->setOption(\Redis::OPT_PREFIX, $preKey);
        }

        return $this->handler;

    }


    /**
     * getHost  [获取redis Server Ip  ]
     * @return mixed|string|static
     */
    private function getHost(): string
    {
        $host = getConf('redis.host');
        if (!empty($host))
            $this->host = $host;

        return $this->host;
    }

    /**
     * getPort  [获取端口]
     * @return int|mixed|static
     */
    private function getPort(): int
    {
        $port = getConf('redis.port');
        if (!empty($port))
            $this->port = $port;

        return $this->port;
    }

    private function getPassword(): int
    {
        $password = getConf('redis.password');
        if (!empty($password))
            $this->password = $password;

        return $this->password;
    }
}