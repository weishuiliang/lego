<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationImage extends Model
{
    use HasFactory;

    protected $table = 'rotation_image';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = ['type' => 'string', 'image' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];



    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

}
