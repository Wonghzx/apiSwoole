<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2/002
 * Time: 15:44
 */

namespace Core\Swoole\Process;

use Swoole\Table as swTable;

/**
 * Class MainProcess
 * @package Core\Swoole\Process
 */
class MainProcess
{
    /**
     * @var int
     */
    public $mpid = 0;

    /**
     * @var array
     */
    public $works = [];

    /**
     * @var int
     */
    public $max_process = 5;

    /**
     * @var null
     */
    public static $table = NULL;


    public function __construct()
    {

    }


}