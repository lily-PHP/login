<?php
namespace App\MyFunctions;

use \Swoft\Bean\Annotation\Bean;
use \Swoft\Bean\Annotation\Inject;
use \Facebook\Facebook;

/**
 * Class FacebookController
 * @bean("FacebookController")
 */
class FacebookController
{
    private $appID = '562622214203764';
    private $appSecret = 'fe229c29e799664e6d72c105f5c3f77c';
    private $version = 'v2.10';
    public $fb = null;

    public function __construct()
    {
        $this -> init();
    }

    public function init()
    {
        $this -> fb = new Facebook([
            'app_id' => $this -> appID,
            'app_secret' => $this -> appSecret,
            'default_graph_version' => $this -> version,
        ]);
    }

    public function loginUrl($url)
    {
        $helper = $this->fb -> getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($url, $permissions);
        return $loginUrl;
    }

    public function fbCallback()
    {
        $helper = $this ->fb -> getRedirectLoginHelper();
        $get = $_GET;

//        return $helper;
        //获取token
        try {
            $accessToken = $helper->getAccessToken();
//            return $accessToken;
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
//            return 'Graph returned an error: ' . $e->getMessage();
            return $e;
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
//            return 'Facebook SDK returned an error: ' . $e->getMessage();
            return $e;
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
        $oAuth2Client = $this ->fb ->getOAuth2Client();

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
//        $_SESSION['fb_access_token'] = (string) $accessToken;
        $access_token_value = $accessToken -> getValue();

        //获取用户信息： ID，name，email
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this ->fb ->get('/me?fields=id,name,email', $access_token_value);
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

        $data = ['token' => $access_token_value, 'userInfo' => $user, 'get' => $get];
        return $data;
    }



}