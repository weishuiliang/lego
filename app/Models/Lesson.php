<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lesson';

    protected $primaryKey = 'lesson_id';


    protected $casts = ['lesson_category_id' => 'integer', 'lesson_name' => 'string', 'applicable_age' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
