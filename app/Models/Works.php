<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Works extends Model
{
    use HasFactory;

    protected $table = 'works';

    protected $primaryKey = 'works_id';


    protected $casts = ['works_image' => 'string', 'title' => 'string', 'tags' => 'json', 'user_id' => 'int', 'date' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
