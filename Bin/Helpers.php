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
    function getDi($abstract = 'conf')
    {
        return \Core\Component\Bean\Container::getInstance()->offsetGet($abstract);
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
    function getConf(string $key = '', $default = '')
    {
        $conf = getDi('conf');
        if (empty($key)) {
            return $conf;
        }
        return $conf->get($key, $default);
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

if (!function_exists('hideTel')) {
    /**
     * hideTel  [隐藏手机号中间4位]
     * @param $phone
     * @return mixed
     */
    function hideTel($phone)
    {
        $IsWhat = preg_match('/(0[0-9]{2,3}[-]?[2-9][0-9]{6,7}[-]?[0-9]?)/i', $phone);
        if ($IsWhat == 1) {
            return preg_replace('/(0[0-9]{2,3}[-]?[2-9])[0-9]{3,4}([0-9]{3}[-]?[0-9]?)/i', '$1****$2', $phone);
        } else {
            return preg_replace('/(1[3587]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phone);
        }
    }
}


if (!function_exists('timeDiff')) {
    /**
     * timeDiff  [计算两个时间的时差]
     * @param $begin_time
     * @param $end_time
     * @return array
     */
    function timeDiff($begin_time, $end_time)
    {
        if ($begin_time < $end_time) {
            $startTime = $begin_time;
            $endTime = $end_time;
        } else {
            $startTime = $end_time;
            $endTime = $begin_time;
        }
        $timeDiff = $endTime - $startTime;

        $days = intval($timeDiff / 86400);

        $remain = $timeDiff % 86400;

        $hours = sprintf("%02d", intval($remain / 3600));

        $remain = $remain % 3600;

        $mins = sprintf("%02d", intval($remain / 60));

        $secs = sprintf("%02d", $remain % 60);

        $res = ["day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs];

        return $res;
    }
}

if (!function_exists('curlPosts')) {
    /**
     * curlPosts  [description]
     * @param $url
     * @param $data
     * @return mixed
     */
    function curlPosts($url, array $params)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json", "Content-Length: " . strlen($params)));
        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);

        return $result;
    }
}

if (!function_exists('curlGets')) {
    /**
     * curlGets  [description]
     * @param $url
     * @param array $params
     * @param int $timeOut
     * @return mixed
     */
    function curlGets($url, array $params, $timeOut = 300)
    {
        $flag = (strpos($url, '?') !== false) ? '&' : '?';
        $query = http_build_query($params);
        $url = $url . $flag . $query;
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
}
