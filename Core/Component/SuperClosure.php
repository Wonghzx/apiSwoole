<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14/014
 * Time: 11:12
 */

namespace Core\Component;

use SuperClosure\Serializer;

/**
 * Class SuperClosure 闭包
 * @package Core\Component
 */
class SuperClosure
{
    protected $func;

    protected $serializer;

    protected $serializedJson;

    protected $isSerialized = 0;

    function __construct(\Closure $func)
    {
        $this->func = $func;
        $this->serializer = new Serializer();
    }

    public function __sleep()
    {
        // TODO: Implement __sleep() method.
        $this->serializedJson = $this->serializer->serialize($this->func);
        $this->isSerialized = 1;
        return ["serializedJson", 'isSerialized'];
    }

    public function __wakeup()
    {
        // TODO: Implement __wakeup() method.
        $this->serializer = new Serializer();
        $this->func = $this->serializer->unserialize($this->serializedJson);
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
        /*
         * prevent call before serialized
         */
        $args = func_get_args();
        if ($this->isSerialized) {
            $func = $this->serializer->unserialize($this->serializedJson);
        } else {
            $func = $this->func;
        }
        return call_user_func_array($func, $args);
    }
}