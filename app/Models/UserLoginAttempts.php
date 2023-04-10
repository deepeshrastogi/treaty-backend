<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginAttempts extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_ip','code'];
    protected $table = 'user_login_attempts';

}
