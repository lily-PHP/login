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
 * @uses      SubMiddleware
 */
class BaseMiddleware implements MiddlewareInterface
{
    private $this_user = false;
    private $key = 'wTnF0GPTDUNt2J2Q2eVmVo43';
    private $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $c_uid = $request->getCookieParams();
        if($c_uid['UserLg']){
            $UserLg = explode(';', $c_uid['UserLg'])[0];
            $this->this_user = $UserLg && $UserLg != "1" ? $UserLg : false;
        }

        // 测试环境
        $system_type = env('APP_ENV');

        if(in_array($system_type, ['local', 'dev'])){
            if ($request->getMethod() == 'OPTIONS') {
                return response()->withStatus(202);
            }
            $response = $handler->handle($request);
            $response = $this->addUserInfoToHeader($response);
            return $this->configResponse($response);
        }

        // 生产环境
        $response = $handler->handle($request);
        $response = $this->addUserInfoToHeader($response);
        return $response;
    }


    private function configResponse(ResponseInterface $response)
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', ['http://127.0.0.1:8080'])
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Headers', "X-Requested-With,Content-Type")
            ->withHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
    }


    /**
     * 携带用户信息
     */
    private function addUserInfoToHeader(ResponseInterface $response)
    {
        $s_id = session()->getId();
        $u_id = session()->get('uid');
        $userInfo = ['1', '1', '1', '1', '1'];
        if($s_id && $u_id){
            $userInfo[0] = $this->validateUser();
            $userInfo[1] = session()->get('gid') ? session()->get('uid') : '1';
        }
        return $response->withAddedHeader('x-User-Lg', $userInfo);
    }


    /**
     * 检测当前用户的合法性
     */
    private function validateUser()
    {
        // 检测 cookie 传入的用户值，解密验证，是否为合法用户
        $u_id = session()->get('uid');
        $e_uid = $this->encryptUser($u_id);

        return $e_uid != $this->this_user || empty($u_id) ? '1' : $e_uid;
    }


    /**
     * 用户进行加密
     */
    private function encryptUser($u)
    {
        $txt = $u.$this->key;
        $nh = rand(0,64);
        $ch = $this->chars[$nh];
        $mdKey = md5($this->key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = base64_encode($txt);
        $tmp = '';
        $i=0;$j=0;$k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh+strpos($this->chars,$txt[$i])+ord($mdKey[$k++]))%64;
            $tmp .= $this->chars[$j];
        }
        return urlencode(base64_encode($ch.$tmp));
    }


    /**
     * 用户进行解密
     */
    private function decryptUser($u)
    {
        $txt = base64_decode(urldecode($u));
        $ch = $txt[0];
        $nh = strpos($this->chars,$ch);
        $mdKey = md5($this->key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = substr($txt,1);
        $tmp = '';
        $i=0;$j=0; $k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($this->chars,$txt[$i])-$nh - ord($mdKey[$k++]);
            while ($j<0) $j+=64;
            $tmp .= $this->chars[$j];
        }
        return trim(base64_decode($tmp),$this->key);
    }



}