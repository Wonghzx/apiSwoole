<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14/014
 * Time: 18:28
 */

namespace Core\Swoole\Session;

use Core\AbstractInterface\AbstractSession;
use Core\Component\Di;
use Core\Component\Random;
use Core\Swoole\HttpServer\Storage\Request;
use Core\Swoole\HttpServer\Storage\Response;

class Session extends AbstractSession
{
    private static $instance;

    private $sessionHandler;

    private $sessionName;

    private $sessionSavePath;

    private $sessionId;

    private $isStart;

    static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Session();
        }
        return self::$instance;
    }


    public function __construct()
    {
        $handler = Di::getInstance()->get(SESSION_HANDLER);
        if ($handler instanceof \SessionHandlerInterface) {
            $this->sessionHandler = $handler;
        } else {
            $this->sessionHandler = new SessionHandler();
        }
        $this->initialization();
    }

    /**
     * start  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function start()
    {
        // TODO: Implement start() method.
        if (!$this->isStart) {
            //会话存储的返回值（成功返回 0，失败返回 1）
            $bool = $this->sessionHandler->open($this->sessionSavePath, $this->sessionName);
            if (!$bool) {
                trigger_error("session fail to open {$this->sessionSavePath} @ {$this->sessionName}");
                return false;
            }

            $request = Request::getInstance();
            $cookie = $request->getCookieParams($this->sessionName); //session_id
            if ($this->sessionId) {
                if ($this->sessionId != $cookie) {
                    $data = [
                        $this->sessionName => $this->sessionId
                    ];
                    Response::getInstance()->setCookies($this->sessionName, $this->sessionId);
                }
            } else {
                /*
                 * 判断cookie name 是否存在
                 *
                 */
                if ($cookie === null) {
                    $sid = $this->generateSid();
                    $data = [
                        $this->sessionName => $sid
                    ];

                    Response::getInstance()->setCookies($this->sessionName, $sid);
                    $this->sessionId = $sid;
                } else {
                    $this->sessionId = $cookie;
                }
            }
            $this->isStart = 1;
            return true;
        } else {
            trigger_error('session has start s ');
            return false;
        }
    }

    /**
     * initialization  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    private function initialization()
    {
        $di = Di::getInstance();
        $name = $di->get(SESSION_NAME);
        $this->sessionName = $name ? $name : 'ApiSwoole'; //设置session Name
        $this->sessionSavePath = $di->get(SESSION_SAVE_PATH); ///home/wwwroot/apiSwoole/Runtime/Session/
        $this->sessionId = null;
        $this->isStart = false;
    }


    /**
     * generateSid  [生成Session_id]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    private function generateSid()
    {
        return md5(microtime() . Random::randNumStr(6));
    }


    /**
     * set  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function set($string)
    {
        // TODO: Implement set() method.
        return $this->sessionHandler->write($this->sessionId, $string);
    }


    /**
     * sessionId  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
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
     * read  [当执行read的时候，要求上锁]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function read()
    {
        // TODO: Implement read() method.
        return $this->sessionHandler->read($this->sessionId);
    }

    /**
     * destroy  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function destroy()
    {
        // TODO: Implement destroy() method.
        if ($this->sessionHandler->destroy($this->sessionId)) {
            return $this->close();
        }
        return false;
    }

    /**
     * close  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function close()
    {
        // TODO: Implement close() method.
        if ($this->isStart) {
            $this->initialization();
            return $this->sessionHandler->close();
        } else {
            return true;
        }
    }


    /**
     * sessionName  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function sessionName($name = null)
    {
        // TODO: Implement sessionName() method.
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
     * savePath  [description]
     * @param null $savePath
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function savePath($savePath = null)
    {
        // TODO: Implement savePath() method.
        if ($savePath == null) {
            return $this->sessionSavePath;
        } else {
            if ($this->isStart) {
                trigger_error("your can not change session path as {$savePath} when session is start");
                return false;
            } else {
                $this->sessionSavePath = $savePath;
                return true;
            }
        }
    }

    /**
     * isStart  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function isStart()
    {
        // TODO: Implement isStart() method.
        return $this->isStart;
    }
}