<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $data = [
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

    // 模拟DB数据
    public function getData(): array
    {
        return $this->data;
    }
}
