<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4/004
 * Time: 13:43
 */

namespace Conf;

use Core\Component\Spl\SplArray;

class ConstantClass
{

    private static $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    function __construct()
    {
        $s = $this->get();
        $a = new SplArray($s);
    }

    public function get()
    {
        return [
            'asd' => '123',
            'asdasd' => '234'
        ];
    }
}