<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16/016
 * Time: 11:58
 */

namespace Core\Swoole\Async;

interface IAsyncPool
{
    public function execute($data);

    public function prepareOne();
}