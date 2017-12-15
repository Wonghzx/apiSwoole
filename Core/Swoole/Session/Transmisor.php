<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 13:55
 */

namespace Core\Swoole\Session;

class Transmisor extends Base
{

    private static $instance;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Transmisor();
        }
        return self::$instance;
    }

    public function set($key, $default)
    {
        if (!$this->session->isStart()) {

            $this->session->start();
        }
        $data = $this->session->read();

        $data = unserialize($data);

        if (!is_array($data)) {

            $data = [];
        }
        $data[$key] = $default;

        return $this->session->set(serialize($data));
    }

    public function get($key, $default = null)
    {
        if (!$this->session->isStart()) {

            $this->session->start();
        }
        $data = $this->session->read();

        $data = unserialize($data);

        if (is_array($data)) {

            if (isset($data[$key])) {

                return $data[$key];

            } else {
                return $default;
            }
        } else {
            return $default;
        }
    }

    public function toArray()
    {
        if (!$this->session->isStart()) {

            $this->session->start();
        }

        $data = $this->session->read();

        $data = unserialize($data);

        if (is_array($data)) {

            return $data;

        } else {

            return array();
        }
    }
}