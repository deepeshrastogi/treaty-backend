<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Topics extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'topics';
    protected $fillable = ['topic'];
    public $timestamps = false;
    protected $hidden = ['pivot','deleted_at'];
}
