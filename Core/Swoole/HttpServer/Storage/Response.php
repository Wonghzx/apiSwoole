<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9/009
 * Time: 10:41
 */

namespace Core\Swoole\HttpServer\Storage;

class Response extends Message
{


    const STATUS_NOT_END = 0; //结束状态

    const STATUS_LOGICAL_END = 1; //逻辑终点

    const STATUS_REAL_END = 2; //真正的结束状态

    private $statusCode = 200;

    private $reasonPhrase = 'OK';

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
        $this->swooleHttpResponse = $response;
    }

    public function end($realEnd = false)
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

            $headers = $this->getHeaders();
            foreach ($headers as $header => $val) {
                foreach ($val as $sub) {
                    $this->swooleHttpResponse->header($header, $sub);
                }
            }
            $cookies = $this->getCookies();
            foreach ($cookies as $cookie) {
                $this->swooleHttpResponse->cookie($cookie->getName(), $cookie->getValue(), $cookie->getExpire(), $cookie->getPath(), $cookie->getDomain(), $cookie->getSecure(), $cookie->getHttponly());
            }

            $write = $this->getBody()->__toString();

            if (!empty($write)) {
                //启用Http Chunk分段向浏览器发送相应内容。关于Http Chunk可以参考Http协议标准文档。
                $this->swooleHttpResponse->write($write);
            }
            $this->getBody()->close();
            $this->swooleHttpResponse->end();
        }
    }

    public function write($obj)
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
            return true;
        } else {
            trigger_error("write");
            return false;
        }
    }

    private function getStatusCode()
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        // TODO: Implement withStatus() method.
        if ($code === $this->statusCode) {
            return $this;
        } else {
            $this->statusCode = $code;
            if (empty($reasonPhrase)) {
                $this->reasonPhrase = Status::getReasonPhrase($this->statusCode);
            } else {
                $this->reasonPhrase = $reasonPhrase;
            }
            return $this;
        }
    }


    public function isEndResponse()
    {
        return $this->isEndResponse;
    }

    private function getCookies()
    {
        return $this->cookies;
    }

}