<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25/025
 * Time: 17:03
 */

namespace Core\Swoole\Helpers;

class PipeMessage
{
    /**
     * 任务消息
     */
    const TYPE_TASK = 'task';


    /**
     * pack  [pipe消息格式化]
     * @param string $type 类型
     * @param array $data 数据
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static public function pack(string $type, array $data)
    {
        $data = [
            'pipeType' => $type,
            'message' => $data
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * unpack  [pipe消息解析]
     * @param string $message
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return array
     */
    static public function unpack(string $message)
    {
        $messageAry = json_decode($message, true);
        $type = $messageAry['pipeType'];
        $data = $messageAry['message'];

        return [$type, $data];
    }

}