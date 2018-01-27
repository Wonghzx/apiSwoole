<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/23/023
 * Time: 10:00
 */

namespace Core\Component\Session;

use Core\Component\Random;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Response;

class Session
{

    /**
     * @var 单例模式
     */
    private static $instance;

    /**
     * @var Request Name
     */
    private $sessionName;

    /**
     * @var Session 路径
     */
    private $sessionSavePath;

    /**
     * @var bool
     */
    private $isStart = false;

    /**
     * @var Session 回话程序
     */
    private $sessionHandler;

    /**
     * @var Session_id
     */
    private $sessionId;


    static public function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new Session();

        return self::$instance;
    }


    public function __construct()
    {
        $handler = getConf('session.session_handler');
        if ($handler instanceof \SessionHandlerInterface)
            $this->sessionHandler = $handler;
        else
            $this->sessionHandler = new SessionHandler();

        $this->init();
    }


    /**
     * init  [初始化]
     */
    private function init()
    {
        $session_name = getConf('session.session_name');
        $this->sessionName = $session_name ? $session_name : 'ApiSwoole';
        $this->sessionSavePath = getConf('session.session_save_path');
        $this->sessionId = null;
        $this->isStart = false;
    }


    /**
     * start  [开始]
     */
    public function start()
    {
        if (!$this->isStart) {
            $boolean = $this->sessionHandler->open($this->sessionSavePath, $this->sessionName);
            if (!$boolean) {
                trigger_error("session fail to open {$this->sessionSavePath} @ {$this->sessionName}");
                return false;
            }
            $request = Request::getInstance();
            $cookie = (array)$request->getCookieParams();
            $cookie = isset($cookie[$this->sessionName]) ? $cookie[$this->sessionName] : null;
            if ($this->sessionId) {
                //预防提前指定sid
                if ($this->sessionId != $cookie) {
                    $data = [
                        $this->sessionName => $this->sessionId
                    ];
                    $request->withCookieParams($request->getCookieParams() + $data);
                    Response::getInstance()->setCookies($this->sessionName, $this->sessionId);
                }
            } else {

                if ($cookie === null) {
                    $sid = $this->generateSid();
                    $data = [
                        $this->sessionName => $sid
                    ];
                    $request->withCookieParams($request->getCookieParams() + $data);
                    Response::getInstance()->setCookies($this->sessionName, $sid);
                    $this->sessionId = $sid;
                } else {
                    $this->sessionId = $cookie;
                }
            }
            $this->isStart = 1;
            return true;
        } else {
            trigger_error('session has start');
            return false;
        }
    }

    public function isStart(): bool
    {
        return $this->isStart;
    }

    /**
     * sessionId  [session_id]
     * @param null $sid
     * @return bool|Session_id
     */
    public function sessionId($sid = null)
    {
        if ($sid === null) {
            return $this->sessionId;
        } else {
            if ($this->isStart) {
                trigger_error("your can not change session sid as {$sid} when session is start");
                return false;
            } else {
                $this->sessionId = $sid;
                return true;
            }
        }
    }

    /**
     * sessionName  [description]
     * @param null $name
     * @copyright Copyright (c)
     */
    public function sessionName($name = null)
    {
        if ($name == null) {
            return $this->sessionName;
        } else {
            if ($this->isStart) {
                trigger_error("your can not change session name as {$name} when session is start");
                return false;
            } else {
                $this->sessionName = $name;
                return true;
            }
        }
    }

    /**
     * close  [description]
     * @return bool
     */
    public function close(): bool
    {
        if ($this->isStart) {
            $this->init();
            return $this->sessionHandler->close();
        } else {
            return true;
        }
    }

    /**
     * read  [读]
     */
    public function read()
    {
        //当执行read的时候，要求上锁
        return $this->sessionHandler->read($this->sessionId);
    }


    /**
     * write  [description]
     * @param $string
     * @return mixed
     */
    public function write($string)
    {
        return $this->sessionHandler->write($this->sessionId, $string);
    }


    /**
     * destroy  [description]
     * @return bool
     */
    public function destroy()
    {
        if ($this->sessionHandler->destroy($this->sessionId)) {
            return $this->close();
        }
        return false;
    }

    /**
     * generateSid  [生成SID]
     */
    private function generateSid(): string
    {
        return md5(microtime() . Random::randStr(3));
    }

}