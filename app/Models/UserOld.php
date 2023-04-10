<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class UserOld extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $guard = "admin";
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $connection = 'mysql2';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'twofactor_code',
        'code_expire_time',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCustomerManagers($markt) {
        $select = [
            "ct.id", 
            DB::raw("CASE WHEN ct.company IS NOT NULL THEN ct.company ELSE TRIM(CONCAT(COALESCE(cu.vorname,''), ' ', COALESCE(cu.nachname,''))) END AS 'name'")
        ];
    
        $data = DB::connection($this->connection)->table("markt_x_property_mgmt as mpm")->select($select)
        ->leftJoin("contact_mt as ct",function($join){
            $join->on("ct.id", "=", "mpm.company");
            $join->where([["ct.isarchived","<>", 1],["ct.status","<>", 1]]);
        })
        ->leftJoin("contact_user as cu",function($join){
            $join->on("cu.id", "=", "mpm.user");
            $join->where([["cu.isarchived","<>", 1],["cu.status","<>", 1]]);
        })
        ->where(["mpm.markt" => $markt])
        ->orderBy("mpm.id","DESC")->get();
            return $data;
    }

    public function userMdt(){
        return $this->hasOne(Mdt::class,'id','mdt');
    }

    
}
