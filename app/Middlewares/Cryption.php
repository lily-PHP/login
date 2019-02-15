<?php
namespace App\Middlewares;


class Cryption
{
    private $code = false;

    private function __construct()
    {
        $this->code = env('CSRF_CODE', false);
    }

    /*
     * 获取 csrf
     * 由MD5加密后配置参数切割重组,再次与请求的参数序列化后获得
     */
    protected function getCsrf()
    {
        //
    }


    /*
     * 验证 csrf
     */
    protected function validateCsrf()
    {
        //
    }


    /*
     * 获取用户 token
     */
    protected function getUserToken()
    {
        //
    }


    /*
     * 验证用户 token
     */
    protected function validateUserToken()
    {
        //
    }


    /*
     * 获取用户参数
     */
    protected function getUserInfo()
    {
        //
    }


    /*
     * 验证用户参数
     */
    protected function validateUserInfo()
    {
        //
    }
}