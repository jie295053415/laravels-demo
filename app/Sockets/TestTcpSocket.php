<?php


namespace App\Sockets;


use Hhxsv5\LaravelS\Swoole\Socket\TcpSocket;
use Illuminate\Support\Facades\Log;
use Swoole\Server;
use Swoole\Table;

class TestTcpSocket extends TcpSocket
{
    /** @var Table $laravelTable */
    protected $laravelTable;

    public function onConnect(Server $server, $fd, $reactorId): void
    {
        Log::info(__METHOD__, [$fd]);

        $this->laravelTable = app('swoole')->laravelTable;
        $server->send($fd, 'Welcome to LaravelS.' . PHP_EOL);
    }

    public function onReceive(Server $server, $fd, $reactorId, $data): void
    {
        Log::info(__METHOD__, [$fd, $data]);

        $server->send($fd, 'LaravelS: ' . $data . PHP_EOL);

        if ($data === "quit\r\n") {
            $server->send($fd, 'LaravelS: bye' . PHP_EOL);
            $server->close($fd);
        }
    }

    public function onClose(Server $server, $fd, $reactorId): void
    {
        Log::info(__METHOD__, [$fd]);

        $server->send($fd, 'Goodbye');
    }
}
