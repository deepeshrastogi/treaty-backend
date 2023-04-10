<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tokens extends Model
{
    use HasFactory;

    protected $table = 'customer_tokens';
    

    protected $fillable = ['access_token', 'user_id'];
}
