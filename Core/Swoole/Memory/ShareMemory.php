<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21/021
 * Time: 13:44
 */

namespace Core\Swoole\Memory;

use Core\Component\Spl\SplArray;

use Swoole\Table as swTable;

/**
 * Class ShareMemory 共享内存
 * @package Core\Component
 */
class ShareMemory implements InterfaceShareMemory
{


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
     * @var Table $table 内存表实例
     */
    private $table = null;


    /**
     * @var string $name 内存表名
     */
    private $name = '';


    /**
     * @var int $size table大小
     */
    private $size = 0;


    /**
     * @var array $column 列数组
     * [
     *  'field' => ['type', length]
     * ]
     */
    private $columns = [];


    public function __construct(string $name = '', int $size = 0, array $columns = [])
    {
        $this->setName($name);
        $this->setTable(new swTable($size));
        $this->setSize($size);
        $this->setColumns($columns);
    }

    /**
     * setName  [设置内存表名]
     * @param string $name 内存表名
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return Table
     */
    public function setName(string $name): ShareMemory
    {
        $this->name = $name;

        return $this;
    }


    /**
     * setTable  [设置内存表实例]
     * @param swTable $table 内存表实例
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return ShareMemory
     */
    public function setTable(swTable $table): ShareMemory
    {
        $this->table = $table;

        return $this;
    }

    /**
     * getTable  [获取内存表实例]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * getName  [返回内存表名]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * setSize  [设置内存表大小]
     * @param int $size 内存表大小
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return ShareMemory
     */
    public function setSize(int $size): ShareMemory
    {
        $this->size = $size;

        return $this;
    }


    /**
     * getSize  [获取内存表大小]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }


    /**
     * setColumns  [设置内存表字段结构]
     * @param array $columns
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return Table
     */
    public function setColumns(array $columns): ShareMemory
    {
        $this->columns = $columns;

        return $this;
    }


    /**
     * getColumns  [内存表增加一列]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * column  [description]
     * @param string $name 列名
     * @param int $type 类型
     * @param int $size 最大长度，单位为字节
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function column(string $name, int $type, int $size = 0)
    {
        // TODO: Implement column() method.
        switch ($type) {
            case self::TYPE_INT:
                if (!in_array($size, [self::ONE_INT_LENGTH, self::TWO_INT_LENGTH, self::FOUR_INT_LENGTH, self::EIGHT_INT_LENGTH])) {
                    $size = 4;
                }
                break;
            case self::TYPE_STRING:
                if ($size < 0) {
                    throw new \RuntimeException('Size not be allow::' . $size);
                }
                break;
            case self::TYPE_FLOAT:
                $size = 8;
                break;
            default:
                throw new \RuntimeException('Undefind Column-Type::' . $type);
        }
        $this->table->column($name, $type, $size);
    }

    /**
     * create  [创建内存表]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function create()
    {
        // TODO: Implement create() method.
        foreach ($this->columns as $field => $fieldValue) {
            $args = array_merge([$field], $fieldValue);
            $this->column(...$args);
        }

        return $this->table->create();
    }

    /**
     * incr  [原子自增操作]
     * @param string $key 索引键
     * @param string $column 列名
     * @param int $incrby 增量。如果列为整形，$incrby必须为int型，如果列为浮点型，$incrby必须为float类型
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function incr(string $key, string $column, $incrby = 1)
    {
        // TODO: Implement incr() method.
        return $this->table->incr($key, $column, $incrby);
    }

    /**
     * decr  [原子自减操作]
     * @param string $key 索引键
     * @param string $column 列名
     * @param int $incrby 增量。如果列为整形，$incrby必须为int型，如果列为浮点型，$incrby必须为float类型
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function decr(string $key, string $column, $incrby = 1)
    {
        // TODO: Implement decr() method.
        return $this->table->decr($key, $column, $incrby);
    }

    /**
     * exist  [检查table中是否存在某一个key]
     * @param string $key 索引键
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function exist(string $key)
    {
        // TODO: Implement exist() method.
        return $this->table->exist($key);
    }

    /**
     * set  [设置行数据]
     * @param string $key 索引键
     * @param array $data 数据
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function set(string $key, array $data)
    {
        // TODO: Implement set() method.
        return $this->table->set($key, $data);
    }

    /**
     * get  [获取一行数据]
     * @param string $key 索引键
     * @param null $field 列名
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function get(string $key, $field = null)
    {
        // TODO: Implement get() method.
        return $this->table->get($key, $field);
    }

    /**
     * del  [删除数据]
     * @param string $key 索引键
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function del(string $key)
    {
        // TODO: Implement del() method.
        return $this->table->del($key);
    }


    /**
     * __call  [description]
     * @param string $method 方法名字
     * @param array $args 参数
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function __call(string $method, array $args = [])
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$args);
        }
        throw new \RuntimeException('Call a not exists method.');
    }


    /**
     * __get  [description]
     * @param string $name 属性名
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);
        if (!method_exists($this, $method)) {

            throw new \RuntimeException('Call undefind property::' . $name);
        }

        return $this->$method();
    }
}