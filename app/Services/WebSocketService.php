<?php


namespace App\Services;


use App\Models\User;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Support\Facades\Log;
use Swoole\Http\Request;
use Swoole\Table;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class WebSocketService implements WebSocketHandlerInterface
{
    /**
     * @var $wsTable Table
     */
    private $wsTable;

    public function __construct()
    {
        $this->wsTable = app('swoole')->wsTable;
    }

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
        $this->wsTable->set($fd, $user);

        $msg = json_encode(['user' => $user, 'all' => $this->allUser(), 'type' => 'openSuccess']);
        $server->push($fd, $msg);
        $this->pushMessage($server, $fd, sprintf("欢迎%s进入聊天室", $user['name']), 'open');
    }

    private function allUser(): array
    {
        $users = [];
        foreach ($this->wsTable as $k => $row) {
            $users[] = $row;
        }
        return $users;
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
        $user = $this->wsTable->get($fd);
        Log::info(__METHOD__, [$fd, $user]);
        $this->pushMessage($server, $fd, sprintf("%s离开聊天室", $user['name']), 'close');
        $this->wsTable->del($fd);
    }

    /**
     * 遍历发送消息
     *
     * @param Server $server
     * @param $frameFd
     * @param $message
     * @param $messageType
     */
    private function pushMessage(Server $server, $frameFd, $message, $messageType): void
    {
        $message = htmlspecialchars($message);
        $datetime = date('Y-m-d H:i:s');
        $user = $this->wsTable->get($frameFd);
        foreach ($this->wsTable as $row) {
            if ($frameFd == $row['fd']) {
                continue;
            }
            $data = [
                'type'     => $messageType,
                'message'  => $message,
                'datetime' => $datetime,
                'user'     => $user
            ];

            $server->push($row['fd'], json_encode($data));
        }
    }
}
