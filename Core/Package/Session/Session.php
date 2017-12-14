<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13/013
 * Time: 18:04
 */

namespace Session;

use Core\Component\Di;

class Session
{
    private $sessionName;

    private $sessionSavePath;

    private $isStart = false;

    private $sessionHandler;

    private $sessionId;

    private static $staticInstance;

    public static function getInstance()
    {
        if (!isset(self::$staticInstance)) {
            self::$staticInstance = new Session();
        }
        return self::$staticInstance;
    }

    public function __construct()
    {
        $handler = Di::getInstance()->get(SESSION_HANDLER);

        if ($handler instanceof \SessionHandler) {
            $this->sessionHandler = $handler;
        } else {
            $this->sessionHandler = new SessionHandler();
        }
        $this->init();
    }


    /**
     * init  [初始化]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function init()
    {
        $di = Di::getInstance();
        $name = $di->get(SESSION_NAME);
        $this->sessionName = $name ? $name : 'ApiSwoole';
        $this->sessionSavePath = $di->get(SESSION_SAVE_PATH);
        $this->sessionId = null;
        $this->isStart = false;
    }

    public function start()
    {
        if (!$this->isStart) {
            $boolean = $this->sessionHandler->open($this->sessionSavePath, $this->sessionName);
            if (!$boolean) {
                trigger_error("session fail to open {$this->sessionSavePath} @ {$this->sessionName}");
                return false;
            }
            $probability = intval(Di::getInstance()->get(SESSION_GC_PROBABILITY));
            $probability = $probability >= 30 ? $probability : 1000;
        }

    }

}