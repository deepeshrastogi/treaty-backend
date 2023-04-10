<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Mdt extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
   
    protected $table = 'mdt';
    protected $connection = 'mysql2';

    public function __construct() {
        $this->table = DB::connection($this->connection)->getDatabaseName() . '.' . $this->table;
    }

     public function getMdt(){
        $select = ['id'];
        $select['mdt'] = DB::raw("CONCAT(code,'-',name) as mdt");
        $mdts = Mdt::select($select)->where(['isactive' => 1])->get();
        return $mdts;
    }
    
}
