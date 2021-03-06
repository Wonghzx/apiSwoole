<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 16:01
 */

namespace Core\Component;

use Core\AbstractInterface\AbstractLoggerWriter;

class Logger
{
    private static $instance = [];

    private $logCategory = 'default';


    static function getInstance($logCategory = 'default')
    {
        if (!isset(self::$instance[$logCategory])) {
            //这样做纯属为了IDE提示
            $instance = new static($logCategory);
            self::$instance[$logCategory] = $instance;
        } else {
            $instance = self::$instance[$logCategory];
        }
        return $instance;
    }

    public function __construct($logCategory)
    {
        $this->logCategory = $logCategory;
    }


    public function log($obj)
    {
        $loggerWriter = getConf('debug.logger_writer');
        if ($loggerWriter instanceof AbstractLoggerWriter) {
            $loggerWriter->writeLog($obj, $this->logCategory, time());
        } else {
            $obj = $this->objectToString($obj);
            $str = "time : " . date("y-m-d H:i:s") . " message: " . $obj . "\n";
            $filePrefix = $this->logCategory . "_" . date('ym');

            $logDirectory = getConf('common.logs') . getConf('debug.log_directory');
            if (!File::createDir($logDirectory)) {
                die("create log Directory:{$logDirectory} fail");
            }
            $filePath = $logDirectory . "/{$filePrefix}.log";
            file_put_contents($filePath, $str, FILE_APPEND | LOCK_EX);
        }
        return $this;
    }

    /**
     * console  [description]
     * @param $obj
     * @param int $saveLog
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return $this
     */
    function console($obj, $saveLog = 1)
    {
        $obj = $this->objectToString($obj);
        echo $obj . "\n";
        if ($saveLog) {
            $this->log($obj);
        }
        return $this;
    }

    /**
     * objectToString  [转字符串]
     * @param $obj
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed|string
     */
    private function objectToString($obj)
    {
        if (is_object($obj)) {
            if (method_exists($obj, "__toString")) {
                $obj = $obj->__toString();
            } else if (method_exists($obj, 'jsonSerialize')) {
                $obj = json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                $obj = var_export($obj, true);
            }
        } else if (is_array($obj)) {
            $obj = json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        return $obj;
    }

}