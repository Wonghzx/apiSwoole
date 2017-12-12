<?php
/**
 * [Index.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 10/12/17
 * Time: 下午11:11
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Http\Controller;

use Core\AbstractInterface\AbstractController;
use Core\Swoole\HttpServer\Storage\Response;

class Test
{
    public function index()
    {
        Response::getInstance()->write('index');
    }

    public function test()
    {
        Response::getInstance()->write('test');
    }

    public function test1()
    {
    }

    public function test2()
    {
    }

    function onRequest($actionName)
    {
        // TODO: Implement onRequest() method.
    }

    function actionNotFound($actionName = null, $arguments = null)
    {
        // TODO: Implement actionNotFound() method.
    }

    function afterAction()
    {
        // TODO: Implement afterAction() method.
    }
}