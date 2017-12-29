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
     * @var  server信息
     */
    private static $server;

    /**
     * @var 客户端发来的数据信息
     */
    private static $frame;

    /**
     * @var Server的活动
     */
    private $stats;


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
     * onMessage  [当服务器收到来自客户端的数据帧时会回调此函数]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onMessage()
    {


        if ($this->checkSocketStatus()) {

            $data = $this->analysisPackets(self::$frame->data);


        }

    }


    /**
     * checkSocketStatus  [如何判断连接是否为WebSocket客户端]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function checkSocketStatus()
    {

        print_r(self::$frame);

    }

    /**
     * analysisPackets  [解析数包]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function analysisPackets($data)
    {
        if (is_null($data))
            return [];

        $dat = json_decode($data, true);
        $is_json = (json_last_error() == JSON_ERROR_NONE);
        if (!$is_json) {
            //TODO:
            return $data;
        } else {
            return $dat;
        }
    }
}