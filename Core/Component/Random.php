<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13/013
 * Time: 14:52
 */

namespace Core\Component;
/**
 * Class Random 随机生成
 * @package Core\Component
 */
class Random
{
    private static $lastTimestamp = 0;

    private static $lastSequence = 0;

    private static $sequenceMask = 4095;

    private static $twepoch = 1508945092000;

    /**
     * randStr  [随机生成字符串]
     * @param $length
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool|string
     */
    static function randStr($length)
    {
        return substr(str_shuffle("abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ23456789"), 0, $length);
    }


    /**
     * randNumStr  [随机生成数字]
     * @param $length
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    static function randNumStr($length)
    {
        $chars = [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        ];
        $password = '';
        while (strlen($password) < $length) {
            $password .= $chars[rand(0, 9)];
        }
        return $password;
    }


    /**
     * snowFlake  [生成雪花算法的随机编号]
     * @param int $dataCenterID 数据中心ID 0-31
     * @param int $workerID 任务进程ID 0-31
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return int 分布式ID
     */
    static function snowFlake($dataCenterID = 0, $workerID = 0)
    {
        // 41bit timestamp + 5bit dataCenter + 5bit worker + 12bit

        $timestamp = self::timeGen();

        if (self::$lastTimestamp == $timestamp) {
            self::$lastSequence = (self::$lastSequence + 1) & self::$sequenceMask;
            if (self::$lastSequence == 0) $timestamp = self::tilNextMillis(self::$lastTimestamp);
        } else {
            self::$lastSequence = 0;
        }
        self::$lastTimestamp = $timestamp;

        $snowFlakeId = (($timestamp - self::$twepoch) << 22) | ($dataCenterID << 17) | ($workerID << 12) | self::$lastSequence;
        return $snowFlakeId;
    }


    /**
     * unSnowFlake  [反向解析雪花算法生成的编号]
     * @param $snowFlakeId int|float $snowFlakeId
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    static function unSnowFlake($snowFlakeId)
    {
        $Binary = str_pad(decbin($snowFlakeId), 64, '0', STR_PAD_LEFT);
        return [
            'timestamp' => bindec(substr($Binary, 0, 41)) + self::$twepoch,
            'dataCenterID' => bindec(substr($Binary, 42, 5)),
            'workerID' => bindec(substr($Binary, 47, 5)),
            'sequence' => bindec(substr($Binary, -12)),
        ];
    }


    /**
     * tilNextMillis  [等待下一毫秒的时间戳]
     * @param $lastTimestamp
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return float
     */
    private static function tilNextMillis($lastTimestamp)
    {
        $timestamp = self::timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = self::timeGen();
        }
        return $timestamp;
    }


    /**
     * timeGen  [获取毫秒级时间戳]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return float
     */
    private static function timeGen()
    {
        return (float)sprintf('%.0f', microtime(true) * 1000);
    }
}