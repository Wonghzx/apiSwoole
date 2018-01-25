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
     * @var array 上传文件信息
     */
    private $info;

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

    /**
     * UploadFile constructor.
     * @param $fileName
     * @param string $openMode
     */
    public function __construct($fileName, $openMode = 'r')
    {
        parent::__construct($fileName, $openMode);
        $this->fileName = $this->getRealPath() ?: $this->getPathname();
    }


    /**
     * move  [description]
     * @param string $path 保存路径
     * @param bool $saveName 保存的文件名 默认自动生成
     * @param bool $replace 同名文件是否覆盖
     */
    public function move($path, $saveName = true, $replace = true)
    {
        if (!empty($this->info['error'])) {
            $this->error($this->info['error']);
            return false;
        }

        // 检测合法性
        if (!$this->isValid()) {
            $this->error = 'upload illegal files';
            return false;
        }

        // 验证上传
        if (!$this->check()) {
            return false;
        }

        $path = rtrim($path, '/') . '/';

        // 文件保存命名规则
        $saveName = $this->buildSaveName($saveName);
        $filename = $path . $saveName;

        // 检测目录
        if (false === $this->checkPath(dirname($filename))) {
            return false;
        }

        // 不覆盖同名文件
        if (!$replace && is_file($filename)) {
            $this->error = ['has the same filename: {:filename}', ['filename' => $filename]];
            return false;
        }

        /* 移动文件 */
        if ($this->isTest) {
            rename($this->fileName, $filename);
        } elseif (!move_uploaded_file($this->fileName, $filename)) {
            $this->error = 'upload write error';
            return false;
        }

        // 返回 File 对象实例
//        $file = new self($filename);
//        $file->setSaveName($saveName)->setUploadInfo($this->info);
        return ['filename' => $filename, 'saveName' => $saveName, 'msg' => $this->getError()];
    }

    /**
     * 设置上传文件的保存文件名
     * @access public
     * @param  string $saveName 保存名称
     * @return $this
     */
    public function setSaveName($saveName)
    {
        $this->saveName = $saveName;

        return $this;
    }

    /**
     * setUploadInfo  [description]
     * @param array $info 上传文件信息
     */
    public function setUploadInfo(array $info)
    {
        $this->info = $info;

        return $this;
    }


    /**
     * 设置文件的命名规则
     * @access public
     * @param  string $rule 文件命名规则
     * @return $this
     */
    public function rule($rule)
    {
        $this->rule = $rule;

        return $this;
    }


    public function getError()
    {
        if (is_array($this->error)) {
            list($msg, $vars) = $this->error;
        } else {
            $msg = $this->error;
            $vars = [];
        }

        if (!empty($msg) || !$vars) {
            Logger::getInstance('error')->log($msg);
            return 'success';
        } else {
            return 'error';
        }
//        return Lang::has($msg) ? Lang::get($msg, $vars) : $msg;
    }

    private function checkPath($path)
    {
        if (is_dir($path) || mkdir($path, 0755, true)) {
            return true;
        }
        $this->error = ['directory {:path} creation failed', ['path' => $path]];

        return false;
    }

    /**
     * buildSaveName  [description]
     * @param $saveName
     * @return array|mixed|string
     */
    private function buildSaveName($saveName)
    {
        // 自动生成文件名
        if (true === $saveName) {
            if ($this->rule instanceof \Closure) {
                $saveName = call_user_func_array($this->rule, [$this]);
            } else {
                switch ($this->rule) {
                    case 'date':
                        $saveName = date('Ymd') . '/' . md5(microtime(true));
                        break;
                    default: {
                        if (in_array($this->rule, hash_algos())) {
                            $hash = $this->hash($this->rule);
                            $saveName = substr($hash, 0, 2) . '/' . substr($hash, 2);
                        } elseif (is_callable($this->rule)) {
                            $saveName = call_user_func($this->rule);
                        } else {
                            $saveName = date('Ymd') . '/' . md5(microtime(true));
                        }
                    }
                }
            }
        } elseif ('' === $saveName || false === $saveName) {
            $saveName = $this->getInfo('name');
        }
        if (!strpos($saveName, '.')) {
            $saveName .= '.' . pathinfo($this->getInfo('name'), PATHINFO_EXTENSION);
        }
        return $saveName;
    }


    /**
     * hash  [description]
     * @param string $type
     * @return mixed
     */
    private function hash($type = 'sha1')
    {
        if (!isset($this->hash[$type])) {
            $this->hash[$type] = hash_file($type, $this->fileName);
        }
        return $this->hash[$type];
    }

    /**
     * check  [检测上传文件]
     * @param array $rule 验证规则
     */
    private function check($rule = []): bool
    {
        $rule = $rule ?: $this->validate;

        /* 检查文件大小 */
        if (isset($rule['size']) && !$this->checkSize($rule['size'])) {
            $this->error = 'filesize not match';
            return false;
        }

        /* 检查文件 MIME 类型 */
        if (isset($rule['type']) && !$this->checkMime($rule['type'])) {
            $this->error = 'mimetype to upload is not allowed';
            return false;
        }


        /* 检查图像文件 */
        if (!$this->checkImg()) {
            $this->error = 'illegal image files';
            return false;
        }

        return true;
    }


    /**
     * checkImg  [description]
     * @return bool
     */
    private function checkImg(): bool
    {
        $extension = strtolower(pathinfo($this->getInfo('name'), PATHINFO_EXTENSION));

        return !in_array($extension, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf']) ||
            in_array($this->getImageType($this->fileName), [1, 2, 3, 4, 6, 13]);
    }


    /**
     * getImageType  [description]
     * @param $image
     * @return bool|int
     */
    private function getImageType($image): bool
    {
        if (function_exists('exif_imagetype')) {
            return exif_imagetype($image);
        }

        try {
            $info = getimagesize($image);
            return $info ? $info[2] : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * getInfo  [description]
     * @param $name
     * @return array|mixed
     */
    private function getInfo($name)
    {
        return isset($this->info[$name]) ? $this->info[$name] : $this->info;
    }

    /**
     * checkSize  [description]
     * @param $size 最大大小
     * @return bool
     */
    private function checkSize($size): bool
    {
        return $this->getSize() <= $size;
    }


    /**
     * checkMime  [检测上传文件类型]
     * @param $mime
     * @return bool
     */
    private function checkMime($mime): bool
    {
        $mime = is_string($mime) ? explode(',', $mime) : $mime;

        return in_array(strtolower($this->getMime()), $mime);
    }

    /**
     * getMime  [获取文件类型信息]
     * @return mixed
     */
    private function getMime()
    {
        $fInfo = finfo_open(FILEINFO_MIME_TYPE);

        return finfo_file($fInfo, $this->fileName);
    }

    /**
     * isValid  [检测是否合法的上传文件]
     */
    private function isValid(): bool
    {
        return $this->isTest ? is_file($this->fileName) : is_uploaded_file($this->fileName);
    }


    /**
     * error  [获取错误代码信息]
     * @param int $errorNo 错误码
     */
    private function error(int $errorNo)
    {
        switch ($errorNo) {
            case 0:
            case 1:
            case 2:
                $this->error = 'upload File size exceeds the maximum value';
                break;
            case 3:
                $this->error = 'only the portion of file is uploaded';
                break;
            case 4:
                $this->error = 'no file to uploaded';
                break;
            case 6:
                $this->error = 'upload temp dir not found';
                break;
            case 7:
                $this->error = 'file write error';
                break;
            default:
                $this->error = 'unknown upload error';
        }
        return $this;
    }

    /**
     * 魔法方法，获取文件的 hash 值
     * @access public
     * @param  string $method 方法名
     * @param  mixed $args 调用参数
     * @return string
     */
    public function __call($method, $args)
    {
        return $this->hash($method);
    }

}