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
        $response->header("Content-Type", "text/html; charset=utf-8");
        $response->end("<h1>Hello laravels-demo. #".mt_rand(1000, 9999)."</h1>");
    }
}
