<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4/004
 * Time: 14:46
 */

namespace Core\Component\Spl;

class SplArray extends \ArrayObject
{
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        var_dump($this[$name] = $value);
    }
}