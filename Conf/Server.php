<?php

return [
    'server' => [
        'pname'         => env('PNAME', 'php-Apiswoole'),
        'tcpable'       => env('TCPABLE', true),
        'cronable'      => env('CRONABLE', false),
        'autoReload'    => env('AUTO_RELOAD', true),
    ],
    'tcp' => [
        'host'                  => env('TCP_HOST', '0.0.0.0'),
        'port'                  => env('TCP_PORT', 8099),
        'model'                 => env('TCP_MODEL', SWOOLE_PROCESS),
        'type'                  => env('TCP_TYPE', SWOOLE_SOCK_TCP),
        'package_max_length'    => env('TCP_PACKAGE_MAX_LENGTH', 2048),
        'open_eof_check'        => env('TCP_OPEN_EOF_CHECK', false),
    ],
    'http' => [
        'host'       => env('HTTP_HOST', '0.0.0.0'),
        'port'       => env('HTTP_PORT', 9501),
        'model'      => env('HTTP_MODEL', SWOOLE_PROCESS),
        'type'       => env('HTTP_TYPE', SWOOLE_SOCK_TCP),
    ],
    'crontab' => [ //定时任务
        'task_count' => env('CRONTAB_TASK_COUNT', 1024),
        'task_queue' => env('CRONTAB_TASK_QUEUE', 2048),
    ],
    'setting' => [
        'worker_num'        => env('WORKER_NUM', 8),
        'max_request'       => env('MAX_REQUEST', 5000),
        'daemonize'         => env('DAEMONIZE', 0),
        'dispatch_mode'     => env('DISPATCH_MODE', 2),
        'log_file'          => ROOT . env('LOG_FILE', '/Runtime/Logs/swoole.log'),
        'pid_file'             => ROOT . env('PFILE', '/Runtime/Logs/apiswoole.pid'),
        'task_max_request'  => env('TASK_MAX_REQUEST', 10),
        'task_worker_num'   => env('TASK_WORKER_NUM', 8),
        'upload_tmp_dir'    => ROOT . env('UPLOAD_TMP_DIR', '/Runtime/UploadFiles'),
        'server_name'    => env('SERVER_NAME', ''),
        'server_type'    => env('SERVER_TYPE', 'SERVER_TYPE_WEB'),
    ],
];