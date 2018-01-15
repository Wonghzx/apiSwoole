<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15/015
 * Time: 18:03
 */

namespace Http\Service;


class DataStream
{
    private $data = null;

    private static $instance;


    static public function getInstance($data)
    {
        if (!isset(self::$instance))
            self::$instance = new static($data);

        return self::$instance;
    }

    public function __construct($data)
    {
        $this->data = $data;
    }
}