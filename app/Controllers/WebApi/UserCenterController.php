<?php
namespace App\Controllers\WebApi;

use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Bean\Annotation\Middlewares;
use App\Middlewares\InterfaceMiddleware;
use App\Middlewares\BaseMiddleware;
use Swoft\Db\Db;
use Swoft\Bean\Annotation\Inject;
use Swoft\Cache\Cache;
use App\Controllers\publicReturn;


/**
 * @Controller("/user")
 * @Middleware(BaseMiddleware::class)
 *
 */
class UserCenterController
{



    /**
     * 用户中心数据
     * @RequestMapping(route="index", method={RequestMethod::GET, RequestMethod::POST})
     * @return array
     */
    public function getIndexData()
    {
        $ret = (new publicReturn())->getReturn();
        $data = [];

        // order
        $data['order'] = [];

        $ret['item'] = $data;
        return $ret;
    }
}