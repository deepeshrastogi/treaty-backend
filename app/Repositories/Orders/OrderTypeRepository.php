<?php
namespace App\Repositories\Orders;

use App\Models\OType;
use App\Repositories\Interfaces\Orders\OrderTypeRepositoryInterface;

class OrderTypeRepository implements OrderTypeRepositoryInterface
{

    /**
     * get listing of the order types.
     * @return object $orderType
     */
    public function orderTypes($whereIn = [])
    {
        $select = ['id', 'label', 'value'];
        $orderType = new OType;
        if(!empty($whereIn)){
            $select = ["id",\DB::raw("label as name")];
            $orderType = $orderType->whereIn("label",$whereIn);
        }
        $orderType = $orderType->select($select);
        $orderType = $orderType->get();
        return $orderType;
    }

    /**
     * get order type topics using id.
     * @param int $id, where id is order_type_id
     * @return object $orderTypeTopics
     */
    public function orderTypeTopics($id){
        $orderTypeTopics = OType::with('topics')->find($id);
        return $orderTypeTopics;
    }

    /**
     * save order type.
     * @param array $data
     * @return object $orderType
     */
    public function store($data){
        $orderType = Otype::create($data);
        return $orderType;
    }

    /**
     * update order type using id.
     * @param array $data
     * @param int $id
     * @return object $orderType
     */
    public function update($data, $id){
        Otype::where(['id' => $id])->update($data);
        $orderType = $this->getOrderType($id);
        return $orderType;
    }

     /**
     * get order type using id.
     * @param int $id
     * @return object $orderType
     */
    public function getOrderType($id){
        $orderType = Otype::find($id);
        return $orderType;
    }

     /**
     * delete order type using id.
     * @param int $id
     * @return object $orderType
     */
    public function destroy($id){
        $orderType = Otype::findOrFail($id);
        $orderType->delete();
        return $orderType;
    }
}
