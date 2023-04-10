<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markt extends Model {
    protected $fillable = [
        'markt_nr',
        'mdt',
        'address',
        'ort',
        'postbox',
    ];
    protected $connection = 'mysql2';
    protected $table = 'markt';
    public $timestamps = false;
    use HasFactory;
    public function __construct() {
        $this->table = DB::connection($this->connection)->getDatabaseName() . '.' . $this->table;
    }
}
