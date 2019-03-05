<?php
namespace App\SiteModel\syncData;

use Swoft\App;
use App\SiteModel\syncData\baseSyncModel;
use Swoft\Db\Db;
use Swoft\Bean\Annotation\Inject;
use Swoft\Cache\Cache;
use Swoft\Redis\Redis;


/**
 * 同步首页/活动页
 * 特点:更新时间间隔低于10秒
 * sync Index Cache
 *
 */
class syncIndexCache
{
    /**
     * 缓存
     */
    private $zhRedis;


    /**
     * 区域（国家、大洲）
     */
    private $area = false;


    /**
     * 平台ID zai .env中设置
     */
    private $platform = false;


    public function __construct()
    {
        /* @var Redis $cache */
        $this->zhRedis = \Swoft\App::getBean(Redis::class);

        $this->area = env('APP_AREA');
        $this->platform = env('APP_PLATFORM');
    }


    /**
     * 同步分类缓存
     */
    public function createClassifyData()
    {
        $msg = '未查询到分类数据';
        $category = Db::query('SELECT * FROM zh_classify WHERE zc_area="'.$this->area.'"')->getResult();
        //$classify = false;
        if($category){
            $classify = $this->getTree($category, 0);
        }

        $msg = '同步分类失败！';
        if($classify && $this->zhRedis->hSet('indexData', 'nav_classify', $classify)){
            $msg = '同步分类成功！';
        }
        return  $this->zhRedis->hGet('indexData', 'nav_classify');
    }


    /**
     * 同步首页楼层缓存
     */
    public function createFloorData()
    {
        $msg = '未查询到楼层数据';
        $floor = Db::query('SELECT * FROM zh_index WHERE zi_platform_id="'.$this->platform.'"')->getResult();

        if(!$floor){
            return  $msg = '同步分类失败！';
        }

        $fail = [];
        foreach ($floor as $k){
            if(!$this->zhRedis->hSet('indexData', $k['zi_hash_id'], $k)){
                $fail[] = $k['zi_hash_id'];
            };
        }
        if(count($fail) > 0){
            return $fail;
        }
        return  $msg = '同步楼层数据成功！';
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


    /*public function demo1()
    {
        $this->demo();
        echo "syncIndexCache demo1 \n";
    }*/


}