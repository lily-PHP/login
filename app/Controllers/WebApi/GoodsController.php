<?php

/**
 * @Created by PhpStorm.
 * @author chenli <491126240@qq.com>
 * @Date: 2019/3/1
 * @Time: 14:54
 */

namespace App\Controllers\WebApi;

use App\Commands\Tool;
use App\Models\Entity\ZhProduct;
use App\Models\Entity\ZhSearchNamerica;
use App\SiteModel\syncData\syncIndexCache;
use Swoft\Bean\Annotation\Inject;
use Swoft\Db\Query;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * Goods
 *
 * @Controller(prefix="/api/goods")
 */
class GoodsController
{

    /**
     * @Inject()
     * @var \Swoft\Redis\Redis
     */
    private $redis;

    /**
     * 评论表地区标志
     * @var mixed
     */
    private $area = '';

    public function __construct()
    {
        $this->area = env('APP_AREA');
    }


    /**
     * 查询列表接口
     * 地址:/list.html.
     * @param Request $request
     * @RequestMapping(route="/list.html", method={RequestMethod::GET})
     * @return array
     */
    public function list(Request $request)
    {
        $where = [];
        $query = $request->query();

        $page = $query['page']?:1;
        $limit =  ['limit']?:50;

        $query['zs_attribute_a'] && $where[]['zs_attribute_a'] = $query['zs_attribute_a'];
        $query['zs_attribute_b'] && $where[]['zs_attribute_b'] =  $query['zs_attribute_b'];
        $query['zs_attribute_c'] && $where[]['zs_attribute_c'] = $query['zs_attribute_c'];

        $order = [
            'zs_evaluate' => $query['zs_evaluate']?:'desc',
            'zs_salesvolume' => $query['zs_salesvolume']?:'desc',
            'zs_hot' => $query['zs_hot']?:'desc',
            'zs_time' => $query['zs_time']?:'desc',
        ];


        // 查询结果列表
        $ret = ZhSearchNamerica::findAll(
            $where,
            [
                'fields' => ['zs_product_id'],
                'orderby' => $order,
                'offset' => ($page - 1) * $limit,
                'limit' => $limit

            ]
        )->getResult();

        // 总产品数量
        $count =  ZhSearchNamerica::count()->getResult();

        $list = [];
        // redis 中获取数据产品数量列表
        foreach ($ret as $v) {
            if ($this->redis->has($v['zsProductId'])){
                $list[] = $this->redis->hGetAll($v['zsProductId']);
            }
        }

        return [$count,$list];

    }


    /**
     * 查询一个商品信息
     * 地址:/{gid}.html.
     * @RequestMapping(route="/{gid}.html", method={RequestMethod::GET})
     * @param int $gid
     * @return mixed
     * @throws \Swoft\Db\Exception\DbException
     */
    public function getProduct(int $gid)
    {

        // 分类数据
        $category = $this->redis->hGet('indexData', 'nav_classify');

        // 不做缓存
        /*$key = 'product_info_'.$gid;
        // 从缓存中先查询详情数据
        if($this->redis->has($key)){
            $info = $this->redis->get($key);
            return ['goods'=>unserialize($info),'category'=>$category];
        }*/

        // 缓存不存在时候 查询数据库
        $ret = Query::table(ZhProduct::class)
            ->leftJoin('zh_product_sku','zps_advertId = zp_id')
            ->leftJoin('zh_product_image','zpi_zp_id = zp_id')
            ->where('zp_id',$gid)->one()->getResult();
        // 更新缓存
        //$this->redis->set($key,serialize($ret));

        return ['goods'=>$ret,'category'=>$category];

    }


    /**
     * 查询最新产品数据列表
     * 地址:/new.html.
     * @RequestMapping(route="/new.html", method={RequestMethod::GET})
     * @return mixed
     */
    public function new(){
        // redis list中维护20个最新产品数据，详情页根据需求取出5个
        return $this->redis->lRange('product_new',0,5);
    }

    /**
     * 查询产品评论
     * 地址:/review.html.
     * @RequestMapping(route="/review.html", method={RequestMethod::GET})
     * @param Request $request
     * @return array|mixed
     */
    public function review(Request $request){
        $pid = $request->query('pid');
        $ret = Query::table('zh_product_evaluate'.($this->area?'_'.$this->area:''))
            ->where('zpe_zp_id',$pid)->where('zpe_status',0)->get()->getResult();
        
        // 评论数据树形结构
        $ret = Tool::tree($ret,'zpe_id','zpe_pid');
        return $ret;
    }



}
