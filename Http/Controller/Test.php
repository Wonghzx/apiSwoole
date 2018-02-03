<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12/012
 * Time: 11:49
 */

namespace Http\Controller;

use Core\AbstractInterface\AbstractViewController;
use Core\Component\Session\SessionFacade;
use Core\Swoole\Async\Redis\RedisConnect;
use Core\Swoole\AsyncTaskManager;
use Core\Swoole\Timer\Timer;

class Test extends AbstractViewController
{
    public function taskManager()
    {
        /**
         * 测试异步添加任务
         */

//        $this->view('Index/index');
//        AsyncTaskManager::getInstance()->addTask(function () {
//            $address = 'email@address';
//            $content = "mail body";
//            echo $address . "\n";
//            echo $content . "\n";
//        });
        $this->response()->assign($this->selectSeeks('5'));
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


    public function testUploadFile()
    {
        $info = $this->request()->getUploadedFiles();

        if (!empty($info)) {
            $this->response()->write($info['myfile']);
        }

    }


    private function selectSeeks($num)
    {
        $num = intval($num);
        if ($num < 1 || $num > 5) {
            return [];
        }

        $redis = RedisConnect::getInstance()->handler();

        $selectSeeks = []; // 选中座位
        $leaveNum = $redis->sCard('seeks');

        if ($leaveNum > 0 && $redis->set('lock:', 1, ['nx', 'ex' => 10])) { // 加锁
            $selectSeeks = $redis->sRandMember('seeks', min($num, $leaveNum)); // min避免订票数大于剩余座位数
            $redis->sRem('seeks', $selectSeeks); // 移除被订单的票
        }
        return $selectSeeks;
    }

    public function seat()
    {
        $area = ['A', 'B', 'C', 'D'];

        $sum = 50;
        for ($i = 1; $i < 26; $i++) {
            $sum += 50 + $i * 2;
        }

        $this->response()->assign($sum);
        foreach ($area AS $value) {
            for ($k = 1; $k < $sum; $k++) {
                RedisConnect::getInstance()->handler()->sAdd('seeks', $value . '-' . $k);
            }
        }
    }
}