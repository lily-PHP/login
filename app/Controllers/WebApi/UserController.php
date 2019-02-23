<?php
namespace App\Controllers\WebApi;

use App\Commands\HttpCurl;
use App\Models\Entity\ZhUser;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use \Swoft\Bean\Annotation\Value;
use Facebook\Facebook;
use Swoft\Log\Log;

/**
 * Class UserController
 * @Controller(prefix="/user")
 */
class UserController
{
    private $fb = false;

    /**
     * @Value(name="${config.facebook.fbConfig}")
     */
    public $fbConfig;

    /**
     * @Value(name="${config.facebook.fbURL}")
     */
    public $url;

    private $inst = false;

    private $twitter = false;

    private $twitterUrl = false;

    public function __construct()
    {
        $facebookConfig = $this -> fbConfig;

        $this->fb = new Facebook([
            'app_id' => '562622214203764',
            'app_secret' => 'fe229c29e799664e6d72c105f5c3f77c',
            'default_graph_version' => 'v2.10',
        ]);

        $this->inst = config('instConfig');

        $this->twitter = config('twitterConfig');

        $this->twitterUrl = config('twitterUrl');

        //$this -> fb = new Facebook($facebookConfig);
    }

    /**
     * @RequestMapping(route="login")
     * @return Response
     */
    public function getLoginUrl(Request $request)
    {
        session()->start();

        $url = $this -> url;
        $helper = $this->fb->getRedirectLoginHelper();


        //$facebookLoginUrl = $helper->getLoginUrl($url['url'], ['email', 'picture', 'gender']);
        //$facebookLoginUrl = $helper->getLoginUrl($url['url'], ['email']);
        $facebookLoginUrl = $helper->getLoginUrl($url['url'], ['email','user_photos', 'user_gender', 'user_location']);

        return view('index/login', ['url' => $facebookLoginUrl,'inst'=>$this->inst['code_url'].'?client_id='.$this->inst['client_id'].'&redirect_uri='.$this->inst['redirect_uri'].'&response_type=code']);
    }

    /**
     * @RequestMapping(route="insloginok", method={RequestMethod::GET, RequestMethod::POST})
     */
    public function insLoginOk(Request $request){

        $code = $request->getUri()->getQuery();
        $param = explode('=',$code);

        $curl = new HttpCurl();

        $data = [
            'client_id'=> $this->inst['client_id'],
            'client_secret'=> $this->inst['client_secret'],
            'grant_type'=> $this->inst['grant_type'],
            'redirect_uri'=> $this->inst['redirect_uri'],
            'code'=> $param[1],
        ];

        $res = $curl->requestCURL($this->inst['access_token_url'],'POST',$data);

        $file = \Swoft::getAlias('@runtime');
        if(isset($res->code)){
            file_put_contents($file.'/facebook.log', "用户登录失败",FILE_APPEND);
            file_put_contents($file.'/facebook.log', "失败信息：".json_encode($res)."\n\n",FILE_APPEND);
            return;
        }

        $user = ZhUser::findOne(['zu_userId' => $res->user->id])->getResult();

        if($user){
            // 登录成功处理
            file_put_contents($file.'/facebook.log', "旧用户登录成功处理\n\n",FILE_APPEND);
            return;
        }


        $zhUser = new ZhUser();

        $zhUser->setZuUsername($res->user->username);
        $zhUser->setZuToken($res->access_token);
        $zhUser->setZuUserfrom('Instagram');
        $zhUser->setZuUserId($res->user->id);
        $zhUser->setZuAvatar($res->user->profile_picture);
        $id = $zhUser->save()->getResult();
        if($id){
            // 登录成功
            file_put_contents($file.'/facebook.log', "新用户登录成功处理\n\n",FILE_APPEND);

            // 跳转完善信息

        }
        //$this->redirect("login");
        //return view('index/login', ['access_token'=>json_decode($res)]);

    }


    /**
     * @RequestMapping(route="twitterlogin", method={RequestMethod::GET, RequestMethod::POST})
     */
    /*public function twitterLogin(){
        $header = $this->twitter;
        $curl = new HttpCurl();

        $res = $curl->requestCURL($this->twitterUrl['request_token'],'POST','',$header);

    }*/

    /**
     * @RequestMapping(route="twitterloginok", method={RequestMethod::GET, RequestMethod::POST})
     */
    /*public function twitterLoginOk(Request $request){

        $curl = new HttpCurl();

        //$header = $this->twitter;
        //$res = $curl->requestCURL($this->twitterUrl['request_token'],'POST','',$header);

        $res = $curl->requestCURL($this->twitterUrl['authenticate'].'?oauth_token=Z6eEdO8MOmk394WozF5oKyuAv855l4Mlqo7hhlSLik','GET');

        $header = '';
        $res = $curl->requestCURL($this->twitterUrl['access_token'],'POST','',$header);


    }*/

    /**
     * @RequestMapping(route="loginok", method={RequestMethod::GET, RequestMethod::POST})
     * @throws \Facebook\Exceptions\FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function loginOk(Request $request)
    {
        session()->start();
        //$this -> getFbObj();
        session()->put(['fb_code'=> $request->query()]);
        $facebookConfig = $this -> fbConfig;

        $helper = $this->fb->getRedirectLoginHelper();
        if(!empty($request->input('state'))){
            session()->put(['FBRLH_state'=> $request->input('state')]);
            session()->put(['state'=> $request->input('state')]);
        }

        $file = \Swoft::getAlias('@runtime');
        if( !file_exists($file) ){
            $mask = @umask(0);
            $result = @mkdir($file, 0777, true);
            @umask($mask);
        }

        try {
            $url = $request -> fullUrl();
            $accessToken = $helper->getAccessToken($url);
            file_put_contents($file.'/logs/facebook.log', (string) $accessToken. "\n\n",FILE_APPEND);

        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $message = 'Graph returned an error~~~~~~: ' . $e->getMessage();
            Log::error('facebook login failed',['error'=>$message]);//Log的命名空间没加,写的时候自己加一下
            file_put_contents($file.'/logs/facebook.log', $message. "\n\n",FILE_APPEND);

        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $message = 'Facebook SDK returned an error~~~~~~~~: ' . $e->getMessage();
            Log::error('facebook login failed',['error'=>$message]);
            file_put_contents($file.'/logs/facebook.log', $message. "\n\n",FILE_APPEND);
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                $message = "Error~~~: " . $helper->getError() . "\n";
                $message .= "Error Code~~~~: " . $helper->getErrorCode() . "\n";
                $message .= "Error Reason~~~~~: " . $helper->getErrorReason() . "\n";
                $message .= "Error Description~~~~~~: " . $helper->getErrorDescription() . "\n";
            } else {
                $message = 'Bad request';
            }
            Log::warning('facebook login failed',['error'=>$message]);
            file_put_contents($file.'/logs/facebook.log', $message. "\n\n",FILE_APPEND);
        }

        $oAuth2Client = $this->fb->getOAuth2Client();
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        $tokenMetadata->validateAppId($facebookConfig['app_id']);
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                $message = 'Error getting long-lived access token~~~~~'. $e->getMessage();
                Log::warning('facebook login failed~~~~~~',['error'=>$message]);
                file_put_contents($file.'/logs/facebook.log', $message. "\n\n",FILE_APPEND);
            }
        }
        $_SESSION['fb_access_token'] = (string) $accessToken;
        $this->fb->setDefaultAccessToken($accessToken);

        try {
            $response = $this->fb ->get('/me?fields=id,name,email,picture, gender, location{location}');
            $responsePic = $this->fb ->get('/me/picture?redirect=false');
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            $message = 'Graph returned an error~~~~: ' . $e->getMessage();

        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            $message = 'Facebook SDK returned an error~~~~~: ' . $e->getMessage();
        }
        file_put_contents($file.'/logs/facebook.log', $message. "\n\n",FILE_APPEND);

//        $user = $response-> getGraphUser();
        $user = $response -> getGraphNode();
        $userArr = $response -> getGraphNode() -> asArray();
        $pic = $responsePic->getGraphObject()->asArray();
        if(!$user){
            $message = "facebook_api_error~~~~~~:: user is null";
            file_put_contents($file.'/logs/facebook.log', $message. "\n\n",FILE_APPEND);
        }

        $user_data['fb_access_token'] = (string) $accessToken;
        $user_data['fb_id'] = $user -> getField('id');
        $user_data['nickname'] = $user -> getField('name');
        $user_data['email'] = $user -> getField('email');
        $user_data['pic'] = $pic['url'];
        $user_data['locale'] = $userArr['location']['location']['country'];

        $user_data['gender'] = $user -> getField('gender');
        return $user_data;

    }

    /**
     * @RequestMapping(route="fblogin")
     */
    public function fbLogin()
    {
        return view('index/fbindex');
    }

    /**
     * @RequestMapping(route="saveuser")
     */
    public function saveUserData()
    {
        return 'to be save mysql';
    }

    /**
     * @RequestMapping(route="fbutton")
     */
    public function fbuton()
    {
        return view('index/fbjs');
    }

}