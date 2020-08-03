<?php


namespace App\Sockets;


use Hhxsv5\LaravelS\Swoole\Socket\UdpSocket;
use Illuminate\Support\Facades\Log;
use Swoole\Server;

class TestUdpSocket extends UdpSocket
{
    public function onPacket(Server $server, $data, array $clientInfo): void
    {
        Log::info(__METHOD__, [$data]);
        $server->sendto($clientInfo['address'], $clientInfo['port'], 'Server send data to: ' . $data);
    }
}
