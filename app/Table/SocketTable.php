<?php


namespace App\Utils;


use App\Table\AbstractTable;

class SocketTable extends AbstractTable
{
    public function __construct($tableName = 'socket')
    {
        parent::__construct($tableName);
    }
}
