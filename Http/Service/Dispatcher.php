<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15/015
 * Time: 14:54
 */

namespace Http\Service;

use Core\Component\Error\Trigger;

class Dispatcher implements DispatcherInterface
{


    /**
     * doDispatcher  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doDispatcher(...$params)
    {
        // TODO: Implement doDispatcher() method.

        list($server, $fd, $fromId, $data) = $params;

        try {

//            DataStream::getInstance($data);
        } catch (\Exception $exception) {

            Trigger::exception($exception);

        } finally {
            $client = new \swoole_redis();
            print_r($client);
            $server->send($fd, $data);
        }
    }

    /**
     * onConnect  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doConnect(...$params)
    {
        // TODO: Implement doConnect() method.
        list($server, $fd, $reactorId) = $params;
    }

    /**
     * doClose  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doClose(...$params)
    {
        // TODO: Implement doClose() method.
        list($server, $fd, $reactorId) = $params;
    }

}

