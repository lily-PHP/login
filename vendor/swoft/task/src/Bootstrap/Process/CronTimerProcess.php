<?php

namespace Swoft\Task\Bootstrap\Process;

use Swoft\App;
use Swoft\Process\Bean\Annotation\Process;
use Swoft\Process\Process as SwoftProcess;
use Swoft\Process\ProcessInterface;

/**
 * Crontab timer process
 *
 * @Process(name="cronTimer", boot=true)
 */
class CronTimerProcess implements ProcessInterface
{
    /**
     * @param \Swoft\Process\Process $process
     */
    public function run(SwoftProcess $process)
    {
        $pname = App::$server->getPname();
        $process->name(sprintf('%s crontimer process', $pname));

        /* @var \Swoft\Task\Crontab\Crontab $cron*/
        $cron = App::getBean('crontab');

        // Swoole/HttpServer
        $server = App::$server->getServer();

        echo "准备进入定时任务投递\n";
        $time = (60 - date('s')) * 1000;
        $server->after(1000, function () use ($server, $cron) {
            // Every minute check all tasks, and prepare the tasks that next execution point needs
            $cron->checkTask();
            /* 定时任务开启 */
            echo "已进入定时任务投递\n";
            $server->tick(1 * 1000, function () use ($cron) {
                $cron->checkTask();
            });
        });
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        $serverSetting = App::$server->getServerSetting();
        $cronable = (int)$serverSetting['cronable'];
        if ($cronable !== 1) {
            return false;
        }
        return true;
    }
}
