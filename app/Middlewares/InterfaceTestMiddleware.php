<?php
namespace App\Middlewares;

use Swoft\App;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;
use App\Middlewares\BaseMiddleware;
use Swoft\Log\Log;

/**
 * 这是一堆没用的代码，可以忽略
 *
 * @Bean()
 */
class InterfaceTestMiddleware implements MiddlewareInterface
{
    private $isLogin = false;
    private $isGrade = false;

    /**
     * @var array [ 0=> 用户登录态, 1=> 用户权限等级]
     */
    private $userInfo = ['1','1',];

    /**
     * x-User-Lg  user-Login-Grade 用户登录-会员等级
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getPath();
        if(session()->getId()){
            // 验证当前请求是否需要登录
//            $this->validateLogin($path);
//            if($this->isLogin){
//                return \response()->withStatus(301)->withAddedHeader('x-User-Lg', $this->setUserInfo('isLogin'));
//            }
//
//            // 过滤等级
//            $this->validateGrade($path);
//            if($this->isGrade){
//                return \response()->withStatus(301)->withAddedHeader('x-User-Lg', $this->setUserInfo('isGrade'));
//            }
        }
        $response = $handler->handle($request);
        $response = $response->withAddedHeader('InterfaceMiddleware', $path);
        return $response;
        /*// 验证 跨站请求
        $CsrfToken = $this->validateCsrfToken($request->getHeaderLine('Csrf-Token'));
        if($CsrfToken['code'] != 200)return response()->withStatus($CsrfToken['code']);

        // 验证 数据签名
        $DataSign = $this->validateDataSign($request->getHeaderLine('Data-Sign'));
        if($DataSign['code'] != 200)return response()->withStatus($DataSign['code']);

        // 验证 用户session ID
        $SessionId = $this->validateSessionId($request->getHeaderLine('Session-Id'));
        if($SessionId['code'] != 200)return response()->withStatus($SessionId['code']);

        // 验证 AuthKey
        $AuthKey = $this->validateAuthKey($request->getHeaderLine('AuthKey'));
        if($AuthKey['code'] != 200)return response()->withStatus($AuthKey['code']);

        $response = $handler->handle($request);
        $response = $response->withAddedHeader('AuthKey', $AuthKey['value']);
        $response = $response->withAddedHeader('Csrf-Token', $CsrfToken['value']);
        $response = $response->withAddedHeader('Session-Id', $SessionId['value']);
        $response = $response->withAddedHeader('Data-Sign', $DataSign['value']);

        return $response;*/
    }


    /**
     * userInfo
     */
    private function setUserInfo($str)
    {
        $userInfo = $this->userInfo;
        if($str == 'isLogin'){
            $userInfo[0] = '2';
        }
        if($str == 'isGrade'){
            $userInfo[1] = '2';
        }
        return $userInfo;
    }


    /**
     * 检测当前路径是否需要登录，如未登录 true
     */
    private function validateLogin($path)
    {
        $s_id = session()->getId();
        $u_id = session()->get('uid');
        $this->isLogin = true;
    }


    /**
     * 检测当前路径是否为当前用户所在的会员等级，如不在 true
     */
    private function validateGrade($path)
    {
        // 等级验证，检测 cookie 传入的值是否为与存储的值相同
        // 检测 path 是否为会员路径
        $this->isGrade = true;
    }






    /**
     * 验证 CsrfToken
     */
    private function validateCsrfToken($CsrfToken)
    {
        // code = 310 不允许的请求
        // 验证是否超时

        // 验证是否符合
        return ['code'=>200,'value'=>$CsrfToken];
    }


    /**
     * 验证 数据签名
     */
    private function validateDataSign($DataSign)
    {
        // code = 311 签名错误
        return ['code'=>200,'value'=>$DataSign];
    }


    /**
     * 验证 用户信息
     */
    private function validateSessionId($SessionId)
    {
        // code = 312 无效的用户
        return ['code'=>200,'value'=>$SessionId];
    }


    /**
     * 验证 用户登录信息
     */
    private function validateAuthKey($AuthKey)
    {
        // code = 313 用户数据错误
        return ['code'=>200,'value'=>$AuthKey];
    }


    /**
     * 组合 哈希码
     */
    private function getHashCode($code=false)
    {
        return $code;
    }
}