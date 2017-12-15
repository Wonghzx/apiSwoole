<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/15/015
 * Time: 13:42
 */

namespace Core\Swoole\Session;
class Base
{
    public $session;

    public function __construct()
    {
        $this->session = Session::getInstance();
    }

    public function sessionName($name = null)
    {
        return $this->session->sessionName($name);
    }


    public function savePath($path = null)
    {
        return $this->session->savePath($path);
    }

    public function sessionId($sid = null)
    {
        return $this->session->sessionId($sid);
    }

    public function destroy()
    {
        return $this->session->destroy();
    }

    public function close()
    {
        return $this->session->destroy();
    }

    public function start()
    {
        if (!$this->session->isStart()) {
            return $this->session->start();
        } else {
            trigger_error("session has start");
            return false;
        }
    }
}