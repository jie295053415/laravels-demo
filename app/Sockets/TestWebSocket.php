<?php


namespace App\Sockets;


use App\Table\UserTable;
use App\Utils\WebsocketTrait;
use Hhxsv5\LaravelS\Swoole\Socket\WebSocket;
use Swoole\Server\Port;

/**
 * Class TestWebSocket
 * @package App\Sockets
 *
 * 这个类是多端口监听其中一个类, 主要是作用是两个端口可以这样通讯
 */
class TestWebSocket extends WebSocket
{
    use WebsocketTrait;

    /**
     * @var  UserTable $wsTable
     */
    protected $userTable;

    public function __construct(Port $port)
    {
        parent::__construct($port);

        $this->userTable = new UserTable();
    }


}
