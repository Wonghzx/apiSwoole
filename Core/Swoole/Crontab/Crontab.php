<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/19/019
 * Time: 15:32
 */

namespace Core\Swoole\Crontab;

use Core\Component\Ioc\DocParser;
use Core\Component\Ioc\Ioc;
use Http\Tasks\TestCrontab;

/**
 * Class Crontab 定时任务
 * @package Core\Swoole\Crontab
 */
class Crontab
{

    private static $instance;


    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * init  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function init(): bool
    {
        $crontab = (int)getConf('common.crontab');

        if ($crontab !== 1) {
            return false;
        }

        $pocFile = $this->selectDir();

        $a = '\Http\Tasks\TestCrontab';
        $as = Ioc::getInstance($a);
        $ax = $as->create();
        $x = $as->parseMethodsDoc();
//        $a = DocParser::getInstance()->analysis($x);



        return true;
    }


    private function selectDir(): array
    {
        $dir = recursionDirFiles(ROOT . '/Http/Tasks');
        $dirs = [];
        foreach ($dir AS $item => $value) {
            if (is_file($value)) {
                $dirs[] = $value;
            }
        }
        return $dirs;
    }
}