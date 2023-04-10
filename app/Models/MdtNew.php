<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class MdtNew extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'mdt';

    public function getMdt(){
        $select = ['id'];
        $select['mdt'] = DB::raw("CONCAT(code,'-',business) as mdt");
        $mdts = MdtNew::select($select)->where(['is_active' => 1])->get();
        return $mdts;
    }
}
