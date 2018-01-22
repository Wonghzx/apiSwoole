<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22/022
 * Time: 15:41
 */

namespace Core\Console\Input;

use Core\Console\CommandParser;

class Input implements InterfaceInput
{

    private static $_instance;

    /**
     * 资源句柄
     * @var resource
     */
    protected $handle = STDIN;

    /**
     * 当前目录
     * @var CWD
     */
    private $cwd;


    /**
     * @var string $fullCommand
     */
    private $fullCommand;

    /**
     * @var $command
     */
    private $command;


    /**
     * 输入参数集合
     *
     * @var array
     */
    private $args = [];


    /**
     * 短参数
     *
     * @var array
     */
    private $sOpts = [];


    /**
     * 长参数
     *
     * @var array
     */
    private $lOpts = [];


    /**
     * 执行的命令
     * @var mixed|null
     */
    private $comm;


    static public function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }

    /**
     * Input constructor.
     */
    public function __construct()
    {
        global $argv;

        $this->cwd = $this->getCwd();

        $this->fullCommand = implode(' ', $argv);
        $this->command = array_shift($argv);

        list($this->args, $this->sOpts, $this->lOpts) = CommandParser::parse($argv);
        $this->comm = isset($this->args[0]) ? array_shift($this->args) : null;

    }


    private function getCwd(): string
    {
        if (!$this->cwd) {
            $this->cwd = getcwd();
        }
        return $this->cwd;
    }

    /**
     * read  [读取输入信息]
     * @param null $question 若不为空，则先输出文本消息
     * @param bool $nl true 会添加换行符 false 原样输出，不添加换行符
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function read($question = null, $nl = false): string
    {
        // TODO: Implement read() method.
        fwrite($this->handle, $question . ($nl ? "\n" : ''));
        return trim(fgets($this->handle));
    }

    /**
     * getScript  [获取执行的脚本]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function getScript(): string
    {
        // TODO: Implement getScript() method.
        return $this->command;
    }

    /**
     * getCommand  [获取执行的命令]
     * @param string $default
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function getCommand($default = ''): string
    {
        // TODO: Implement getCommand() method.
        return $this->comm ?: $default;
    }

    /**
     * getArgs  [获取命令参数]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    public function getArgs(): array
    {
        // TODO: Implement getArgs() method.
        return $this->args;
    }

    /**
     * getArg  [获取输入某个参数]
     * @param $name
     * @param null $default
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function getArg($name, $default = null)
    {
        // TODO: Implement getArg() method.
        return $this->get($name, $default);
    }

    /**
     * getOpts  [获取输入选项集合]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    public function getOpts(): array
    {
        // TODO: Implement getOpts() method.
        return array_merge($this->sOpts, $this->lOpts);
    }

    /**
     * getOpt  [获取某一个选项参数]
     * @param string $name
     * @param null $default
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function getOpt(string $name, $default = null)
    {
        // TODO: Implement getOpt() method.
        if (isset($name{1})) {
            return $this->getLongOpt($name, $default);
        }

        return $this->getShortOpt($name, $default);
    }


    /**
     * 获取某个长选项
     *
     * @param string $name 名称
     * @param null $default 默认值
     *
     * @return mixed|null
     */
    public function getLongOpt(string $name, $default = null)
    {
        return $this->lOpts[$name] ?? $default;
    }


    /**
     * 是否存在某个长选项
     *
     * @param string $name 名称
     *
     * @return bool
     */
    public function hasLOpt(string $name): bool
    {
        return isset($this->lOpts[$name]);
    }


    /**
     * 所有长选项
     *
     * @return array
     */
    public function getLongOpts(): array
    {
        return $this->lOpts;
    }


    /**
     * 所有长选项
     *
     * @return array
     */
    public function getLOpts(): array
    {
        return $this->lOpts;
    }


    /**
     * 全脚本
     *
     * @return string
     */
    public function getFullScript(): string
    {
        return $this->fullCommand;
    }

    /**
     * 获取某个参数值
     *
     * @param string $name 名称
     * @param null $default 默认值
     *
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        return $this->args[$name] ?? $default;
    }
}