<?php


namespace App\Tasks;


use Hhxsv5\LaravelS\Swoole\Task\Task;
use Illuminate\Support\Facades\Log;

class TestTask extends Task
{
    private $data;

    private $result;

    public function __construct($data)
    {
        $this->data = $data;
    }

    // 处理任务的逻辑，运行在Task进程中，不能投递任务
    public function handle(): void
    {
        Log::info(__METHOD__, ['handle start']);

        sleep(2);// 模拟一些慢速的事件处理
        // throw new \Exception('an exception');// handle时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
        $this->result = 'the result of ' . $this->data;
    }

    // 可选的，完成事件，任务处理完后的逻辑，运行在Worker进程中，可以投递任务
    public function finish(): void
    {
        Log::info(__METHOD__, ['handle finish']);
//        Task::deliver(new TestTask2('task2')); // 投递其他任务
    }
}
