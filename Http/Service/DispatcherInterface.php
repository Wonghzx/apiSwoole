<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/15/015
 * Time: 14:50
 */

namespace Http\Service;

/**
 * Interface DispatcherInterface
 * @package Http\Service
 */
interface DispatcherInterface
{
    /**
     * doDispatcher  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doDispatcher(...$params);


    /**
     * onConnect  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doConnect(...$params);


    /**
     * doClose  [description]
     * @param array ...$params
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function doClose(...$params);


}