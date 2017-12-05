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
}