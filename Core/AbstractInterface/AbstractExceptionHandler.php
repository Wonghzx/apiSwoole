<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 15:32
 */

namespace Core\AbstractInterface;
abstract class AbstractExceptionHandler
{
    /**
     * handler  [description]
     * @param \Exception $exception
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function handler(\Exception $exception);

    /**
     * display  [description]
     * @param \Exception $exception
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function display(\Exception $exception);

    /**
     * log  [description]
     * @param \Exception $exception
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    abstract public function log(\Exception $exception);
}