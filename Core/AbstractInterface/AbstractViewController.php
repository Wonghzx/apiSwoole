<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18/018
 * Time: 15:00
 */

namespace Core\AbstractInterface;

use Latte\View;

abstract class AbstractViewController extends AbstractController
{
    public function view($tplName, $tplData = [])
    {
        $viewTemplate = View::getInstance()->renderToString(ROOT . '/Http/Views/' . $tplName . '.html', json_decode(json_encode($tplData), true));
        $this->response()->assign($viewTemplate);

    }
}