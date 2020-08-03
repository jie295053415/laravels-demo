<?php


namespace App\Listeners;


use App\Events\TestEvent;
use App\Tasks\TestTask1;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Hhxsv5\LaravelS\Swoole\Task\Listener;
use Illuminate\Support\Facades\Log;

class TestListener1 extends Listener
{
    /**
     * @var TestEvent
     */
    protected $event;

    public function handle(): void
    {
        sleep(2);// 模拟一些慢速的事件处理
        // 监听器中也可以投递Task，但不支持Task的finish()回调。
        // 注意：config/laravels.php中修改配置task_ipc_mode为1或2，参考 https://wiki.swoole.com/#/server/setting?id=task_ipc_mode
        $ret = Task::deliver(new TestTask1('task data'));

        Log::info(__METHOD__, ['result' => $ret, 'event data' => $this->event->getData()]);
        // throw new \Exception('an exception');// handle时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }
}
