<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12/012
 * Time: 11:49
 */

namespace Http\Controller;

use Core\AbstractInterface\AbstractViewController;
use Core\Swoole\AsyncTaskManager;
use Core\Swoole\Timer\Timer;

class Test extends AbstractViewController
{
    public function taskManager()
    {

        /**
         * 测试异步添加任务
         */

        AsyncTaskManager::getInstance()->addTask(function () {
            $address = 'email@address';
            $content = "mail body";
            echo $address . "\n";
            echo $content . "\n";
        });


    }


    public function testTimer()
    {
        $t = date('Y-m-d H:i:s');


        /**
         * 定时器
         */
        Timer::addTimer('20000', function () use ($t) {
            echo 'before :' . $t . "\n";

            echo 'now :' . date('Y-m-d H:i:s') . "\n";
            echo '---------------- \\n';
        });


        /**
         * 指定的时间后执行函数 执行完成后就会销毁
         */
        Timer::addAfter('20000', function () use ($t) {
            echo 'before :' . $t . "\n";

            echo 'now :' . date('Y-m-d H:i:s') . "\n";

            echo '---------------- \\n';
        });

    }
}