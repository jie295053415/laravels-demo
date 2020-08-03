<?php


namespace App\Utils;


use App\Models\User;
use App\Table\UserTable;
use Illuminate\Support\Facades\Log;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * @property UserTable userTable
 */
trait WebsocketTrait
{
    use MessageTrait;

    protected $userTable;

    /**
     * @param Server $server
     * @param $request
     */
    public function onOpen(Server $server, Request $request): void
    {
        $data = (new User())->getData();
        $names = $data['name'];
        $avatars = $data['avatar'];

        $fd = $request->fd;

        $user = [
            'fd'     => $fd,
            'name'   => $names[array_rand($names)] . '_' . $fd,
            'avatar' => $avatars[array_rand($avatars)],
        ];

        Log::info(__METHOD__, $user);

        $this->userTable->set($fd, $user);

        $server->push($fd, json_encode(['user' => $user, 'all' => $this->userTable->all(), 'type' => 'openSuccess']));

        $this->pushMessage($server, $fd, sprintf("欢迎%s进入聊天室", $user['name']), 'open');
    }

    /**
     * @param Server $server
     * @param $frame
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        Log::info(__METHOD__, [$frame->data, $frame->fd]);

        $this->pushMessage($server, $frame->fd, $frame->data, 'message');
    }

    /**
     * @param Server $server
     * @param $fd
     * @param $reactorId
     */
    public function onClose(Server $server, $fd, $reactorId): void
    {
        $user = $this->userTable->get($fd);

        Log::info(__METHOD__, [$fd, $user]);

        $this->pushMessage($server, $fd, sprintf("%s离开聊天室", $user['name']), 'close');

        $this->userTable->del($fd);
    }
}
