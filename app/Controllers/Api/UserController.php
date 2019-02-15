<?php
namespace App\Controllers\Api;

use App\Models\Logic\IndexLogic;
use Swoft\App;
use Swoft\Core\Coroutine;
use Swoft\Bean\Annotation\Inject;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\View\Bean\Annotation\View;
use Swoft\Core\Application;
use Swoft\Http\Message\Server\Request;

/**
 * Class UserController
 * @Controller(prefix="/user")
 */
class UserController
{
    /**
     * @Inject(name="FacebookController")
     */
    public $fbController;

    /**
     * @RequestMapping(route="login")
     */
    public function getLoginUrl()
    {
//        return 'ok';
        $url = $this -> fbController ->loginUrl('https://login.lhydejia.site/user/loginok');
        return view('index/login', ['url' => $url]);
//        return $url;
//        return '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
    }

    /**
     * @RequestMapping(route="loginok")
     */
    public function loginOk()
    {
        $helper = $this ->fbController ->fb -> getRedirectLoginHelper();
        //获取token
        try {
            $accessToken = $helper->getAccessToken();
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        //验证token
        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                return "Error: " . $helper->getError() . "\n";
                return "Error Code: " . $helper->getErrorCode() . "\n";
                return "Error Reason: " . $helper->getErrorReason() . "\n";
                return "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                return 'Bad request';
            }
            exit;
        }

        //成功登陆，获取token
//        echo '<h3>Access Token</h3>';
//        var_dump($accessToken->getValue());
//        $AccessToken = $accessToken -> getValue();

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $this ->fbController ->getOAuth2Client();

        // 有的用户第一次登陆，token验证期只有2个小时， 可以将其延长至60天
        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                return "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }

//            echo '<h3>Long-lived</h3>';
//            var_dump($accessToken->getValue());
        }

        //将token保存至session
        $_SESSION['fb_access_token'] = (string) $accessToken;
        $access_token_value = $accessToken -> getValue();

        //获取用户信息： ID，name，email
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this ->fbController ->get('/me?fields=id,name,email', $access_token_value);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user = $response->getGraphUser();
        if(!$user){
            return "facebook_api_error:: user is null";
            exit;
        }

        $user_data['fb_access_token'] = $access_token_value;
        $user_data['fb_id'] = $user -> getId();
        $user_data['nickname'] = $user -> getName();
        // $user_data['email'] = $user -> getEmail();
        $user_data['email'] = $user -> getField('email');
        $user_data['11email'] = $user['email'];
        $user_data['pic'] = $user -> getPicture();

//        echo "<pre/>";
//        var_dump($user_data);
        return $user_data;
        //已获取用户信息， 可header跳转至 xx页面

    }

}