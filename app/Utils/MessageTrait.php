<?php


namespace App\Utils;


use App\Table\UserTable;
use Swoole\WebSocket\Server;

trait MessageTrait
{
    /**
     * 遍历发送消息
     *
     * @param Server $server
     * @param $frameFd
     * @param $message
     * @param $messageType
     */
    protected function pushMessage(Server $server, $frameFd, $message, $messageType): void
    {
        $message = htmlspecialchars($message);

        $datetime = date('Y-m-d H:i:s');

        $userTable = new UserTable();

        $user = $userTable->get($frameFd);

        foreach ($userTable->getTable() as $row) {
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
