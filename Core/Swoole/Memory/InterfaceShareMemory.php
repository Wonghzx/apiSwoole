<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25/025
 * Time: 12:02
 */

namespace Core\Swoole\Memory;
use Swoole\Table;
interface InterfaceShareMemory
{
    /**
     * 一个单位长度的int类型
     */
    const ONE_INT_LENGTH = 1;

    /**
     * 两个单位长度的int类型
     */
    const TWO_INT_LENGTH = 2;

    /**
     * 四个单位长度的int类型
     */
    const FOUR_INT_LENGTH = 4;

    /**
     * 八个单位长度的int类型
     */
    const EIGHT_INT_LENGTH = 8;


    /**
     * int类型
     */
    const TYPE_INT = Table::TYPE_INT;

    /**
     * string类型
     */
    const TYPE_STRING = Table::TYPE_STRING;

    /**
     * float类型
     */
    const TYPE_FLOAT = Table::TYPE_FLOAT;


    /**
     * column  [description]
     * @param string $name 列名
     * @param int $type 类型
     * @param int $size 最大长度，单位为字节
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function column(string $name, int $type, int $size = 0);


    /**
     * create  [创建内存表]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function create();


    /**
     * set  [设置行数据]
     * @param string $key 索引键
     * @param array $data 数据
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function set(string $key, array $data);


    /**
     * incr  [原子自增操作]
     * @param string $key 索引键
     * @param string $column 列名
     * @param int $incrby 增量。如果列为整形，$incrby必须为int型，如果列为浮点型，$incrby必须为float类型
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function incr(string $key, string $column, $incrby = 1);


    /**
     * decr  [原子自减操作]
     * @param string $key 索引键
     * @param string $column 列名
     * @param int $incrby 增量。如果列为整形，$incrby必须为int型，如果列为浮点型，$incrby必须为float类型
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function decr(string $key, string $column, $incrby = 1);


    /**
     * get  [获取一行数据]
     * @param string $key 索引键
     * @param null $field 列名
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function get(string $key, $field = null);


    /**
     * exist  [检查table中是否存在某一个key]
     * @param string $key 索引键
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function exist(string $key);


    /**
     * del  [删除数据]
     * @param string $key 索引键
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function del(string $key);
}