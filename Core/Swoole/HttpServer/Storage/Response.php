<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9/009
 * Time: 10:41
 */

namespace Core\Swoole\HttpServer\Storage;

use Core\Component\IO\Stream;
use Core\Swoole\Session\Cookie;


class Response extends Status
{

    use MessageTrait;

    const STATUS_NOT_END = 0; //结束状态

    const STATUS_LOGICAL_END = 1; //逻辑终点

    const STATUS_REAL_END = 2; //真正的结束状态

    protected static $instance;

    private $swooleHttpResponse = null;

    private $isEndResponse = 0;//1 逻辑end  2真实end

    private $cookies = [];

    static function getInstance(\swoole_http_response $response = null)
    {
        if ($response !== null) {
            self::$instance = new Response($response);
        }
        return self::$instance;
    }

    function __construct(\swoole_http_response $response)
    {
        parent::__construct();
        $this->swooleHttpResponse = $response;
    }

    /**
     * end  [处理 Response 并发送数据]
     * swoole 发送Http响应体，并结束请求处理。
     * @param bool $realEnd
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function send($realEnd = false)
    {

        if ($this->isEndResponse == self::STATUS_NOT_END) {
//            Session::getInstance()->close();
            $this->isEndResponse = self::STATUS_LOGICAL_END;
        }
        if ($realEnd === true && $this->isEndResponse !== self::STATUS_REAL_END) {
            $this->isEndResponse = self::STATUS_REAL_END;

            //结束处理
            $status = $this->getStatusCode();
            //发送Http状态码。 必须在$response->end之前执行status
            $this->swooleHttpResponse->status($status);


            /**
             *写标题swoole响应
             */
            $headers = $this->getHeaders();
            foreach ($headers as $header => $val) {
                foreach ($val as $sub) {
                    $this->swooleHttpResponse->header($header, $sub);
                }
            }

            /**
             * Cookies
             * TODO: 设置Cookie
             */
            $cookies = $this->getCookies();
            foreach ($cookies as $cookie) {
                $this->swooleHttpResponse->cookie($cookie->getName(), $cookie->getValue(), $cookie->getExpire(), $cookie->getPath(), $cookie->getDomain(), $cookie->getSecure(), $cookie->getHttponly());
            }

            $write = $this->getBody()->__toString();

            if (!empty($write)) {
                //启用Http Chunk分段向浏览器发送相应内容。关于Http Chunk可以参考Http协议标准文档。
                $this->swooleHttpResponse->write($write);
            }

            /**
             * 关闭流和任何底层资源。
             */
            $this->getBody()->close();


            $this->swooleHttpResponse->end();
        }
    }



    /**
     * write  [json格式数据输出]
     * @param $obj
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function write($obj, int $statusCode = 200, string $msg = 'success')
    {
        if (!$this->isEndResponse()) {
            $data = [
                "code" => $statusCode,
                "msg" => $msg,
                "data" => $obj
            ];
            if (is_object($data)) {
                if (method_exists($data, "__toString")) {
                    $data = $data->__toString();
                } else if (method_exists($data, 'jsonSerialize')) {
                    $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } else {
                    $data = var_export($data, true);
                }
            } else if (is_array($data)) {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            $this->getBody()->write($data);
            $this->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->withStatus($statusCode);
            return true;
        } else {
            trigger_error("write");
            return false;
        }
    }


    /**
     * output  [html 格式]
     * @param int $statusCode
     * @param null $result
     * @param null $msg
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function assign($obj)
    {
        if (!$this->isEndResponse()) {
            if (is_object($obj)) {
                if (method_exists($obj, "__toString")) {
                    $obj = $obj->__toString();
                } else if (method_exists($obj, 'jsonSerialize')) {
                    $obj = json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } else {
                    $obj = var_export($obj, true);
                }
            } else if (is_array($obj)) {
                $obj = json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            $this->getBody()->write($obj);
            $this->withHeader("Content-type", "text/html;charset=utf-8");
            return true;
        } else {
            trigger_error("response has end");
            return false;
        }
    }

    public function setCookies($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = false, $httponly = false)
    {
        if (!$this->isEndResponse()) {
            $cookie = new Cookie();
            $cookie->setKey($name);
            $cookie->setValue($value);
            $cookie->setExpire($expire);
            $cookie->setPath($path);
            $cookie->setDomain($domain);
            $cookie->setSecure($secure);
            $cookie->setHttponly($httponly);
            $this->withAddedCookie($cookie);
            return true;
        } else {
            trigger_error("response has end");
            return false;
        }
    }

    /**
     * withAddedCookie  [description]
     * @param Cookie $cookie
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return $this
     */
    private function withAddedCookie(Cookie $cookie)
    {
        $this->cookies[$cookie->getName()] = $cookie;
        return $this;
    }


    public function isEndResponse()
    {
        return $this->isEndResponse;
    }

    public function getCookies()
    {
        return $this->cookies;
    }

}