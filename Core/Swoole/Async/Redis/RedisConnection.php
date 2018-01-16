<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16/016
 * Time: 13:42
 */

namespace Core\Swoole\Async\Redis;

/**
 * @method select  切换到指定的数据库，数据库索引号 index 用数字值指定，以 0 作为起始索引值。
 * @method hexists 查看哈希表 key 中，给定域 field 是否存在。
 * @method sadd     设置元素加入 key 中
 * @method sMembers  返回 key 中的所有成员
 * Class RedisConnection
 * @package Core\Swoole\Async\Redis
 */
class RedisConnection
{
    /**
     * @var RedisClient
     */
    protected $redis;
    protected $buffer = '';
    /**
     * @var \swoole_client
     */
    protected $client;
    protected $callback;

    /**
     * 等待发送的数据
     */
    protected $wait_send = false;
    protected $wait_recv = false;
    protected $multi_line = false;
    public $fields;

    function __construct(RedisClient $redis)
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        $client->on('connect', [$this, 'onConnect']);
        $client->on('error', [$this, 'onError']);
        $client->on('receive', [$this, 'onReceive']);
        $client->on('close', [$this, 'onClose']);
        $client->connect($redis->host, $redis->port);
        $this->client = $client;
        $redis->pool[$client->sock] = $this;
        $this->redis = $redis;
    }

    /**
     * 清理数据
     */
    function clean()
    {
        $this->buffer = '';
        $this->callback;
        $this->wait_send = false;
        $this->wait_recv = false;
        $this->multi_line = false;
        $this->fields = array();
    }


    /**
     * 执行redis指令
     * @param $cmd
     * @param $callback
     */
    function command($cmd, $callback)
    {
        /**
         * 如果已经连接，直接发送数据
         */
        if ($this->client->isConnected()) {
            $this->client->send($cmd);
        } /**
         * 未连接，等待连接成功后发送数据
         */
        else {
            $this->wait_send = $cmd;
        }
        $this->callback = $callback;
        //从空闲连接池中移除，避免被其他任务使用
        $this->redis->lockConnection($this->client->sock);
    }

    public function onConnect(\swoole_client $client)
    {
        if ($this->wait_send) {

            $client->send($this->wait_send);

            $this->wait_send = '';

        }
    }

    public function onError()
    {
        echo "连接redis服务器失败\n";
    }

    public function onReceive($cli, $data)
    {
        $success = true;
        if ($this->redis->debug) {

            $this->redis->trace($data);
        }
        if ($this->wait_recv) {

            $this->buffer .= $data;

            if ($this->multi_line) {

                $require_line_n = $this->multi_line * 2 + 1 - substr_count($data, "$-1\r\n");

                if (substr_count($this->buffer, "\r\n") - 1 == $require_line_n) {

                    goto parse_multi_line;

                } else {

                    return;
                }
            } else {
                //就绪
                if (strlen($this->buffer) >= $this->wait_recv) {

                    $result = rtrim($this->buffer, "\r\n");

                    goto ready;

                } else {

                    return;
                }
            }
        }

        $lines = explode("\r\n", $data, 2);

        $type = $lines[0][0];

        if ($type == '-') {

            $success = false;

            $result = substr($lines[0], 1);

        } elseif ($type == '+') {

            $result = substr($lines[0], 1);;

        } //只有一行数据
        elseif ($type == '$') {

            $len = intval(substr($lines[0], 1));

            if ($len > strlen($lines[1])) {

                $this->wait_recv = $len;

                $this->buffer = $lines[1];

                $this->multi_line = false;
                return;
            }
            $result = $lines[1];
        } //多行数据
        elseif ($type == '*') {

            parse_multi_line:

            $data_line_num = intval(substr($lines[0], 1));

            $data_lines = explode("\r\n", $lines[1]);

            $require_line_n = $data_line_num * 2 - substr_count($data, "$-1\r\n");

            $lines_n = count($data_lines) - 1;

            if ($lines_n == $require_line_n) {

                $result = [];

                $key_n = 0;

                for ($i = 0; $i < $lines_n; $i++) {

                    //not exists
                    if (substr($data_lines[$i], 1, 2) === '-1') {

                        $value = false;

                    } else {

                        $value = $data_lines[$i + 1];
                        $i++;
                    }
                    if ($this->fields) {

                        $result[$this->fields[$key_n]] = $value;

                    } else {

                        $result[] = $value;
                    }
                    $key_n++;
                }
                goto ready;
            } //数据不足，需要缓存
            else {
                $this->multi_line = $data_line_num;
                $this->buffer = $lines[1];
                $this->wait_recv = true;
                return;
            }
        } elseif ($type == ':') {
            $result = intval(substr($lines[0], 1));
            goto ready;
        } else {
            echo "Response is not a redis result. String:\n$data\n";
            return;
        }

        ready:
        $this->clean();
        $this->redis->freeConnection($cli->sock, $this);
        call_user_func($this->callback, $result, $success);
    }


    function onClose(\swoole_client $cli)
    {
        if ($this->wait_send) {
            $this->redis->freeConnection($cli->sock, $this);
            call_user_func($this->callback, "timeout", false);
        }
    }
}