<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/24/024
 * Time: 18:08
 */

namespace Core\Component;
class UploadFile extends \SplFileObject
{

    /**
     * @var self 单例
     */
    private static $instance;

    /**
     * @var string 错误信息
     */
    private $error = '';

    /**
     * @var string 当前完整文件名
     */
    private $fileName;

    /**
     * @var string 上传文件名
     */
    private $saveName;

    /**
     * @var string 文件上传命名规则
     */
    private $rule = 'date';

    /**
     * @var array 文件上传验证规则
     */
    private $validate = [];

    /**
     * @var bool 单元测试
     */
    private $isTest;

    /**
     * @var array 文件 hash 信息
     */
    private $hash = [];

    static public function getInstance($filename)
    {
        if (!isset(self::$instance))
            self::$instance = new static($filename, $mode = 'r');

        return self::$instance;
    }

    public function __construct($fileName, $openMode = 'r')
    {
        parent::__construct($fileName, $openMode);
        $this->fileName = $this->getRealPath() ?: $this->getPathname();
    }
}