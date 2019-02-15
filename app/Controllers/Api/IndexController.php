<?php
namespace App\Controllers\Api;

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
 * @Controller("/indexapi")
 * @Middlewares({
 *     @Middleware(BaseMiddleware::class),
 *     @Middleware(InterfaceMiddleware::class)
 * })
 */
class IndexController
{

    /**
     * @Inject("zhRedis")
     * @var \Swoft\Redis\Redis
     */
    private $zhRedis;

    /**
     * 获取首页数据
     * @RequestMapping(route="index", method={RequestMethod::GET, RequestMethod::POST})
     * @return array
     */
    public function getIndexData()
    {
        $ret = (new publicReturn())->getReturn();
        $data = [];

        // header
        $data['header'] = [];

        // banner
        $data['banner'] = [];

        // list
        $data['list'] = [];

        $ret['item'] = $data;
        return $ret;
    }


    /**
     * 获取列表数据
     * @RequestMapping("list", method={RequestMethod::GET, RequestMethod::POST})
     * @return array
     */
    public function getListData(Request $request)
    {
        $ret = (new publicReturn())->getReturn();
        $data = ['list xxxxxxxxx'];

        $ret['item'] = $data;
        return $ret;
    }


    /**
     * 获取产品详情页数据
     * @RequestMapping("details", method={RequestMethod::GET, RequestMethod::POST})
     * @return array
     */
    public function getDetailsData(Request $request)
    {
        $ret = (new publicReturn())->getReturn();
        $data = ['details xxxxxxxxx'];

        $ret['item'] = $data;
        return $ret;
    }


}
?>