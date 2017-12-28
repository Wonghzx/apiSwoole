<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28/028
 * Time: 14:55
 */

namespace Http\SocketController;

/**
 * Class HandShake 处理三次握手验证
 * @package Http\SocketController
 */
class HandShake
{


    /**
     * @var  单例模式
     */
    private static $instance;

    /**
     * @var
     */
    private static $request;

    /**
     * @var
     */
    private static $response;

    /**
     * @var bool
     */
    private static $isHandShake = false;


    static public function getInstance($request, $response)
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        self::$request = $request;
        self::$response = $response;
        return self::$instance;
    }

    /**
     * onHandShake  [description]
     * WebSocket建立连接后进行握手。WebSocket服务器已经内置了handshake，如果用户希望自己进行握手处理，可以设置onHandShake事件回调函数
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function onHandShake(): bool
    {
        if (!self::$isHandShake) {

            //websocket握手连接算法验证
            $secWebSocketKey = self::$request->header['sec-websocket-key'];

            $patten = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
            if (0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
                self::$response->end();
                return false;
            }
            $key = $this->hash1Encrypt($secWebSocketKey); //加密

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
                $headers['Sec-WebSocket-Protocol'] = self::$request->header['sec-websocket-protocol'];
            }

            foreach ($headers as $key => $val) {
                self::$response->header($key, $val);
            }

            self::$response->status(101);
            self::$response->end();
            return true;
        }
    }


    /**
     * hash1Encrypt  [webSocket握手 Hash 安全哈希算法]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function hash1Encrypt(string $req): string
    {
        $mask = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";

        return base64_encode(sha1($req . $mask, true));
    }

}
