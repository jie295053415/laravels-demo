<?php


namespace App\Services;


use App\Table\UserTable;
use App\Utils\WebsocketTrait;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;

class WebSocketService implements WebSocketHandlerInterface
{
    use WebsocketTrait;

    /**
     * @var  UserTable $wsTable
     */
    protected $userTable;

    public function __construct()
    {
        $this->userTable = new UserTable();
    }
}
