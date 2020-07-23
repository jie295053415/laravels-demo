<?php


namespace App\Jobs\Timer;


use Hhxsv5\LaravelS\Swoole\Timer\CronJob;
use Illuminate\Support\Facades\Log;

class Time extends CronJob
{
    protected $i = 0;

    // --- 重载对应的方法来返回配置：开始
    public function interval()
    {
        return 1000 * 60;// 每60秒运行一次
    }

    public function isImmediate()
    {
        return true;// 是否立即执行第一次，false则等待间隔时间后执行第一次
    }

    // --- 重载对应的方法来返回配置：结束

    public function run()
    {
        Log::info('test time: ' . $this->i++);
    }
}
