<?php

namespace Core\Component\IO;


class FileIO extends Stream
{
    function __construct($file, $mode = 'c+')
    {
        $fp = fopen($file, $mode);
        parent::__construct($fp);
    }

    /**
     * lock  [ +锁]
     * @param int $mode
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    function lock($mode = LOCK_EX)
    {
        return flock($this->getStreamResource(), $mode);
    }

    /**
     * unlock  [解锁]
     * @param int $mode
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    function unlock($mode = LOCK_UN)
    {
        return flock($this->getStreamResource(), $mode);
    }
}