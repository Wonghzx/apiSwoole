<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28/028
 * Time: 16:02
 */

namespace Http\SocketController;

/**
 * Class Message 当服务器收到来自客户端的数据帧时会回调此函数
 * @package Core\Swoole\WebSocket
 */
class Message
{
    /**
     * @var 单例模式
     */
    private static $instance;

    /**
     * @var
     */
    private static $server;

    /**
     * @var
     */
    private static $frame;


    static public function getInstance($server, $frame)
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        self::$server = $server;
        self::$frame = $frame;
        return self::$instance;
    }


    /**
     * onMessage  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onMessage()
    {
        $chatData = json_decode(self::$frame->data, true);

        /**
         * 判断是否第一次进来
         */
        if (isset($chatData['content'])) {

        } else {

        }
    }
}