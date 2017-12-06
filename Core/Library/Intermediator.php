<?php
/**
 * Swoole Develop Structure
 */

/**
 * 创建一个异步服务器程序，支持TCP、UDP、UnixSocket 3种协议，支持IPv4和IPv6，支持SSL/TLS单向双向证书的隧道加密。
 * 使用者无需关注底层实现细节，仅需要设置网络事件的回调函数即可。
 * Class swooleServer
 */
class swooleServer extends Swoole\Server
{

}

class swooleHttpServer extends Swoole\Http\Server
{

}