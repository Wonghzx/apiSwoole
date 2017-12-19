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
        View::getInstance()->setAutoRefresh(false);
        $this->response()->assign($viewTemplate);

    }


    /**
     * objTransferStr  [对象转字符串]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function objTransferStr($obj)
    {
        if (is_object($obj)) {
            if (method_exists($obj, "__toString")) {
                $obj = $obj->__toString();
            } else if (method_exists($obj, 'jsonSerialize')) {
                $obj = json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                $obj = var_export($obj, true);
            }
        } else if (is_array($obj)) {
            $obj = json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        return $obj;
    }

}