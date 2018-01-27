<?php
/**
 * [UrlParser.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 10/12/17
 * Time: 下午1:49
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Core\Swoole\HttpServer;

use Core\Swoole\HttpServer\Storage\Request;

class UrlParser
{
    static public function pathInfo($path = null)
    {
        if($path == null){
            $path = Request::getInstance()->getUri()->getPath();
        }
        $basePath = dirname($path);
        $info = pathInfo($path);
        if($info['filename'] != 'index'){
            if($basePath == '/'){
                $basePath = $basePath.$info['filename'];
            }else{
                $basePath = $basePath.'/'.$info['filename'];
            }
        }
        return Request::getInstance()->getUri()->getPath();
    }
}