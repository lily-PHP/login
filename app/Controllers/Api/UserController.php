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
        session_start();
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
        return ok; 
//        session_start();
//        $data = $this -> fbController -> fbCallback();
//        return $data; die;

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
        //已获取用户信息， 可header跳转至 xx页面

    }

}