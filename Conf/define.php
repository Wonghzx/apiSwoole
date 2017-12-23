<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22/022
 * Time: 14:11
 */

use Core\Core;

// Constants
!defined('DS') && define('DS', DIRECTORY_SEPARATOR);

// 系统名称
!defined('APP_NAME') && define('APP_NAME', 'ApiSwoole');

// 基础根目录
!defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

// cli命名空间
!defined('COMMAND_NS') && define('COMMAND_NS', "Core\Commands");


//注册别名
$aliases = [
    '@Root' => BASE_PATH,
    '@Core' => '@Root/Core',
    '@Runtime' => '@Root/Runtime',
    '@Conf' => '@Root/Conf',
    '@Commands' => '@Core/Commands'
];
Core::setAliases($aliases);