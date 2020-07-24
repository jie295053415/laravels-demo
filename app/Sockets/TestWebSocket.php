<?php


namespace App\Sockets;


use Hhxsv5\LaravelS\Swoole\Socket\WebSocket;
use Illuminate\Support\Facades\Log;
use Swoole\Http\Request;
use Swoole\Server\Port;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class TestWebSocket extends WebSocket
{
    public function __construct(Port $port)
    {
        parent::__construct($port);

        $this->wsTable = app('swoole')->wsTable;
    }

    /**
     * @param Server $server
     * @param $request
     */
    public function onOpen(Server $server, Request $request)
    {
        $user = [
            'fd'     => $request->fd,
            'name'   => $this->config['name'][array_rand($this->config['name'])] . '_' . $request->fd,
            'avatar' => $this->config['avatar'][array_rand($this->config['avatar'])],
        ];
        Log::info(__METHOD__, $user);
        $this->wsTable->set($request->fd, $user);

        $msg = json_encode(['user' => $user, 'all' => $this->allUser(), 'type' => 'openSuccess']);
        $server->push($request->fd, $msg);
        $this->pushMessage($server, $request->fd, "欢迎" . $user['name'] . "进入聊天室", 'open');
    }

    private function allUser()
    {
        $users = [];
        foreach ($this->wsTable as $k => $row) {
//            Log::info(__METHOD__, [$k, $row]);
            $users[] = $row;
        }
        return $users;
    }

    /**
     * @param Server $server
     * @param $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        Log::info(__METHOD__, [$frame->data, $frame->fd]);
        $this->pushMessage($server, $frame->fd, $frame->data, 'message');
    }


    /**
     * @param Server $server
     * @param $fd
     * @param $reactorId
     */
    public function onClose(Server $server, $fd, $reactorId)
    {
        $user = $this->wsTable->get($fd);
        Log::info(__METHOD__, [$fd, $user]);
        $this->pushMessage($server, $fd, $user['name'] . "离开聊天室", 'close');
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
    private function pushMessage(Server $server, $frameFd, $message, $messageType)
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

            Log::info(__METHOD__, $data);

            $server->push($row['fd'], json_encode($data));
        }
    }
}
