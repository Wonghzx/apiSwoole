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