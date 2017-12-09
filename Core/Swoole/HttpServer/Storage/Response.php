<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9/009
 * Time: 10:41
 */

namespace Core\Swoole\HttpServer\Storage;

class Response
{

    protected static $instance;


    static function getInstance(\swoole_http_response $response = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = new Response($response);
        }
        return self::$instance;
    }

    function __construct($response)
    {

    }
}