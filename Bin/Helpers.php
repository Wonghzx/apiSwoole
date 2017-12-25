<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19/019
 * Time: 9:48
 */

if (!function_exists('getDi')) {
    function getDi($abstract = null)
    {
        if (is_null($abstract)) {
            return \Core\Component\Di::getInstance();
        }
        return \Core\Component\Di::getInstance()->get($abstract);
    }

}


if (!function_exists('recursionDirFiles')) {

    /**
     * recursionDirFiles  [递归目录和文件]
     * @param $dir
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    function recursionDirFiles($dir)
    {
        $data = [];
        if (is_dir($dir)) {
            //是目录的话，先增当前目录进去
            $data[] = $dir;
            //scandir 是默认禁用PHP危险函数(可以去 php.ini 中搜索 disable_functions 去掉 scandir)
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $data = array_merge($data, recursionDirFiles($dir . "/" . $file));
            }
        } else {
            $data[] = $dir;
        }
        return $data;
    }
}

if (!function_exists('shaEncrypt')) {

    /**
     * shaEncrypt  [webSocket握手 Hash 安全哈希算法]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    function hash1Encrypt($req)
    {
        $mask = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";

        return base64_encode(sha1($req . $mask, true));
    }
}