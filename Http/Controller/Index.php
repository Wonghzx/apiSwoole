<?php
/**
 * [Index.php name]
 * @author wong <[842687571@qq.com]>
 * Date: 10/12/17
 * Time: 下午11:11
 * @return    [type]    PhpStorm  apiSwoole
 */

namespace Http\Controller;


use Core\AbstractInterface\AbstractViewController;
use Core\Component\Pagination\Page;
use Illuminate\Database\Capsule\Manager AS DB;


class Index extends AbstractViewController
{
    use Base;

    public function index()
    {
//        $this->response()->assign('
//            <style type="text/css">
//       *{ padding: 0; margin: 0; }
//       div{ padding: 4px 48px;}
//       body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px}
//       h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; }
//       p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}
//    </style>
//    <div style="padding: 24px 48px;">
//        <h1>:)</h1><p>欢迎使用<b> ApiSwoole</b></p>
//        <span style="font-size:25px">从未如此之快 - 专为API而生的常驻内存型框架</span>
//       <br/>
//       <span style="font-size:20px">[ 欢迎前往 <a href="https://github.com/Wonghzx/apiSwoole" target="apiSwoole">GitHub</a> 为 ApiSwoole 赏一个Star ]</span>
//   </div>
//        ');

        $get = $this->request()->getQueryParams();
        $p = empty($get['page']) ? 1 : $get['page'];
        $a = DB::table('user')->get()->forPage($p, 1);
        $page = Page::show(3, 1);
        $this->view('Index/index', ['user' => $a, 'page' => $page]);
    }

    public function test()
    {
    }
}