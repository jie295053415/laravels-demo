<?php


namespace App\Events;
use Hhxsv5\LaravelS\Swoole\Events\ServerStartInterface;
use Swoole\Atomic;
use Swoole\Http\Server;
class ServerStartEvent implements ServerStartInterface
{
    public function __construct()
    {
    }
    public function handle(Server $server)
    {
        // 初始化一个全局计数器(跨进程的可用)
        $server->atomicCount = new Atomic(2233);

        // 控制器中调用：app('swoole')->atomicCount->get();
    }
}
