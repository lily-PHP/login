<?php
namespace App\SiteModel\syncData;

use Swoft\App;
use App\SiteModel\syncData\baseSyncModel;


/**
 * 同步订单数据
 * 特点:更新时间间隔低于1小时
 * sync Index Cache
 *
 */
class syncOrderCache extends baseSyncModel
{
    public function demo1()
    {
        $this->demo();
        echo "syncIndexCache demo1 \n";
    }
}