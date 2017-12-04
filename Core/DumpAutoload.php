<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4/004
 * Time: 11:21
 */

namespace Core;

class DumpAutoload
{
    /**
     * 对应每个命名空间的路径前缀配置
     * @var array
     */
    private static $classMap = [];

    private static $instance;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DumpAutoload();
        }
        return self::$instance;
    }

    private function loadClass($class)
    {
        //$class = Http\index

        if (isset(self::$classMap[$class])) {
            return true;
        } else {
            $prefix = $class;
            while (false !== $pos = strrpos($prefix, '\\')) {
                $prefix = substr($class, 0, $pos + 1); //类 目录名 & 命名空间名
                $relative_class = substr($class, $pos + 1); //类名称
                $mapped_file = $this->loadMappedFile($prefix, $relative_class);
                if ($mapped_file) {
                    return $mapped_file;
                }
                $prefix = rtrim($prefix, '\\');
            }
            return false;
        }

    }

    public function __construct()
    {
        $this->register();
    }


    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }


    /**
     * addNamespace  [description]
     * @param $prefix 名称空间前缀
     * @param $base_dir  对应的基础路径
     * @param int $memorySecure 存储安全
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function addNamespace($prefix = '', $base_dir = '', $memorySecure = 1)
    {
        $prefix = trim($prefix, '\\') . '\\';  // 转化成 Http\
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/'; // 转化成 Http/

        if (isset($this->classMap[$prefix]) === false) {
            self::$classMap[$prefix] = [];
        }

        if ($memorySecure == 1) {
            //加载前 执行清空
            self::$classMap[$prefix] = [];
        }
        array_push(self::$classMap[$prefix], $base_dir);

        return $this;
    }

    /**
     * loadMappedFile  [负载映射文件]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private static function loadMappedFile($prefix, $relative_class)
    {
        if (isset(self::$classMap[$prefix]) === false) {
            return false;
        }
        foreach (self::$classMap[$prefix] as $base_dir) {
            $file = $base_dir
                . str_replace('\\', '/', $relative_class)
                . '.php';
            if (self::requireFile($file)) {
                return $file;
            }
        }
        return false;
    }


    private static function requireFile($file)
    {
        /*
         * 若不加ROOT，会导致在daemonize模式下
         * 类文件引入目录错误导致类无法正常加载
         */
        $file = ROOT . '/' . $file;
        if (file_exists($file)) {
            require_once($file);
            return true;
        }
        return false;
    }


}