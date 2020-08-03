<?php


namespace App\Events;


use App\Listeners\TestListener1;
use Hhxsv5\LaravelS\Swoole\Task\Event;

class TestEvent extends Event
{
    protected $listeners = [
        // 监听器列表
        TestListener1::class,
    ];

    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
