<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Log extends Model
{
    use HasFactory;

    protected $table = 'log';

    protected $primaryKey = 'id';

    protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public static function saveLog($type = '', $content = '')
    {
        $model = new self();
        $model->type = $type;
        $model->content = $content;
        $model->save();
    }
}
