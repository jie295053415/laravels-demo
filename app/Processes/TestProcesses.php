<?php


namespace App\Processes;


use App\Models\User;
use Hhxsv5\LaravelS\Swoole\Process\CustomProcessInterface;
use Swoole\Http\Server;
use Swoole\Process;

class TestProcesses implements CustomProcessInterface
{
    /**
     * @var bool 退出标记，用于Reload更新
     */
    private static $quit = false;

    public static function callback(Server $server, Process $process): void
    {
        // 进程运行的代码，不能退出，一旦退出Manager进程会自动再次创建该进程。
        while (!self::$quit) {
//            (new User)->handleProcess(); // 直接用模型
            self::handleProcess(); // 用当前类静态方法
        }
    }

    // 要求：LaravelS >= v3.4.0 并且 callback() 必须是异步非阻塞程序。
    public static function onReload(Server $server, Process $process): void
    {
        self::$quit = true;
    }

    public static function handleProcess(): void
    {
        (new User)->handleProcess();
    }
}
