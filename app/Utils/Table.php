<?php


namespace App\Utils;


class Table
{
    /** @var  \Swoole\Table $wsTable */
    private $wsTable;

    public function __construct()
    {
        $this->wsTable = app('swoole')->wsTable;
    }
}
