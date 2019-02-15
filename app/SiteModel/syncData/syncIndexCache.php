<?php
namespace App\SiteModel\syncData;

use Swoft\App;
use App\SiteModel\syncData\baseSyncModel;


/**
 * 同步首页/活动页
 * 特点:更新时间间隔低于10秒
 * sync Index Cache
 *
 */
class syncIndexCache extends baseSyncModel
{
    public function demo1()
    {
        $this->demo();
        echo "syncIndexCache demo1 \n";
    }
}