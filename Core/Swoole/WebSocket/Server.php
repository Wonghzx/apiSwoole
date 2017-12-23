<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21/021
 * Time: 18:04
 */

namespace Core\Swoole\WebSocket;
class Server
{
    private static $instance;

    private $conf;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function serverStart($server, $conf)
    {
        $this->conf = $conf;
        $server->set([
            'worker_num' => 8, //进程数
            'daemonize' => false, //1 加入此参数后，执行php server.php将转入后台作为守护进程运行
            'max_request' => 10000, //此参数表示worker进程在处理完n次请求后结束运行。manager会重新创建一个worker进程。此选项用来防止worker进程内存溢出。
            'dispatch_mode' => 2, //1平均分配，2按FD取模固定分配，3抢占式分配，默认为取模(dispatch=2)
            'debug_mode' => 1,
            'websocket_subprotocol' => 'chat'
        ]);

        $server->on('handshake', [$this, 'onHandShake']);
        $server->on('open', [$this, 'onOpen']);
        $server->on('message', [$this, 'onMessage']);
        $server->on('close', [$this, 'onClose']);

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
        //websocket握手连接算法验证
        $secWebSocketKey = $request->header['sec-websocket-key'];

        $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
        if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
            $response->end();
            return false;
        }

        $key = hash1Encrypt($secWebSocketKey); //加密

        $headers = [
            'Upgrade' => 'websocket',
            'Connection' => 'Upgrade',
            'Sec-WebSocket-Accept' => $key,
            'Sec-WebSocket-Version' => '13',
        ];

        // WebSocket connection to 'ws://127.0.0.1:9502/'
        // failed: Error during WebSocket handshake:
        // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }

        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }

        $response->status(101);
        $response->end();
        return true;
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
        $chatData = json_decode($frame->data, true);

        /**
         * 判断是否第一次进来
         */
        if (isset($chatData['content'])) {

        } else {
            //
        }
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

}