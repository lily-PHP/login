<?php
namespace App\Controllers\AppApi;

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
 * @Middleware(BaseMiddleware::class)
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
     * 获取列表搜索信息
     * @RequestMapping("list", method={RequestMethod::GET, RequestMethod::POST})
     * @return array
     */
    public function getListQueryData(Request $request)
    {
        $ret = (new publicReturn())->getReturn();
        $data = ['list xxxxxxxxx'];
        $data['cookie'] = $request->cookie();

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
        $data['cookie'] = $request->cookie();

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
        $cookie = $request->cookie();
        $ret = (new publicReturn())->getReturn();
        $data = ['details xxxxxxxxx'];
        $data['session_id'] = $cookie['Z_S_ID'];
        $data['UserLg'] = $cookie['UserLg'] ? $cookie['UserLg'] : '暂时没有UserLg';
        $data['cookie'] = $cookie;

        $ret['item'] = $data;
        return $ret;
    }


    /**
     * 获取产品详情页数据
     * @RequestMapping("test", method={RequestMethod::GET, RequestMethod::POST})
     * @return array
     */
    public function testRedis(Request $request)
    {
        $result = $this->zhRedis->set('nameRedis', 'swoft2');
        $name   = $this->zhRedis->get('nameRedis');

        return [$result, $name, [
            $request->getUri()->getHost(),
            $request->getUri()->getPath(),
            $request->getUri()->getPort(),
            $request->getUri()->getScheme()
        ], $request->fullUrl()];
    }


}
?>