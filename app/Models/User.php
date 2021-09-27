<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $openid
 * @property string $session_key
 * @property string $access_token
 */
class User extends Model
{
    use HasFactory;

    protected $table = 'user';


    protected $primaryKey = 'user_id';

    protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}

