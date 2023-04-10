<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';
    protected $connection = 'mysql2';

    public function user() {
        return $this->belongsTo(UserOld::class, 'created_by', 'id');
    }

    public static function countOrderByStatusWithUserId($userId, $mdtId = null) {
        $where = ['created_by' => $userId];
        if(!empty($mdtId)){
            $where = ['mdt_id' => $mdtId];
        }

        $order = Order::selectRaw("count(*) as total, SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as open,
        SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as in_progress, SUM(CASE WHEN status = 3
        THEN 1 ELSE 0 END) as completed")
        ->where($where)->first();

        return $order;
    }

}
