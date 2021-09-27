<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assess extends Model
{
    use HasFactory;

    protected $table = 'assess';

    protected $primaryKey = 'assess_id';



    protected $casts = ['assess_id' => 'integer', 'assess_image' => 'string', 'title' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

}
