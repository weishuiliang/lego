<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $table = 'order';

    protected $primaryKey = 'order_id';


    protected $casts = ['pay_time' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

}
