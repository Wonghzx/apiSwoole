<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22/022
 * Time: 15:41
 */

namespace Core\Console\Input;

/**
 * Interface InterfaceInput 输入接口
 * @package Core\Console
 */
interface InterfaceInput
{
    /**
     * read  [读取输入信息]
     * @param null $question 若不为空，则先输出文本消息
     * @param bool $nl true 会添加换行符 false 原样输出，不添加换行符
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function read($question = null, $nl = false): string;


    /**
     * getScript  [获取执行的脚本]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function getScript(): string;


    /**
     * getCommand  [获取执行的命令]
     * @param string $default
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function getCommand($default = ''): string;


    /**
     * getArgs  [获取命令参数]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    public function getArgs(): array;


    /**
     * getArg  [获取输入某个参数]
     * @param $name
     * @param null $default
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function getArg($name, $default = null);


    /**
     * getOpts  [获取输入选项集合]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    public function getOpts(): array;


    /**
     * getOpt  [获取某一个选项参数]
     * @param string $name
     * @param null $default
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function getOpt(string $name, $default = null);

}