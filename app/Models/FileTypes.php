<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FileTypes extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'file_types';
    protected $fillable = ['file_type'];
    public $timestamps = false;
    protected $hidden = ['pivot','deleted_at'];
}
