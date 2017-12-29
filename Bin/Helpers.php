<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19/019
 * Time: 9:48
 */

if (!function_exists('getDi')) {
    /**
     * getDi  [description]
     * @param null $abstract
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed|static
     */
    function getDi($abstract = null)
    {
        if (is_null($abstract)) {
            return \Core\Component\Di::getInstance();
        }
        return \Core\Component\Di::getInstance()->get($abstract);
    }

}

if (!function_exists('getConf')) {
    /**
     * getConf  [description]
     * @param string $key
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed|static
     */
    function getConf(string $key)
    {
        $conf = getDi('conf');
        if (is_null($key)) {
            return $conf;
        }
        return $conf->get($key);
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


if (!function_exists('_env')) {
    /**
     *[env array|bool|false|mixed|string|void]
     * @author  Wongzx <[842687571@qq.com]>
     * @param $key
     * @param null $default
     * @copyright Copyright (c)
     * @return    [type]        [description]
     */
    function _env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (defined($value)) {
            $value = constant($value);
        }

        return $value;
    }
}
