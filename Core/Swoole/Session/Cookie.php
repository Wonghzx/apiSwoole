<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14/014
 * Time: 18:03
 */

namespace Core\Swoole\Session;
class Cookie
{
    private $key; //name string

    private $value = ''; //value string

    private $expire = 0; //到期时间  秒计算

    private $path = '/'; //cookie工作路劲

    private $domain = ''; //顶级域名和多级域名共享

    private $secure = false; //基于安全的考虑

    private $httpOnly = false; //安全保证


    /**
     * getName  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    public function getName()
    {
        return $this->key;
    }


    /**
     * getValue  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * getExpire  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return int
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * getPath  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * getDomain  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * getSecure  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * getHttpOnly  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function getHttpOnly()
    {
        return $this->httpOnly;
    }


    /**
     * setKey  [description]
     * @param $key
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * setValue  [description]
     * @param $value
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * setExpire  [description]
     * @param $value
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;
    }

    /**
     * setPath  [description]
     * @param $value
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * setDomain  [description]
     * @param $value
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * setSecure  [description]
     * @param $value
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;
    }

    /**
     * setValue  [description]
     * @param $value
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setHttpOnly($httpOnly)
    {
        $this->httpOnly = $httpOnly;
    }

    /**
     * __toString  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return "{$this->key}={$this->value}";
    }

}
