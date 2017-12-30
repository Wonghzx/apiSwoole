<?php

return [
    'server' => [
        'pname'         => _env('PNAME', 'php-Apiswoole'),
        'tcpable'       => _env('TCPABLE', true),
        'cronable'      => _env('CRONABLE', false),
    ],
    'tcp' => [
        'host'                  => _env('TCP_HOST', '0.0.0.0'),
        'port'                  => _env('TCP_PORT', 8099),
        'model'                 => _env('TCP_MODEL', SWOOLE_PROCESS),
        'type'                  => _env('TCP_TYPE', SWOOLE_SOCK_TCP),
        'package_max_length'    => _env('TCP_PACKAGE_MAX_LENGTH', 2048),
        'open_eof_check'        => _env('TCP_OPEN_EOF_CHECK', false),
    ],
    'http' => [
        'host'       => _env('HTTP_HOST', '0.0.0.0'),
        'port'       => _env('HTTP_PORT', 9501),
        'model'      => _env('HTTP_MODEL', SWOOLE_PROCESS),
        'type'       => _env('HTTP_TYPE', SWOOLE_SOCK_TCP),
    ],
    'socket' => [
        'host'                       => _env('SOCKET_HOST', '0.0.0.0'),
        'port'                       => _env('SOCKET_PORT', 8888),
        'model'                      => _env('SOCKET_MODEL', SWOOLE_PROCESS),
    ],
    'crontab' => [ //定时任务
        'task_count' => _env('CRONTAB_TASK_COUNT', 1024),
        'task_queue' => _env('CRONTAB_TASK_QUEUE', 2048),
    ],
    'setting' => [
        'worker_num'        => _env('WORKER_NUM', 8),
        'max_request'       => _env('MAX_REQUEST', 5000),
        'daemonize'         => _env('DAEMONIZE', 0),
        'dispatch_mode'     => _env('DISPATCH_MODE', 2),
        'log_file'          => ROOT . _env('LOG_FILE', '/Runtime/Logs/swoole.log'),
        'pid_file'             => ROOT . _env('PFILE', '/Runtime/Logs/apiswoole.pid'),
        'task_max_request'  => _env('TASK_MAX_REQUEST', 10),
        'task_worker_num'   => _env('TASK_WORKER_NUM', 8),
        'upload_tmp_dir'    => ROOT . _env('UPLOAD_TMP_DIR', '/Runtime/UploadFiles'),
        'server_name'    => _env('SERVER_NAME', 'http://apiswoole.com/'),
    ],
];