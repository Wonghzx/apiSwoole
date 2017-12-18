<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18/018
 * Time: 15:28
 */

namespace Latte;


class View
{
    static function getInstance()
    {


        static $Engine = null;
        if ($Engine === null) {
            $Engine = new Engine();
            $Engine->setTempDirectory(ROOT . '/Runtime/TplCache');
        }
        return $Engine;
    }
}