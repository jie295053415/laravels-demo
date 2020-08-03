<?php


namespace App\Table;


use InvalidArgumentException;

abstract class AbstractTable implements InterfaceTable
{
    /** @var  \Swoole\Table $wsTable */
    protected $table;

    public function __construct($tableName)
    {
        if (!$tableName) {
            throw new InvalidArgumentException('Invalid tableName argument');
        }

        $this->table = app('swoole')->{$tableName . 'Table'};
    }

    /**
     * @return string|\Swoole\Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $all = [];

        foreach ($this->table as $k => $row) {
            $all[] = $row;
        }

        return $all;
    }

    // 其他的table方法没用到就不实现了

    /**
     * @param $key
     * @param array $value
     * @return mixed
     */
    public function set($key, array $value)
    {
        return $this->table->set($key, $value);
    }

    /**
     * @param $key
     * @param null $field
     * @return mixed
     */
    public function get($key, $field = null)
    {
        return $this->table->get($key, $field);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function del($key)
    {
        return $this->table->del($key);
    }
}
