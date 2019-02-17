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
use \Swoft\Bean\Annotation\Value;
use \Facebook\Facebook;
use \GuzzleHttp\Client;

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
     * @Value(name="${config.facebook.fbConfig}")
     */
    public $fbConfig;

    /**
     * @Value(name="${config.facebook.fbURL}")
     */
    public $url;

    /**
     * @RequestMapping(route="login")
     */
    public function getLoginUrl()
    {
        $facebookConfig = $this -> fbConfig;
        $url = $this -> url;
        $fb = new Facebook($facebookConfig);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions

        $facebookLoginUrl = $helper->getLoginUrl($url['url'],$permissions);
        return view('index/login', ['url' => $facebookLoginUrl]);
//        $url = $fb ->loginUrl('https://login.lhydejia.site/user/loginok');
//        $url = $this -> fbController ->loginUrl('https://www.srilankashop.top/user/loginok');
//        return view('index/login', ['url' => $url]);
//        return $url;
    }

    /**
     * @RequestMapping(route="loginok")
     */
    public function loginOk()
    {
        $facebookConfig = $this -> fbConfig;
//        $facebookConfig['http_client_handler'] = 'guzzle';
        $client = new \GuzzleHttp\Client();
        $facebookConfig['http_client_handler'] = $client;

        $fb = new Facebook($facebookConfig);
        $helper = $fb->getRedirectLoginHelper();
        if(isset($_GET['state'])){
            $_SESSION['FBRLH_state']=$_GET['state'];
        }

        try {
            $accessToken = $helper->getAccessToken();
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $message = 'Graph returned an error: ' . $e->getMessage();
            return $message;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $message = 'Facebook SDK returned an error: ' . $e->getMessage();
            return $message;
        }

        //验证token
        if (! isset($accessToken)) {
            if ($helper->getError()) {
                $message = "Error: " . $helper->getError() . "\n";
                $message .= "Error Code: " . $helper->getErrorCode() . "\n";
                $message .= "Error Reason: " . $helper->getErrorReason() . "\n";
                $message .= "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                $message = 'Bad request';
            }
            return $message;
        }

        $oAuth2Client = $fb->getOAuth2Client();

        if (! $accessToken->isLongLived()) {
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                $message = 'Error getting long-lived access token'. $e->getMessage();
                return $message;
            }
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;
        $access_token_value = $accessToken -> getValue();

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,name,email', $access_token_value);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            $message = 'Graph returned an error: ' . $e->getMessage();

        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            $message = 'Facebook SDK returned an error: ' . $e->getMessage();
        }
        return $message;

        $user = $response-> getGraphUser();
        if(!$user){
            $message = "facebook_api_error:: user is null";
            return $message;
        }

        $user_data['fb_access_token'] = $access_token_value;
        $user_data['fb_id'] = $user -> getId();
        $user_data['nickname'] = $user -> getName();
// $user_data['email'] = $user -> getEmail();
        $user_data['email'] = $user -> getField('email');
        $user_data['11email'] = $user['email'];
        $user_data['pic'] = $user -> getPicture();

        return $user_data;

        /*session_start();
        $data = $this -> fbController -> fbCallback();
        return $data;

        /*$user = $data['userInfo'];

        $user_data['fb_access_token'] = $data['token'];
        $user_data['fb_id'] = $user -> getId();
        $user_data['nickname'] = $user -> getName();
        // $user_data['email'] = $user -> getEmail();
        $user_data['email'] = $user -> getField('email');
        $user_data['11email'] = $user['email'];
        $user_data['pic'] = $user -> getPicture();

//        echo "<pre/>";
//        var_dump($user_data);
        return $user_data;*/
        //已获取用户信息， 可header跳转至 xx页面*/

    }

}