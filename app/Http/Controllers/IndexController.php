<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function push(Request $request)
    {
        $frameFd = $request->input('frame_id');

        $message = htmlspecialchars($request->input('msg'));
        $messageType = htmlspecialchars($request->input('msg_type'));

        /** @var \Swoole\WebSocket\Server $server */
        $server = app('swoole');

        /** @var \Swoole\Table $table */
        $table = $server->wsTable;

        $user = $table->get($frameFd);

        $datetime = date('Y-m-d H:i:s');

        foreach ($table as $item) {
            $fd = $item['fd'];

            $data = [
                'type'     => $messageType,
                'message'  => $message,
                'datetime' => $datetime,
                'user'     => $user
            ];

            $server->push($fd, json_encode($data));
        }
    }
}
