<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21/021
 * Time: 18:04
 */

namespace Core\Swoole\WebSocket;

use Http\SocketController\HandShake;
use Http\SocketController\Message;

class Server
{
    /**
     * @var
     */
    private static $instance;


    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function serverStart($server, $conf)
    {
        $conf->set('setting.debug_mode', '1');
        $conf->set('setting.websocket_subprotocol', 'chat');

        $server->set($conf->get('setting'));
        $server->on('handshake', [$this, 'onHandShake']);
        $server->on('open', [$this, 'onOpen']);
        $server->on('message', [$this, 'onMessage']);
        $server->on('close', [$this, 'onClose']);
        $server->on('task', [$this, 'onTask']);
        $server->on('finish', [$this, 'onFinish']);

        $server->start();
    }


    /**
     * onHandShake  [webSocket 处理三次握手验证]
     * WebSocket建立连接后进行握手。WebSocket服务器已经内置了handshake，如果用户希望自己进行握手处理，可以设置onHandShake事件回调函数
     * @param $request
     * @param $response
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    final public function onHandShake($request, $response)
    {
        HandShake::getInstance($request, $response)->onHandShake();
    }


    /**
     * onOpen  [description]
     * @param $server
     * @param $request
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    final public function onOpen($server, $request)
    {

    }


    /**
     * onMessage  [当服务器收到来自客户端的数据帧时会回调此函数。]
     * @param $server
     * @param $frame 接收数据
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    final public function onMessage($server, $frame)
    {
        Message::getInstance($server, $frame)->onMessage();
    }


    /**
     * onClose  [description]
     * @param $server
     * @param $frame
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    final public function onClose($server, $frame)
    {

    }


    /**
     * onRequest  [description]
     * 接收http请求从get获取message参数的值，给用户推送
     * $this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
     * @param $request
     * @param $response
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    final public function onRequest($request, $response)
    {
    }


    /**
     * onTask  [description]
     * @param $server
     * @param $task_id
     * @param $data
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    final public function onTask($server, $task_id, $data)
    {

    }


    /**
     * onFinish  [description]
     * @param $server
     * @param $taskId
     * @param $taskObj
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    final public function onFinish($server, $taskId, $taskObj)
    {

    }

}