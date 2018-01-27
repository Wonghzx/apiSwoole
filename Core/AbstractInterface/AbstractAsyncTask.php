<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/10/010
 * Time: 15:56
 */

namespace Core\AbstractInterface;

use Core\Event;

abstract class AbstractAsyncTask
{
    /**
     * @var 完成回调的数据
     */
    private $dataForFinishCallBack;

    /**
     * @var 执行任务数据
     */
    private $dataForTask;

    function __construct($dataForTask = null)
    {
        $this->dataForTask = $dataForTask;
    }


    public function getDataForFinishCallBack()
    {
        return $this->dataForFinishCallBack;
    }

    /**
     * handler  [description]
     *server为task进程的server   但taskId为分配该任务的主worker分配的taskId 为每个主worker进程内独立自增
     */
    abstract function handler(\swoole_server $server, $taskId, $fromId);

    /**
     * finishCallBack  [description]
     * server为主worker进程的server   但taskId为分配该任务的主worker分配的taskId 为每个主worker进程内独立自增
     */
    abstract function finishCallBack(\swoole_server $server, $task_id, $resultData);

    protected function finish($dataForFinishCallBack = null)
    {
        if ($dataForFinishCallBack !== null) {
            $this->dataForFinishCallBack = $dataForFinishCallBack;
        }

        //避免handler中有释放资源类型被序列化出错
        Event::getInstance()->onGetServer()->finish($this);
    }
}