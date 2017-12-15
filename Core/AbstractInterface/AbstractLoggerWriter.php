<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 16:06
 */

namespace Core\AbstractInterface;
abstract class AbstractLoggerWriter
{
    abstract public function writeLog($obj, $logCategory, $timeStamp);
}
