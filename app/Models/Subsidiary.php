<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsidiary extends Model {
    protected $table = 'subsidary';
    protected $connection = 'mysql';
    public $timestamps = true;
    protected $fillable = ['name', 'subsidary_number', 'location_id', 'po_box', 'address'];
    protected $hidden = ['pivot'];
    use HasFactory;

    public function __construct() {
        $this->table = DB::connection($this->connection)->getDatabaseName() . '.' . $this->table;
    }

}
