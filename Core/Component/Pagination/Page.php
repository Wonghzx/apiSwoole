<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19/019
 * Time: 10:31
 */

namespace Core\Component\Pagination;


use Core\Swoole\HttpServer\Storage\Request;

class Page
{

    static public function show($countPage = 1, $itemsPerPage = 8)
    {
//        $page = new Pagination($countPage, $itemsPerPage, self::pageNum(), self::getUrl());
//        return $page->toHtml();
    }

    static private function pageNum()
    {
        $page = Request::getInstance()->getRequest();

//        if (empty($page)) {
//            $page = 1;
//        } else {
//            $page = $page['page'];
//        }
//        return $page;
    }


    static private function getUrl()
    {
//        $domain = Request::getInstance()->getUri()->getHost();
//        $path = Request::getInstance()->getUri()->getPath();
//        return 'http://' . $domain . $path . '?page=(:num)';
    }


}