<?php


namespace App\Services;


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

    protected $config = [
        'avatar' => [
            './images/avatar/a.jpg',
            './images/avatar/b.jpg',
            './images/avatar/c.jpg',
            './images/avatar/d.jpg',
            './images/avatar/e.jpg',
            './images/avatar/f.jpg',
            './images/avatar/g.jpg',
            './images/avatar/h.jpg',
        ],
        'name'   => [
            'AA',
            'BB',
            'CC',
            'DD',
            'EE',
            'FF',
            'GG',
            'HH',
        ]
    ];

    public function __construct()
    {
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

//    public function onClose(Server $server, $fd, $reactorId)
//    {
//        // throw new \Exception('an exception');// 此时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
//    }

    /**
     * 创建内存表
     */
    private function createTable()
    {
        $this->wsTable = new Table(1024);
        $this->wsTable->column('fd', \swoole_table::TYPE_INT);
        $this->wsTable->column('name', \swoole_table::TYPE_STRING, 255);
        $this->wsTable->column('avatar', \swoole_table::TYPE_STRING, 255);
        $this->wsTable->create();
    }
}
