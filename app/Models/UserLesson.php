<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLesson extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'user_lesson';

    protected $primaryKey = 'id';

    protected $casts = ['user_id' => 'integer', 'lesson_id' => 'integer', 'start_time' => 'datetime', 'end_time' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
