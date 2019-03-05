<?php

namespace App\Controllers\WebApi;
use App\Commands\HttpCurl;
use App\Models\Entity\ZhUser;
use Swoft\Db\Query;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use \Swoft\Bean\Annotation\Value;
use Facebook\Facebook;
use Swoft\Db\Db;
use App\Controllers\publicReturn;
use Swoft\Http\Message\Cookie\Cookie;


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

    private $google = false;

    private $ret = null;

    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => '562622214203764',
            'app_secret' => 'fe229c29e799664e6d72c105f5c3f77c',
            'default_graph_version' => 'v2.10',
        ]);

        $this->inst = config('instConfig');

        $this->twitter = config('twitterConfig');

        $this->twitterUrl = config('twitterUrl');

        $this->ret = (new publicReturn())->getReturn();

        $this->google = config('googleConfig');
    }

    /**
     * 测试 登录页面
     * @RequestMapping(route="login")
     * @return Response
     */
    public function getLoginUrl(Request $request)
    {
        /*$ret1 = $this->redis->set('set', ['name1', 'stelin1']);
        $ret2 = $this->cache->set('set', ['name2', 'stelin2']);

        return [$ret1,$ret2];*/

        return view('index/login', [
            'inst'=>$this->inst['code_url'].'?client_id='.$this->inst['client_id'].'&redirect_uri='.$this->inst['redirect_uri'].'&response_type=code',
            'google_client_id'=> $this->google['client_id']
        ]);
    }

    /**
     * @RequestMapping(route="googleLogin",method={RequestMethod::POST})
     */
    public function verifyGoogleIdToken(Request $request){

        $id_token = $request->post('id_token');

        //return $id_token;
        $client = new \Google_Client(['client_id' => $this->google['client_id']]);

        $payload = $client->verifyIdToken($id_token);

        if ($payload) {
            $userid = $payload['sub'];
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
        } else {
            // Invalid ID token
        }
    }

    /**
     * @RequestMapping(route="insloginok", method={RequestMethod::GET, RequestMethod::POST})
     */
    public function insLoginOk(Request $request){

        $code  = $request->query('code');

        //$curl = new HttpCurl();
        //$res = $curl->requestCURL($this->inst['access_token_url'],'POST',$data);

        $data = [
            'client_id'=> $this->inst['client_id'],
            'client_secret'=> $this->inst['client_secret'],
            'grant_type'=> $this->inst['grant_type'],
            'redirect_uri'=> $this->inst['redirect_uri'],
            'code'=> $code,
        ];

        $res = HttpCurl::post($this->inst['access_token_url'],$data);


        $file = \Swoft::getAlias('@runtime');

        file_put_contents($file.'/facebook.log', "2旧用户登录成功处理".json_encode($res)."\n\n",FILE_APPEND);

        if(isset($res->code)){
            $this->ret['code'] = 500;
            $this->ret['msg'] = '登录失败';
            file_put_contents($file.'/facebook.log', "用户登录失败",FILE_APPEND);
            file_put_contents($file.'/facebook.log', "失败信息：".json_encode($res)."\n\n",FILE_APPEND);
            return $this->ret;
        }

        $user = ZhUser::findOne(['zu_userId' => $res->user->id])->getResult();

        if($user){

            // 登录成功处理
            file_put_contents($file.'/facebook.log', "旧用户登录成功处理\n\n",FILE_APPEND);

            $this->ret['code'] = 200;
            $this->ret['msg'] = '登录成功';
            $this->ret['success'] = true;
            $this->ret['item'] = $user;
            //用户信息存session
            file_put_contents($file.'/facebook.log', "旧用户登录成功处理\n\n".json_encode($user),FILE_APPEND);

            session()->put(['userData' => $user]);
            return $this->ret;

        }


        $zhUser = new ZhUser();

        $zhUser->setZuUsername($res->user->username);
        $zhUser->setZuToken($res->access_token);
        $zhUser->setZuUserfrom('Instagram');
        $zhUser->setZuUserId($res->user->id);
        $zhUser->setZuAvatar($res->user->profile_picture);
        $result = $zhUser->save()->getResult();
        if($result){
            // 登录成功
            file_put_contents($file.'/facebook.log', "新用户登录成功处理\n\n",FILE_APPEND);

            $this->ret['code'] = 200;
            $this->ret['msg'] = '登录成功';
            $this->ret['success'] = true;
            $this->ret['item'] = $zhUser;

            //用户信息存session
            file_put_contents($file.'/facebook.log', "旧用户登录成功处理\n\n".json_encode($result),FILE_APPEND);
            session()->put(['userData' => $zhUser]);
            return $this->ret;

            // 跳转完善信息

        }

        $this->ret['code'] = 500;
        $this->ret['msg'] = '登录失败';
        return $this->ret;

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
     * Facebook登录之 ~~~~ 获取跳转链接
     * @RequestMapping(route="fblogin")
     * @return Response
     */
    public function getFbLoginUrl(Request $request)
    {
        session()->start();

        $url = $this -> url;
        $helper = $this->fb->getRedirectLoginHelper();

        $facebookLoginUrl = $helper->getLoginUrl($url['url'], ['email','user_photos', 'user_gender', 'user_location']);

        return $facebookLoginUrl;
//        return view('index/fbjs', ['url'=> $facebookLoginUrl]);

    }


    /**
     * Facebook登录之 ~~~ 登录成功、获取用户信息
     * @RequestMapping(route="loginok", method={RequestMethod::GET, RequestMethod::POST})
     * @throws \Facebook\Exceptions\FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function loginOk(Request $request)
    {
        session()->start();
        session()->put(['fb_code'=> $request->query()]);

        $facebookConfig = $this -> fbConfig; //Facebook配置信息

        $helper = $this->fb->getRedirectLoginHelper();

        if(!empty($request->input('state'))){
            session()->put(['FBRLH_state'=> $request->input('state')]);
            session()->put(['state'=> $request->input('state')]);
        }

        //自定义日志目录：runtime/logs
        $file = \Swoft::getAlias('@runtime');
        if( !file_exists($file) ){
            $mask = @umask(0);
            $result = @mkdir($file, 0777, true);
            @umask($mask);
        }

        file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '1、facebook配置信息~~~~~~~~~'.json_encode($facebookConfig,320). "\n\n",FILE_APPEND);

        //获取token
        try {
            $url = $request -> fullUrl();
            $accessToken = $helper->getAccessToken($url);
            file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '2、获取的FB-token：~~~~'.(string) $accessToken. "\n\n",FILE_APPEND);

        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $message = 'Graph returned an error~~~~~~: ' . $e->getMessage();
//            Log::error('facebook login failed',['error'=>$message]);//Log的命名空间没加,写的时候自己加一下
            file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '3、'.$message. "\n\n",FILE_APPEND);

        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $message = 'Facebook SDK returned an error~~~~~~~~: ' . $e->getMessage();
//            Log::error('facebook login failed',['error'=>$message]);
            file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '4、获取token报错~~~~~~'.$message. "\n\n",FILE_APPEND);
        }

        //验证token
        if (! isset($accessToken)) {
            if ($helper->getError()) {
                $message = "Error~~~: " . $helper->getError() . "\n";
                $message .= "Error Code~~~~: " . $helper->getErrorCode() . "\n";
                $message .= "Error Reason~~~~~: " . $helper->getErrorReason() . "\n";
                $message .= "Error Description~~~~~~: " . $helper->getErrorDescription() . "\n";
            } else {
                $message = 'Bad request';
            }
//            Log::warning('facebook login failed',['error'=>$message]);
            file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '5、验证token有无报错~~~~~~'.$message. "\n\n",FILE_APPEND);

        }else{
            //验证token通过，开始获取用户信息
            $oAuth2Client = $this->fb->getOAuth2Client();
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);
            $tokenMetadata->validateAppId($facebookConfig['app_id']);
            $tokenMetadata->validateExpiration();

            // 有的用户第一次登陆，token验证期只有2个小时， 可以将其延长至60天
            if (! $accessToken->isLongLived()) {
                try {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                    $message = 'Error getting long-lived access token~~~~~'. $e->getMessage();
//                Log::warning('facebook login failed~~~~~~',['error'=>$message]);
                    file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '6、延长token报错~~~~~~'.$message. "\n\n",FILE_APPEND);
                }
            }

            //将token存入session，方便Graph SDK获取用户信息
            $_SESSION['fb_access_token'] = (string) $accessToken;
            $this->fb->setDefaultAccessToken($accessToken);

            //获取用户信息： 用户ID，姓名，邮箱，头像，性别，当前所在地
            try {
                $response = $this->fb ->get('/me?fields=id,name,email,picture, gender, location{location}');
                $responsePic = $this->fb ->get('/me/picture?redirect=false'); //获取头像
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                $message = 'Graph returned an error~~~~: ' . $e->getMessage();
                file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '7、获取用户信息报错~~~~~'.$message. "\n\n",FILE_APPEND);

            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                $message = 'Facebook SDK returned an error~~~~~: ' . $e->getMessage();
                file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '7、获取用户信息报错~~~~~'.$message. "\n\n",FILE_APPEND);

            }

            $userArr = $response -> getGraphNode() -> asArray();//用户ID，姓名，邮箱，性别，当前所在地
            $pic = $responsePic->getGraphObject()->asArray();//头像
            if(!$userArr){
                $message = "facebook_api_error~~~~~~:: user is null";
                file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '8、用户详情报错~~~~~'.$message. "\n\n",FILE_APPEND);
            }

            $user_data['zu_authkey'] = (string) $accessToken;
            $user_data['zu_avatar'] = isset($pic['url']) ? $pic['url']:'';
            $user_data['zu_userfrom'] = 'facebook';
            $user_data['zu_userId'] = isset($userArr['id']) ? $userArr['id']:'';
            $user_data['zu_username'] = isset($userArr['name']) ? $userArr['name'] : '';
            $user_data['zu_email'] = isset($userArr['email']) ? $userArr['email']: '';
            $user_data['zu_sex'] = isset($userArr['gender'])? ( ($userArr['gender'] == 'male')? 1 : 0 ) : '';
            $user_data['zu_country'] = isset($userArr['location']['location']['country']) ? $userArr['location']['location']['country'] :'';
            $user_data['zu_status'] = 1;
            $user_data['zu_regtime'] = time();

            file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '9、获取的fb用户信息~~~~~~~~~'.json_encode($user_data,320). "\n\n",FILE_APPEND);

            //判断用户是否已存
            $res = Db::query('SELECT zu_id FROM zh_user WHERE zu_userId = '.$userArr['id'])->getResult();

            file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '10、判断用户是否已存$res=~~~~~~~~~'.json_encode($res,320). "\n\n",FILE_APPEND);

            if(!$res){
                //新用户入库
                $result = Query::table('zh_user')->insert($user_data)->getResult();
                $sql = get_last_sql();
                file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '11、新用户入库的SQL语句：~~~~~~~~~'.$sql. "\n\n",FILE_APPEND);
                file_put_contents($file.'/logs/facebook'.date('Ymd').'.log', '12、新用户入库结果$result=~~~~~~~~~'.$result. "\n\n",FILE_APPEND);

                //用户信息存session
                $user_data['zu_id'] = $result; //新增用户ID
                session()->put(['userData' => $user_data]);
                session()-> put(['uid' => md5($user_data['zu_username'])]);

                //重定向到 用户中心  ->withHeader('userName', $user_data['zu_username'])
//            return response()->redirect('http://www.baidu.com');
                return response()->withCookie(new Cookie('userName', $user_data['zu_username'], time()+3600,'/', 'http://localhost:8080'))
                                ->withCookie(new Cookie('userPic', $user_data['zu_avatar'], time()+3600,'/', 'http://localhost:8080'))
                                ->withCookie(new Cookie('userId', $user_data['zu_id'], time()+3600,'/', 'http://localhost:8080'))
                                ->redirect('http://localhost:8080/index_1');

            }else{
                //用户信息存session
                $user_data['zu_id'] = $res[0]['zu_id']; //数据库已存的 用户ID
                session()->put(['userData' => $user_data]);
                session()-> put(['uid' => md5($user_data['zu_username'])]);

                //旧用户 重定向到 登录页面
//            return response()->redirect('http://www.baidu.com');
                return response() ->withCookie(new Cookie('userName', $user_data['zu_username'], time()+3600,'/', 'http://localhost:8080'))
                                ->withCookie(new Cookie('userPic', $user_data['zu_avatar'], time()+3600,'/', 'http://localhost:8080'))
                                ->withCookie(new Cookie('userId', $user_data['zu_id'], time()+3600,'/', 'http://localhost:8080'))
                                ->redirect('http://localhost:8080/index_1');


            }

        }



    }

    /**
     * 邮箱注册
     * @RequestMapping(route="mailreg", method={RequestMethod::POST})
     * 接收参数： userName, email, password
     * return $this->ret  Array
     */
    public function mailRegister(Request $request, Response $response)
    {
        $file = \Swoft::getAlias('@runtime');
        if( !file_exists($file) ){
            $mask = @umask(0);
            $result = @mkdir($file, 0777, true);
            @umask($mask);
        }

        //接收用户信息
        $data = $request -> input(); //数组格式 userName, email, password
        file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '1、用户提交的注册信息~~~~~~~~~'.json_encode($data,320). "\n\n",FILE_APPEND);
//        var_dump($data);

        //校验数据
        $rulers =[
//            'userName' => '/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u',
            'email' => '/\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}/',
            'password' =>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/'
        ];
        $errMsg = [
//            'userName' => '用户名由2-16位数字或字母、汉字、下划线组成！',
            'email' => '请输入正确的邮箱格式',
            'password' =>'密码格式不正确'
        ];
        $required = [
//            'userName' => '用户名为必填',
            'email' => '邮箱为必填',
            'password' =>'密码为必填'
        ];

        foreach($rulers as $key => $rule){
            $user = isset($data[$key])? isset($data[$key]): false;
            if(!$user){
                $this -> ret['msg'] = $required[$key];
                file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '2、用户注册信息必填校验~~~~~~~~~'.$required[$key]. "\n\n",FILE_APPEND);
                return $this -> ret;
            }
            if(!preg_match($rule, $data[$key])){
                $this ->ret['msg'] = $errMsg[$key];
                file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '3、用户注册信息格式校验~~~~~~~~~'.$errMsg[$key]. "\n\n",FILE_APPEND);
                return $this -> ret;
            }

        }

        //数据校验通过， 1、先判断邮箱是否存在
        $is_register = Db::query("SELECT zu_id, zu_userfrom FROM zh_user WHERE zu_email = "."'$data[email]'")->getResult();
//        $sql = get_last_sql();
        file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '4、判断邮箱是否存在~~~~~~~~~'.json_encode($is_register,320). "\n\n",FILE_APPEND);

        if($is_register){
            $acount = $is_register[0]['zu_userfrom'] == 1 ? '邮箱' : $is_register[0]['zu_userfrom'];
            $this -> ret['msg'] = '邮箱已被注册，请用您的'.$acount.'账号直接登录';
            file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '5、邮箱已存在~~~~~~~~~'.$acount. "\n\n",FILE_APPEND);
            return $this -> ret;
        }

        //新用户注册
        $psw = crypt(md5($data['password']), 'mall'); //密码加密方法 abc12345
        $regTime = time();
        $token = md5($data['userName'].$data['email'].$regTime); //邮箱验证码
        $expire = time()+60*60*24; //验证码有效期24个小时

        //组装数据 入库
        $userData['zu_username'] = $data['userName'];
        $userData['zu_password'] = $psw;
        $userData['zu_token'] = $token;
        $userData['zu_token_time'] = $expire;
        $userData['zu_email'] = $data['email'];
        $userData['zu_country'] = env('AREA_ZONE');
        $userData['zu_regtime'] = $regTime;
        $userData['zu_userfrom'] = '1';
        file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '6、新用户注册入库的信息~~~~~~~~~'.json_encode($userData,320). "\n\n",FILE_APPEND);

        //入库
        $result = Query::table('zh_user')->insert($userData)->getResult();
//        return $result;

        if(!$result){
            //入库失败
            file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '7、新用户注册入库失败原因~~~~~~~~~'.json_encode($result,320). "\n\n",FILE_APPEND);
            $this -> ret['msg'] = '注册数据入库失败，请联系客服~~~~';
            return $this -> ret;
        }else{
            //入库成功， 调用发邮件服务，创建Redis邮件队列
            $uri = $request->getUri(); //PSR-7 URI对象
            $validUrl = $uri ->getHost().'/user/validate?token='.$token; //激活邮箱跳转URL
            $d = ['email' => $data['email'], 'userName'=> $data['userName'], 'api'=> '/srilanka/purchase', 'content' => $validUrl];
            file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '8、新用户注册入库成功，创建Redis队列的数据~~~~~~~~~'.json_encode($d,320). "\n\n",FILE_APPEND);

            $send = $this -> createQue($d);
            if(!$send['success']){
                //创建Redis邮件队列失败
                file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '9、注册用户创建Redis队列失败原因~~~~~~~~~'.$send['msg']. "\n\n",FILE_APPEND);
                $this -> ret['success'] = 2;
                $this -> ret['msg'] = '注册成功， 但是发送激活邮件失败，请登录账号重新发送~~~';
                return $this -> ret;
            }
            //创建成功， 指引用户去激活账号
            $this -> ret['success'] = 1;
            $this -> ret['msg'] = '耶~~~ 注册成功,请去邮箱激活账号~~~';
            file_put_contents($file.'/logs/mailRegister'.date('Ymd').'.log', '10、注册用户创建Redis队列成功~~~~~~~~~'. "\n\n",FILE_APPEND);
            return $this -> ret;
        }

    }

    /**
     * 邮箱登录
     * @RequestMapping(route="loginmail", method={RequestMethod::POST})
     * 接收参数： email, password
     * return $this->ret Array
     */
    public function loginByEmail(Request $request)
    {
        $file = \Swoft::getAlias('@runtime');
        if( !file_exists($file) ){
            $mask = @umask(0);
            $result = @mkdir($file, 0777, true);
            @umask($mask);
        }

        $data = $request -> input();
        file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '1、邮箱登录，用户传递的数据~~~~~~~~~'.json_encode($data,320). "\n\n",FILE_APPEND);

        $email = isset($data['email']) ? $data['email'] : false;
        $password = isset($data['password']) ? $data['password'] : false;
        if(!$email || !$password){
            $this -> ret['msg'] = '咦~~~参数缺失~~~~';
            $this -> ret['success'] = false;
            return $this -> ret; exit();
        }

        //密码加密
        $psw = crypt(md5($password), 'mall');

        $pass = Db::query("SELECT * FROM zh_user WHERE zu_email ='".$email."' AND zu_userfrom= '1'")->getResult();
//        $sql=get_last_sql(); return $sql;
        file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '2、邮箱登录，查询是否有此用户~~~~~~~~~'.json_encode($pass,320). "\n\n",FILE_APPEND);

        if(!$pass){
            //查无此用户
            $this -> ret['msg'] = '咦~~~这个账号在我们这不存在的~~~';
            $this -> ret['success'] = false;
            file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '3、数据库查无用户~~~~~~~~~'. "\n\n",FILE_APPEND);
            return $this -> ret; exit();
        }
        //查有此用户，判断密码是否正确
        if($psw !== $pass[0]['zu_password']){
            //密码不正确
            $this -> ret['msg'] = '密码或用户名不正确~~~~';
            $this -> ret['success'] = 5;
            file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '3、密码不正确~~~~~~~~~'. "\n\n",FILE_APPEND);
            return $this -> ret; exit();
        }
        if($pass[0]['zu_status'] == 0){
            if(time()<= $pass[0]['zu_token_time']){
                //账号未激活，且还未过有效期，提示用户去邮箱激活账号
                $this -> ret['success'] = 3;
                $this -> ret['msg'] = '咦~~账号还未激活，请去您的邮箱激活此账号~~~';
                return $this -> ret; exit();
            }
            //邮箱未激活，且已过有效期， 调用邮件服务再次发送激活邮件
            $token = $pass[0]['zu_token'];
            $expire = time()+60*60*24;
            $res = Db::query("UPDATE zh_user SET zu_token_time =".$expire." WHERE zu_id=".$pass[0]['zu_id'])->getResult();
//            $sql = get_last_sql(); return $sql; die;
            file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '4、账号未激活，更新激活码有效期，并且准备再次发送激活邮件~~~~~~~~~'.json_encode($res,320). "\n\n",FILE_APPEND);

            $domain = $request -> getUri() -> getHost();
            $validUrl = $domain.'/user/validate?token='. $pass[0]['zu_token'];
            $d = ['email' => $pass[0]['zu_email'], 'userName'=> $pass[0]['zu_username'], 'api'=>'/srilanka/purchase', 'content' => $validUrl];
            file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '5、账号未激活，再次发送激活邮件的数据~~~~~~~~~'.json_encode($d,320). "\n\n",FILE_APPEND);

            $send = $this -> createQue($d);
            if(!$send['success']){
                //创建Redis邮件队列失败
                file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '6、再次创建Redis队列失败原因~~~~~~~~~'.$send['msg']. "\n\n",FILE_APPEND);
                $this -> ret['success'] = 4;
                $this -> ret['msg'] = '创建Redis邮件队列失败,请登录并重新获取激活邮件~~~';
                return $this -> ret;exit();
            }
            //创建Redis邮件队列成功, 提醒用户去激活账号
            $this -> ret['success'] = 1;
            $this -> ret['msg'] = '激活邮件已重新发送，请移步您的邮箱处理激活邮件~~~~';
            file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '7、账号未激活，并成功再次创建Redis队列，提示用户去激活账号~~~~~~~~~'. "\n\n",FILE_APPEND);
            return $this -> ret;exit();
        }
        //登录成功
        //用户信息存session
        session() -> put(['userDada' => $pass]);
        session() -> put(['uid' => md5($pass[0]['zu_username'])]);
        $this -> ret['success'] = 2;
        $this -> ret['userId'] = $pass[0]['zu_id']; //用户ID
        $this -> ret['userName'] = $pass[0]['zu_username']; //用户ID
        $this -> ret['msg'] = '耶~~~~ 登录成功，去玩吧~~~~';
        file_put_contents($file.'/logs/loginByEmail'.date('Ymd').'.log', '8、登录成功~~~~~~~~~'. "\n\n",FILE_APPEND);
        return $this -> ret;exit();

    }

    /*
     * 调用邮件服务，创建Redis邮件队列
     * @Param $input=['email' => $data['email'], 'userName'=> $data['userName'], 'content' => $validUrl];
     * return $this -> ret  Array
     */
    private function createQue($input)
    {
        $data = [
            'toMail'               =>  $input['email'],
            'api'                   =>  $input['api'],
            'toName'                =>  $input['userName'],
            'content'               =>  $input['content'],
            'msgTime'               =>  time(),
        ];

        $url = 'http://email.lhydejia.site/mq/create'; //邮件服务Redis队列创建路由
        $code = 'zehui.advert.website.com';
        $time = time();
        $str = 'ghs5dxd4a1xzd5fz4a';
        $sign = md5($str.json_encode($data).$code);

        $headr = [
            "X-PROJECT-ID:".$code,
            "X-AUTH-SIGNATURE:".$sign,
            "X-AUTH-TIMESTAMP:".$time,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//获取的信息以文件流的形式返回
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//SSL验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($ch, CURLOPT_URL, $url); //要访问的地址
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //带参数
        $result = (array)json_decode(curl_exec($ch));
        if ($error = curl_error($ch)) {
            //CURL请求失败
            $this -> ret['msg'] = $error;
            curl_close($ch);// 关闭CURL会话
            return $this -> ret;

        }
        if(!$result['success']){
            $this -> ret['msg'] = $result['msg'];
            curl_close($ch);// 关闭CURL会话
            return $this -> ret;
        }
       //redis队列创建成功
        curl_close($ch);// 关闭CURL会话
        $this -> ret['success'] = true;
        $this -> ret['msg'] = 'Redis队列创建成功';
        return $this -> ret;

    }

    /**
     * 处理用户 点击激活邮件的请求
     * @RequestMapping(route="validate", method={RequestMethod::GET})
     * return
     */
    public function validate(Request $request)
    {
        $file = \Swoft::getAlias('@runtime');
        if( !file_exists($file) ){
            $mask = @umask(0);
            $result = @mkdir($file, 0777, true);
            @umask($mask);
        }

        $token = $request -> input('token'); //激活邮件接收的token
        file_put_contents($file.'/logs/validate'.date('Ymd').'.log', '1、接收用户传递的激活码~~~~~~~~~'.$token. "\n\n",FILE_APPEND);

        if(empty($token)){
            //接收的token为空
            $this -> ret['msg'] = '无token， 非法操作';
            file_put_contents($file.'/logs/validate'.date('Ymd').'.log', '2、无token， 非法操作~~~~~~~~~'. "\n\n",FILE_APPEND);
            return response() -> redirect('/login');
//            return $this -> ret;
        }
        //查询用户token的有效期
        $tokenTime = Db::query("SELECT zu_token_time FROM zh_user WHERE zu_token ="."'$token'")->getResult();
        file_put_contents($file.'/logs/validate'.date('Ymd').'.log', '3、查询用户token的有效期~~~~~~~~~'.json_encode($tokenTime, 320). "\n\n",FILE_APPEND);
        //判断是否还可以激活用户
        if($tokenTime){
            $curTime = time();
            if( $curTime> $tokenTime[0]['zu_token_time']){
                file_put_contents($file.'/logs/validate'.date('Ymd').'.log', '4、当前时间和激活码有效期比较~~~~已过期，提示重新登录账号发送激活邮件~~~~~~~~~'. "\n\n",FILE_APPEND);
                //已过有效期
                $this -> ret['msg'] = '已过激活有效期，请重新登录账号发送激活邮件';
                return response() -> redirect('/user/loginmail');
//                return $this -> ret;
            }
            //在有效期内， 改变用户状态
            $status = Db::query("UPDATE zh_user SET zu_status=1 WHERE zu_token="."'$token'")-> getResult();
            if(!$status){
                $sql = get_last_sql();
                file_put_contents($file.'/logs/validate'.date('Ymd').'.log', '5、在激活有效期内，但是更新用户状态失败，SQL语句~~~~~~~~~'.$sql. "\n\n",FILE_APPEND);
                $this -> ret['msg']= '改变状态失败';
                return response() -> redirect('/login');
//                return $this -> ret;
            }
            file_put_contents($file.'/logs/validate'.date('Ymd').'.log', '6、成功激活账号~~~~~~~~'. "\n\n",FILE_APPEND);
            $this -> ret['msg'] = '成功激活';
            $this -> ret['success'] = true;
            return response() -> redirect('/login');
//            return $this ->ret;

            }

    }


    /**
     * 用户找回密码之 ~~~ 请求授权邮件
     * @RequestMapping(route="resetpass", method={RequestMethod::POST})
     *
     */
    public function resetPass(Request $request)
    {
        $mail = $request -> input('email'); //要修改密码的邮箱
        if(empty($mail)){
            $this -> ret['msg'] = '参数缺失~~~';
            return $this -> ret;exit();
        }

        //查询用户信息
        $userInfo = Db::query("SELECT * FROM zh_user WHERE zu_email='" .$mail."' AND zu_userfrom=1")-> getResult();
//        return $userInfo;
        if(!$userInfo){
            //查无用户信息
            $this -> ret['msg'] = '邮箱不存在~~~';
            return $this -> ret; exit();
        }
        $key = md5($userInfo[0]['zu_email'].$userInfo[0]['zu_password']); //用户邮箱+密码md5加密字符串
        $str = base64_encode($userInfo[0]['zu_id'].'+'.$key);//用户ID+md5加密字符串
        $domain = $request -> getUri() -> getHost();
        $url = $domain.'/user/findpass?token='. $str;//重置密码邮件的跳转URL
        $d = ['email' => $userInfo[0]['zu_email'], 'userName'=> $userInfo[0]['zu_username'], 'api'=> '/srilanka/resetpass', 'content' => $url];
        $res = $this -> createQue($d);  //创建Redis邮件队列
        if(!$res['success']){
            $this -> ret['success'] = 1;
            $this -> ret['msg'] = '发送重置密码授权邮件失败，请稍后重试~~~';
            return $this -> ret;exit();
        }
        //Redis邮件队列创建成功
        $time = time();
        $valTime = $time + 60*60*24;
        $tokenTime = Db::query("UPDATE zh_user SET zu_token_time= ".$valTime." WHERE zu_email='" .$mail."' AND zu_userfrom=1")->getResult(); //更新授权邮件有效期24个小时
        $this -> ret['success'] = 2;
        $this -> ret['msg'] = '重置密码授权邮件已发送，请移步您的邮箱处理~~~~';
        return $this -> ret;exit();

    }

    /** 用户找回密码之~~ 处理邮件内容里的 URL
     * @RequestMapping(route="findpass", method={RequestMethod::GET})
     */
    public function readyForResetPass(Request $request)
    {
        $str = $request -> input('token');
        if(empty($str)){
//            $this -> ret['msg'] = '参数缺失';
//            return $this -> ret;
            return response() ->withCookie(new Cookie('errCode', 1, time()+3600,'/', 'http://localhost:8080'))
                          ->redirect('http://localhost:8080/findPassword');
        }
        $decode = base64_decode($str);
        $arr = explode('+', $decode); //用户ID+md5加密字符串

        //查询用户信息
        $userInfo = Db::query("SELECT zu_password, zu_email, zu_token_time FROM zh_user WHERE zu_id= ".$arr[0]) ->getResult();
        if(!$userInfo){
//            $this -> ret['msg'] = '传参错误~~~';
//            return $this -> ret;
            return response() ->withCookie(new Cookie('errCode', 1, time()+3600,'/', 'http://localhost:8080'))
                ->redirect('http://localhost:8080/findPassword');
        }
        $key = md5($userInfo[0]['zu_password']. $userInfo[0]['zu_email']);
        if($key != $arr[1]){
//            $this -> ret['msg'] = '传参错误2~~~~~';
//            return $this -> ret;
            return response() ->withCookie(new Cookie('errCode', 1, time()+3600,'/', 'http://localhost:8080'))
                ->redirect('http://localhost:8080/findPassword');
        }
        $time = time();
        if($time > $userInfo[0]['zu_token_time']){
//            $this -> ret['msg'] = '链接已过有效期，请重新发送请求~~~~';
//            return $this -> ret;
            return response() ->withCookie(new Cookie('errCode', 2, time()+3600,'/', 'http://localhost:8080'))
                ->redirect('http://localhost:8080/findPassword');
        }
        //在有效期内，且$key验证通过， ==》授权OK，跳转至写新密码页面
        return response() ->withCookie(new Cookie('passId', $arr[0], time()+600,'/', 'http://localhost:8080'))
                          ->redirect('http://localhost:8080/findPassword');

    }

    /**
     * 用户找回密码之~~~ 接收新密码
     * @RequestMapping(route="newpass", method={RequestMethod::POST})
     */
    public function newPass(Request $request)
    {
        //接收新密码、用户ID
        $pass = $request -> input();
        if(!isset($pass['newPass']) || !isset($pass['userId'])){
            $this -> ret['msg']= '参数缺失~~';
            return $this -> ret;exit();
        }
        if(!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,16}$/', $pass['newPass'])){
            $this -> ret['msg'] = '密码格式不正确~~~';
            return $this -> ret;exit();
        }

        //新密码入库
        $psw = crypt(md5($pass['newPass']), 'mall');
        $res = Db::query("UPDATE zh_user SET zu_password= '". $psw."' WHERE zu_id=".$pass['userId'])->getResult();
        if(!$res){
            $this -> ret['msg'] = '密码修改失败， 请重试~~~';
            return $this -> ret;exit();
        }
        //修改密码成功
        $this -> ret['success'] = true;
        $this -> ret['msg'] = '耶~~~修改密码成功~~~';
        return $this -> ret;exit();

    }


    /**
     * @RequestMapping(route="testhttp")
     * @return \Psr\Http\Message\ResponseInterface
     *
     */
    public function testHttp(Request $request)
    {
        $uri = $request->getUri();
        $sch = $uri -> getScheme();
        $host = $uri -> getHost();
        $port = $uri -> getPort();
        return $uri. "\n\n". $sch. "\n\n".$host."\n\n".$port;

    }



}