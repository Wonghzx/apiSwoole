<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5/005
 * Time: 11:45
 */

namespace Core;

use Conf\ConstantClass;

class Event extends \Core\AbstractInterface\AbstractEvent
{

    /**
     * initialize  [初始化框架前]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        ConstantClass::getInstance();
    }

    /**
     * initializeEd  [初始化框架后]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function initializeEd()
    {
        // TODO: Implement initializeEd() method.
    }


    /**
     * addListener
     * [
     * Swoole提供了swoole_server::addListener来增加监听的端口。
     * 业务代码中可以通过调用swoole_server::connection_info来获取某个连接来自于哪个端口。
     * ]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function addListener()
    {
        // TODO: Implement addListener() method.
    }

    /**
     * addProcess  [添加一个用户自定义的工作进程。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function addProcess()
    {
        // TODO: Implement addProcess() method.
    }

    /**
     * onListen  [监听一个新的Server端口，此方法是addlistener的别名。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onListen()
    {
        // TODO: Implement onListen() method.
    }

    /**
     * onStart  [启动server，监听所有TCP/UDP端口，函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onStart()
    {
        // TODO: Implement onStart() method.
    }

    /**
     * onReload  [重启所有worker进程。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onReload()
    {
        // TODO: Implement onReload() method.
    }

    /**
     * onStop  [使当前worker进程停止运行，并立即触发onWorkerStop回调函数。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onStop()
    {
        // TODO: Implement onStop() method.
    }

    /**
     * onShutdown  [关闭服务器]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onShutdown()
    {
        // TODO: Implement onShutdown() method.
    }

    /**
     * onTick  [tick定时器，可以自定义回调函数。此函数是swoole_timer_tick的别名]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onTick()
    {
        // TODO: Implement onTick() method.
    }

    /**
     * onAfter  [函数是一个一次性定时器，执行完成后就会销毁]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onAfter()
    {
        // TODO: Implement onAfter() method.
    }

    /**
     * onDefer  [
     * 延后执行一个PHP函数。Swoole底层会在EventLoop循环完成后执行此函数。此函数的目的是为了让一些PHP代码延后执行，
     * 程序优先处理IO事件。底层不保证defer的函数会立即执行，如果是系统关键逻辑，需要尽快执行，请使用after定时器实现。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onDefer()
    {
        // TODO: Implement onDefer() method.
    }

    /**
     * onClearTimer  [清除tick/after定时器，此函数是swoole_timer_clear的别名。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onClearTimer()
    {
        // TODO: Implement onClearTimer() method.
    }

    /**
     * onClose  [关闭客户端连接，函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onClose()
    {
        // TODO: Implement onClose() method.
    }

    /**
     * onSend  [向客户端发送数据，函数原型：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onSend()
    {
        // TODO: Implement onSend() method.
    }

    /**
     * onSendFile  [发送文件到TCP客户端连接。使用示例：]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onSendFile()
    {
        // TODO: Implement onSendFile() method.
    }

    /**
     * onSendto  [向任意的客户端IP:PORT发送UDP数据包。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onSendTo()
    {
        // TODO: Implement onSendTo() method.
    }

    /**
     * onSendWait  [阻塞地向客户端发送数据。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onSendWait()
    {
        // TODO: Implement onSendWait() method.
    }

    /**
     * onSendMessage  [此函数可以向任意worker进程或者task进程发送消息。在非主进程和管理进程中可调用。收到消息的进程会触发onPipeMessage事件]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onSendMessage()
    {
        // TODO: Implement onSendMessage() method.
    }

    /**
     * onExist  [检测fd对应的连接是否存在。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onExist()
    {
        // TODO: Implement onExist() method.
    }

    /**
     * onPause  [停止接收数据。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onPause()
    {
        // TODO: Implement onPause() method.
    }

    /**
     * onResume  [恢复数据接收。与pause方法成对使用]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onResume()
    {
        // TODO: Implement onResume() method.
    }

    /**
     * onConnectionInfo  [swoole_server->connection_info函数用来获取连接的信息，别名是swoole_server->getClientInfo]
     * @copyright Copyright (c) connection_info
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onConnectionInfo()
    {
        // TODO: Implement onConnectionInfo() method.
    }

    /**
     * onConnectionList  [
     * 用来遍历当前Server所有的客户端连接，connection_list方法是基于共享内存的，不存在IOWait，
     * 遍历的速度很快。另外connection_list会返回所有TCP连接，而不仅仅是当前worker进程的TCP连接。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onConnectionList()
    {
        // TODO: Implement onConnectionList() method.
    }

    /**
     * onBind  [将连接绑定一个用户定义的UID，可以设置dispatch_mode=5设置以此值进行hash固定分配。可以保证某一个UID的连接全部会分配到同一个Worker进程]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onBind()
    {
        // TODO: Implement onBind() method.
    }

    /**
     * onStats  [得到当前Server的活动TCP连接数，启动时间，accpet/close的总次数等信息]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onStats()
    {
        // TODO: Implement onStats() method.
    }

    /**
     * onTask  [
     * 投递一个异步任务到task_worker池中。此函数是非阻塞的，执行完毕会立即返回。Worker进程可以继续处理新的请求。
     * 使用Task功能，必须先设置 task_worker_num，并且必须设置Server的onTask和onFinish事件回调函数]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onTask()
    {
        // TODO: Implement onTask() method.
    }

    /**
     * onTaskWait  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onTaskWait()
    {
        // TODO: Implement onTaskWait() method.
    }

    /**
     * onTaskWaitMulti  [并发执行多个Task]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onTaskWaitMulti()
    {
        // TODO: Implement onTaskWaitMulti() method.
    }

    /**
     * onTaskCo  [并发执行Task并进行协程调度。仅用于2.0版本。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onTaskCo()
    {
        // TODO: Implement onTaskCo() method.
    }

    /**
     * onFinish  [此函数用于在task进程中通知worker进程，投递的任务已完成。此函数可以传递结果数据给worker进程。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onFinish()
    {
        // TODO: Implement onFinish() method.
    }

    /**
     * onHeartbeat  [检测服务器所有连接，并找出已经超过约定时间的连接。
     * 如果指定if_close_connection，则自动关闭超时的连接。未指定仅返回连接的fd数组。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onHeartbeat()
    {
        // TODO: Implement onHeartbeat() method.
    }

    /**
     * getLastError  [获取最近一次操作错误的错误码。业务代码中可以根据错误码类型执行不同的逻辑。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function getLastError()
    {
        // TODO: Implement getLastError() method.
    }

    /**
     * getSocket  [调用此方法可以得到底层的socket句柄，返回的对象为sockets资源句柄。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function getSocket()
    {
        // TODO: Implement getSocket() method.
    }

    /**
     * protect  [设置客户端连接为保护状态，不被心跳线程切断。]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onProtect()
    {
        // TODO: Implement onProtect() method.
    }

    /**
     * onConfirm  [
     * 确认连接，与enable_delay_receive或wait_for_bind配合使用。
     * 当客户端建立连接后，并不监听可读事件。仅触发onConnect事件回调，
     * 在onConnect回调中执行confirm确认连接，这时服务器才会监听可读事件，接收来自客户端连接的数据。
     * ]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    function onConfirm()
    {
        // TODO: Implement onConfirm() method.
    }
}