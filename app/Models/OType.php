<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class OType extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'otype';
    protected $fillable = ["label","value"];
    protected $hidden = ['pivot','created_at','updated_at','deleted_at'];
    protected $dates = ['deleted_at'];
    protected $softDelete = true;
    
    public function topics(){
        return $this->belongsTomany(Topics::class,'ordertype_topic','order_type','topic_id');
    }
}
