<?php


namespace App\Sockets;


use Hhxsv5\LaravelS\Swoole\Socket\Http;
use Swoole\Http\Request;
use Swoole\Http\Response;

class TestHttp extends Http
{
    /**
     * @param Request $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response): void
    {
        $response->header('Content-Type', 'text/html; charset=utf-8');
        $response->end(json_encode(array_merge($request->server, ['fd' => $request->fd])));
    }
}
