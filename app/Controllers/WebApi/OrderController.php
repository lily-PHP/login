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
use App\Models\Entity\ZhUserAddress;
use App\Models\Entity\ZhProductSku;

/**
 * @Controller("/order")
 * @Middleware(BaseMiddleware::class)
 */
class OrderController
{
    /**
     * @Inject("zhRedis")
     * @var \Swoft\Redis\Redis
     */
    private $zhRedis;


    /**
     * 当前产品所有数据
     */
    private $product = null;


    /**
     * 创建订单接口
     */
    public function createOrderCache(Request $request)
    {
        // 获取传入的数据
//        $order = $request->input('order');
        $order = [
            "user" => [
                "username"=> "叶凡立",
                "telphone"=> "17704050771",
                "email"=> "ohyevr@163.com",
                "postcode"=> "518000",
                "aid" => 25,
                "country"=> "",
                "province"=> "",
                "city"=> "",
                "area"=> "",
                "address"=> ""
            ],
            "order"=>[
                "itemNum"=> 3,
                "totalPrice"=> 399.5,
                "remarks"=> "",
                "item"=>[
                    [
                        "sku"=> "abc123",
                        "number"=> 1,
                        "price"=> 399.5,
                        "discount"=> 0,
                        "coupon"=> 0
                    ],
                    [
                        "sku"=> "abc123",
                        "number"=> 1,
                        "price"=> 399.5,
                        "discount"=> 0,
                        "coupon"=> 0
                    ]
                ]
            ]
        ];

        $uid = session()->get('uid');

        // 验证地址是否存在
        if($this->validateAdderss($order['user']['aid'])){}

        // 验证下单频率
        if($this->validateFrequency($uid)){}

        $oList = $order['order']['item'];
        foreach ($oList as $key){
            // 验证sku有效性
            if($this->validateSku($key['sku'])){}

            // 分配配送区域、锁区
            if($this->validateShipAddress($key['sku'])){}

            // 验证折扣
            if($this->validateDiscount($key['discount'])){}

            // 验证优惠
            if($this->validateCoupon($key['coupon'])){}

            // 验证sku 价格
            if($this->validatePrice($key['price'])){}

            // 获取sku仓库
            if($this->getSkuWarehouse($key['sku'])){}
        }

        // 根据仓库不同，生成相应的子母单
        $createOrder = $this->createOrder();

        $ret = [];
        if($createOrder){
            $ret = $createOrder;
        }

        return $ret;
    }


    /**
     * 根据仓库不同生成子母单
     */
    private function createOrder(){
        return true;
    }


    /**
     * 寻址sku对应的仓库
     */
    private function getSkuWarehouse($wh)
    {
        return true;
    }


    /**
     * 验证下单频率
     */
    private function validateFrequency($freq)
    {
        return true;
    }


    /**
     * 验证订单的sku是否有效
     */
    private function validateSku($sku)
    {
        if((new ZhProductSku())->getZpsHashSku())return true;
        return null;
    }


    /**
     * 验证订单的价格是否正确
     */
    private function validatePrice($price)
    {
        return true;
    }


    /**
     * 验证折扣
     */
    private function validateDiscount($discount)
    {
        return true;
    }


    /**
     * 验证优惠
     */
    private function validateCoupon($coupon)
    {
        return true;
    }


    /**
     * 配送地址
     */
    private function validateAdderss($aid)
    {
        if(ZhUserAddress::findByIds($aid)->getResult())return true;
        return null;
    }


    /**
     * 验证物流配送模板
     */
    private function validateShipAddress($aid)
    {
        return true;
    }


    /**
     * 验证物流配送模板
     */
    private function validateShipTemplate()
    {
        return true;
    }
}