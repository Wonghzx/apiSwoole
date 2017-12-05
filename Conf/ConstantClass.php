<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4/004
 * Time: 13:43
 */

namespace Conf;


class ConstantClass
{

    private static $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    function __construct()
    {
        self::initializeConstant();
    }

    private static function initializeConstant()
    {

        /**
         * new swoole_server 服务类型
         */
        define('SERVER_TYPE_SERVER', 'SERVER_TYPE_SERVER');
        define('SERVER_TYPE_WEB', 'SERVER_TYPE_WEB');
        define('SERVER_TYPE_WEB_SOCKET', 'SERVER_TYPE_WEB_SOCKET');


        define('SWOOLE_TCP', 1); //创建tcp socket
        define('SWOOLE_TCP6', 2); //创建tcp ipv6 socket
        define('SWOOLE_UDP', 3); //创建udp socket
        define('SWOOLE_UDP6', 4); //创建udp ipv6 socket
        define('SWOOLE_UNIX_DGRAM', 5);
        define('SWOOLE_UNIX_STREAM', 6);

        /**
         * new swoole_server 构造函数参数
         */
        define('SWOOLE_BASE', 1); //使用Base模式，业务代码在Reactor中直接执行
        define('SWOOLE_THREAD', 2); //使用线程模式，业务代码在Worker线程中执行
        define('SWOOLE_PROCESS', 3); //使用进程模式，业务代码在Worker进程中执行
        define('SWOOLE_PACKET', 0x10);
    }
}