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
//        require_once __DIR__ . 'vendor/autoload.php';
        $this -> fb = new \Facebook\Facebook([
            'app_id' => '562622214203764',
            'app_secret' => 'fe229c29e799664e6d72c105f5c3f77c',
            'default_graph_version' => 'v2.10',
        ]);
    }

    public function loginUrl($url)
    {
        $helper = $this->fb -> getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($url, $permissions);
        return $loginUrl;
    }



}