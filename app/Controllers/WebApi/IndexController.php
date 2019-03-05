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
use App\Controllers\WebApi\getIndexData;
use App\SiteModel\syncData\syncIndexCache;


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
        $data['header'] = (new syncIndexCache())->createFloorData();

        // banner
        $data['banner'] = [];

        // list
        $data['list'] = env('APP_AREA');

        // test
        $data['classify'] = (new syncIndexCache())->createClassifyData();

        $ret['item'] = $data;
        return $ret;
    }

    /**
     * 店铺所有分类，递归排序好，数据格式=> 所有一级，二级，三级分类在同层数组
     */
    public function getTree($category, $pid=0)
    {
        $list = [];
        foreach ($category as $cate) {
            if($cate && $cate['zc_pid'] == $pid){
                $cate['child'] = $this->getTree($category, $cate['zc_id']);
                $list[] = $cate;
            }
        }
        return $list;
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

        $request->input(); // 获取条件


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