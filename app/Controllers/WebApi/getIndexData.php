<?php
namespace App\Controllers\WebApi;

use Swoft\Db\Db;
use Swoft\Bean\Annotation\Inject;
use Swoft\Cache\Cache;

/**
 * Class getIndexData
 * 获取首页数据类
 */
class getIndexData{
    /**
     * 当前所在的大区
     */
    private $area = false;


    /**
     * 当前所在的国家
     */
    private $country = false;


    /**
     * 获取当前首页数据
     */
    public function __construct()
    {
        $ret = [];
        $ret['header'] = '';
        $ret['list'] = '';
        $ret['footer'] = '';
        return $ret;
    }


    /**
     * 获取分类导航
     */
    private function getClassify()
    {
        //
    }


    /**
     * 获取headers
     */
    private function getHeaders()
    {
        return [
            'template_id' => 1,
            'info' => [
                'phone_show' => "true",
                "phone" => "01456987",
                "email_show" => "true",
                "email" => "aa.qq.com",
                "logo_url" => "http://static.zh-face.com/mallWebsite/decoration/uploads/images/LogoImg/1/1548149595_JpyiWlhAOu.jpg",
                "calssifyId" => [
                    "175",
                    "263",
                    "266",
                    "269",
                    "274",
                    "294"
                ],
                "head_text_color" => "#fff",
                "head_bc_color" => "#0ff"
            ],

        ];
    }


    /**
     * 获取banner:轮播
     */
    private function getBanner()
    {
        return [
            "name" => "swiper_box",
            "template_id" => null,
            "info" => [
                [
                    "imgurl" => "http://static.zh-face.com/mallWebsite/decoration/uploads/images/SlideImg/1/1548149342_jgYLh6BXFp.jpg",
                    "imgsize" => "small",
                    "imgjumpurl_name" => "--------hat",
                    "imgjumpurl_id" => "277"
                ],
                [
                    "imgurl" => "http://static.zh-face.com/mallWebsite/decoration/uploads/images/SlideImg/1/1548149349_q17xX5MRu4.jpg",
                    "imgsize" => "small",
                    "imgjumpurl_name" => "--------hat",
                    "imgjumpurl_id" => "277"
                ],
                [
                    "imgurl" => "http://static.zh-face.com/mallWebsite/decoration/uploads/images/SlideImg/1/1548149342_jgYLh6BXFp.jpg",
                    "imgsize" => "small",
                    "imgjumpurl_name" => "--------hat",
                    "imgjumpurl_id" => "277"
                ]
            ]
        ];
    }


    /**
     * 获取list分类
     */
    private function getList()
    {
        return [
            "name" => "abc",
            '' => '',
            "template_id" => 2,
            "info" => [
                [
                    "imgurl" => "http://static.zh-face.com/mallWebsite/decoration/uploads/images/SlideImg/1/1548149342_jgYLh6BXFp.jpg",
                    "imgsize" => "small",
                    "imgjumpurl_name" => "--------hat",
                    "imgjumpurl_id" => "277"
                ],
                [
                    "imgurl" => "http://static.zh-face.com/mallWebsite/decoration/uploads/images/SlideImg/1/1548149349_q17xX5MRu4.jpg",
                    "imgsize" => "small",
                    "imgjumpurl_name" => "--------hat",
                    "imgjumpurl_id" => "277"
                ],
                [
                    "imgurl" => "http://static.zh-face.com/mallWebsite/decoration/uploads/images/SlideImg/1/1548149342_jgYLh6BXFp.jpg",
                    "imgsize" => "small",
                    "imgjumpurl_name" => "--------hat",
                    "imgjumpurl_id" => "277"
                ]
            ]
        ];
    }


    /**
     * 获取footer
     */
    private function getFooter()
    {
        return [];
    }
}