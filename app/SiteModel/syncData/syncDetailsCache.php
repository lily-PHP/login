<?php
namespace App\SiteModel\syncData;

use Swoft\App;
use App\SiteModel\syncData\baseSyncModel;


/**
 * 同步产品详细数据
 * 特点:更新时间间隔低于10分钟
 * sync Index Cache
 *
 */
class syncDetailsCache extends baseSyncModel
{
    public function demo1()
    {
        $this->demo();
        echo "syncIndexCache demo1 \n";
    }
}