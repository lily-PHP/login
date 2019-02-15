<?php
namespace App\Middlewares;

use Swoft\App;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * @Bean()
 */
class InterfaceMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getPath();
        if(session()->getId() && (!session()->get('uid') || session()->get('uid') == 1)){
            // 验证当前请求是否需要登录
        }
        $response = $handler->handle($request);
        $response = $response->withAddedHeader('InterfaceMiddleware', $path);
        return $response;
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




}