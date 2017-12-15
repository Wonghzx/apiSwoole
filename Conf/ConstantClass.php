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

        defined(' VERSION') or define('VERSION', '1.0.0');


        /**
         * new swoole_server 服务类型
         */
        defined('SERVER_TYPE_SERVER') or define('SERVER_TYPE_SERVER', 'SERVER_TYPE_SERVER');
        defined('SERVER_TYPE_WEB') or define('SERVER_TYPE_WEB', 'SERVER_TYPE_WEB');
        defined('SERVER_TYPE_WEB_SOCKET') or define('SERVER_TYPE_WEB_SOCKET', 'SERVER_TYPE_WEB_SOCKET');


        defined('SWOOLE_TCP') or define('SWOOLE_TCP', 1); //创建tcp socket
        defined('SWOOLE_TCP6') or define('SWOOLE_TCP6', 2); //创建tcp ipv6 socket
        defined('SWOOLE_UDP') or define('SWOOLE_UDP', 3); //创建udp socket
        defined('SWOOLE_UDP6') or define('SWOOLE_UDP6', 4); //创建udp ipv6 socket
        defined('SWOOLE_UNIX_DGRAM') or define('SWOOLE_UNIX_DGRAM', 5);
        defined('SWOOLE_UNIX_STREAM') or define('SWOOLE_UNIX_STREAM', 6);

        /**
         * new swoole_server 构造函数参数
         */
        defined('SWOOLE_BASE') or define('SWOOLE_BASE', 1); //使用Base模式，业务代码在Reactor中直接执行
        defined('SWOOLE_THREAD') or define('SWOOLE_THREAD', 2); //使用线程模式，业务代码在Worker线程中执行
        defined('SWOOLE_PROCESS') or define('SWOOLE_PROCESS', 3); //使用进程模式，业务代码在Worker进程中执行
        defined('SWOOLE_PACKET') or define('SWOOLE_PACKET', 0x10);


        /**
         * new swoole_client 构造函数参数
         */
        defined('SWOOLE_SOCK_TCP') or define('SWOOLE_SOCK_TCP', 1); //创建tcp socket
        defined('SWOOLE_SOCK_TCP6') or define('SWOOLE_SOCK_TCP6', 3); //创建tcp ipv6 socket
        defined('SWOOLE_SOCK_UDP') or define('SWOOLE_SOCK_UDP', 2); //创建udp socket
        defined('SWOOLE_SOCK_UDP6') or define('SWOOLE_SOCK_UDP6', 4); //创建udp ipv6 socket
        defined('SWOOLE_SOCK_UNIX_DGRAM') or define('SWOOLE_SOCK_UNIX_DGRAM', 5); //创建udp socket
        defined('SWOOLE_SOCK_UNIX_STREAM') or define('SWOOLE_SOCK_UNIX_STREAM', 6); //创建udp ipv6 socket

        /**
         * sysConf
         */
        defined('TEMP_DIRECTORY') or define('TEMP_DIRECTORY', 'TEMP_DIRECTORY'); //临时目录 Runtime DI
        defined('SESSION_SAVE_PATH') or define('SESSION_SAVE_PATH', ROOT . '/Runtime/Session'); //session 目录 DI
        defined('LOG_DIRECTORY') or define('LOG_DIRECTORY', 'LOG_DIRECTORY'); //日志目录 DI
        defined(' CONTROLLER_MAX_DEPTH') or define('CONTROLLER_MAX_DEPTH', 'CONTROLLER_MAX_DEPTH');
        defined(' SESSION_HANDLER') or define('SESSION_HANDLER', 'SESSION_HANDLER'); //会议处理程序
        defined(' SESSION_HANDLER') or define('SESSION_NAME', 'ApiSwoole'); //session_name
        defined('SESSION_GC_PROBABILITY') or define('SESSION_GC_PROBABILITY', 'SESSION_GC_PROBABILITY'); //
        defined('SESSION_GC_MAX_LIFE_TIME') or define('SESSION_GC_MAX_LIFE_TIME', 'SESSION_GC_MAX_LIFE_TIME'); //session GC 最大生命周期
        defined('ERROR_HANDLER') or define('ERROR_HANDLER', 'ERROR_HANDLER');
        defined('EXCEPTION_HANDLER') or define('EXCEPTION_HANDLER', 'EXCEPTION_HANDLER');
        defined('LOGGER_WRITER') or define('LOGGER_WRITER', 'LOGGER_WRITER');
    }
}