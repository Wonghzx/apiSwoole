<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9/009
 * Time: 10:41
 */

namespace Core\Swoole\HttpServer\Storage;

class Request
{

    protected static $instance;

    private $httpRequest = null;

    static function getInstance(\swoole_http_request $request = null)
    {
        if ($request !== null) {
            self::$instance = new Request($request);
        }
        return self::$instance;
    }

    public function __construct(\swoole_http_request $request)
    {
        $this->httpRequest = $request;


        //协议 HTTP 1.0 规定浏览器与服务器只保持短暂的连接    1.1 HTTP 1.1支持长连接
        $protocol = str_replace('HTTP/', '', $this->httpRequest->server['server_protocol']);

        /*
         * 获取原始的POST包体，用于非application/x-www-form-urlencoded格式的Http POST请求。
         * foo = bor
         */
        $rawContent = $this->httpRequest->rawContent();
        $body = new DataStream($rawContent);
        $uri = $this->initUri();
        $files = $this->initFiles();

        /**
         * 请求的方法
         * POST GET
         */
        $method = $this->httpRequest->server['request_method'];

    }


    private function initUri()
    {
        $uri = new Uri();
        $uri->withScheme("http");
        $uri->withPath($this->httpRequest->server['path_info']);
        $query = isset($this->httpRequest->server['query_string']) ? $this->httpRequest->server['query_string'] : '';
        $uri->withQuery($query);
        $host = $this->httpRequest->header['host'];
        $host = explode(":", $host);
        $uri->withHost($host[0]);
        $port = isset($host[1]) ? $host[1] : 80;
        $uri->withPort($port);
        return $uri;
    }

    /**
     * initFiles  [
     * 文件上传信息。类型为以form名称为key的二维数组。与PHP的$_FILES相同。
     * 最大文件尺寸不得超过package_max_length设置的值。请勿使用Swoole\Http\Server处理大文件上传。
     * ]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function initFiles()
    {
        if (isset($this->httpRequest->files)) {
            $normalized = [];
            foreach ($this->httpRequest->files as $key => $value) {
                $normalized[$key] = new UploadFile(
                    $value['tmp_name'],
                    (int)$value['size'],
                    (int)$value['error'],
                    $value['name'],
                    $value['type']
                );
            }
            return $normalized;
        } else {
            return [];
        }
    }

    /**
     * initCookie  [HTTP请求携带的COOKIE信息，与PHP的$_COOKIE相同，格式为数组。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    private function initCookie()
    {
        return isset($this->httpRequest->cookie) ? $this->httpRequest->cookie : [];
    }

    /**
     * initPost  [HTTP POST参数，格式为数组。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    private function initPost()
    {
        return isset($this->httpRequest->post) ? $this->httpRequest->post : [];
    }

    /**
     * initGet  [Http请求的GET参数，相当于PHP中的$_GET，格式为数组。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    private function initGet()
    {
        return isset($this->httpRequest->get) ? $this->httpRequest->get : [];
    }
}