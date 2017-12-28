<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21/021
 * Time: 13:44
 */

namespace Core\Swoole\Memory;

use Core\Component\File;
use Core\Component\IO\FileIO;
use Core\Component\Logger;
use Core\Component\Spl\SplArray;

/**
 * Class ShareMemory 文件共享内存
 * @package Core\Component
 */
class FilesShareMemory
{

    const TYPE_INT = 1;

    const TYPE_STRING = 2;

    const TYPE_FLOAT = 3;

    private static $instance;

    private $file;

    private $fileStream; //文件流

    private $ioTimeOut = 200000; //超时时间

    private $isStartTransaction = false; //开始事务

    private $data = null;

    private $serializeType; //序列化类型

    const SERIALIZE_TYPE_JSON = 'SERIALIZE_TYPE_JSON'; //Json 格式数据

    const SERIALIZE_TYPE_SERIALIZE = 'SERIALIZE_TYPE_SERIALIZE'; //Serialize 格式数据


    /**
     * getInstance  [获取单例模式]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function getInstance($serializeType = self::SERIALIZE_TYPE_SERIALIZE, $file = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($serializeType, $file);
        }
        return self::$instance;
    }


    /**
     * ShareMemory constructor.
     * 通过文件+锁的方式来实现数据共享，建议将文件设置到/dev/shm下
     */
    public function __construct($serializeType = self::SERIALIZE_TYPE_JSON, $file = null)
    {
        $this->serializeType = $serializeType;
        if ($file == null) {
            $shareMemory = getConf('common.shareMemory');

            if (!file_exists($shareMemory)) {
                if (!File::createFile($shareMemory)) {
                    Logger::getInstance()->log("create Temp Directory:{$shareMemory} fail");
                }
            }
            $file = $shareMemory;
        }
        $this->file = $file;
    }


    /**
     * setTimeOut  [设置超时]
     * @param $ioTimeOut
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setTimeOut($ioTimeOut)
    {
        $this->ioTimeOut = $ioTimeOut;
    }


    /**
     * startTransaction  [开始事务]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function startTransaction()
    {
        if ($this->isStartTransaction) {
            return true;
        } else {
            $this->fileStream = new FileIO($this->file);
            if ($this->fileStream->getStreamResource()) { //资源型中的流
                //是否阻塞
                if ($this->ioTimeOut) {
                    $takeTime = 0;
                    while (!$this->fileStream->lock(LOCK_EX | LOCK_NB)) {
                        //要取得独占锁定（写入的程序） | 如果不希望 flock() 在锁定时堵塞
                        if ($takeTime > $this->ioTimeOut) {
                            $this->fileStream->close();
                            unset($this->fileStream);
                            return false;
                        }
                        usleep(5);
                        $takeTime = $takeTime + 5;
                    }
                    $this->isStartTransaction = true;
                    $this->read();
                    return true;
                } else {

                    if ($this->fileStream->lock()) {
                        $this->isStartTransaction = true;
                        $this->read();
                        return true;
                    } else {
                        $this->fileStream->close();
                        unset($this->fileStream);
                        return false;
                    }
                }
            }
        }
    }


    /**
     * commit  [提交事务]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function commit()
    {
        if ($this->isStartTransaction) {
            $this->write();
            if ($this->fileStream->unlock()) {
                $this->data = null;
                $this->isStartTransaction = false;
                $this->fileStream->close();
                unset($this->fileStream);
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    /**
     * rollback  [description]
     * @param bool $autoCommit
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function rollback($autoCommit = false)
    {
        if ($this->isStartTransaction) {
            $this->read();
            if ($autoCommit) {
                $this->commit();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * set  [description]
     * @param $key
     * @param $val
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function set($key, $val)
    {
        if ($this->isStartTransaction) {

            $this->data->set($key, $val);
            return true;
        } else {
            if ($this->startTransaction()) {
                $this->data->set($key, $val);
                return $this->commit();
            } else {
                return false;
            }
        }
    }

    /**
     * del  [description]
     * @param $key
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function del($key)
    {
        return $this->set($key, null);
    }


    /**
     * get  [description]
     * @param $key
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function get($key)
    {
        if ($this->isStartTransaction) {
            return $this->data->get($key);
        } else {
            if ($this->startTransaction()) {
                $data = $this->data->get($key);
                $this->commit();
                return $data;
            } else {
                return false;
            }
        }
    }


    /**
     * clear  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function clear()
    {
        if ($this->isStartTransaction) {
            $this->data = new SplArray();
            return true;
        } else {
            if ($this->startTransaction()) {
                $this->data = new SplArray();
                return $this->commit();
            } else {
                return false;
            }
        }
    }


    /**
     * all  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return null
     */
    public function all()
    {
        if ($this->isStartTransaction) {
            return $this->data->getArrayCopy();
        } else {
            if ($this->startTransaction()) {
                $data = $this->data->getArrayCopy();
                $this->commit();
                return $data;
            } else {
                return null;
            }
        }
    }

    /**
     * read  [读数据]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    private function read()
    {
        if ($this->isStartTransaction) {
            $data = $this->fileStream->getContents();
            if ($this->serializeType == self::SERIALIZE_TYPE_JSON) {
                $data = json_decode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $this->data = is_array($data) ? new SplArray($data) : new SplArray();
            } else {
                $data = unserialize($data);//对单一的已序列化的变量进行操作，将其转换回 PHP 的值。
                $this->data = is_a($data, SplArray::class) ? $data : new SplArray();
            }
            return true;
        } else {
            return false;
        }
    }


    /**
     * write  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    private function write()
    {
        if ($this->isStartTransaction) {
            $this->fileStream->truncate();
            $this->fileStream->rewind();//倒回文件指针的位置
            if ($this->serializeType == self::SERIALIZE_TYPE_JSON) {
                $data = json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } else {
                $data = serialize($this->data);
            }
            $this->fileStream->write($data);
            return true;
        } else {
            return false;
        }
    }


}