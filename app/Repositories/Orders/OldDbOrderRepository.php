<?php
namespace App\Repositories\Orders;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\OrdersTrait;
use App\Models\Order;
use App\Models\TempDocs;
use App\Repositories\Interfaces\Orders\OldDbOrderRepositoryInterface;
use App\Repositories\Orders\OrderTypeRepository;

class OldDbOrderRepository implements OldDbOrderRepositoryInterface {

    use ApiResponse, OrdersTrait;

    /**
     * get listing of the orders.
     * @param  array $data, raw information required for fetching order list
     * @param int $size, pagination size
     * @return [json] \Illuminate\Http\Response
     */
    public function getOrderList($data, $size, $userId, $mdtId = null) 
    {
        $sortOrder = "desc";
        $sortArr[] = ["created_at", $sortOrder];
        $filterSortArr = [];
        $where = ['created_by' => $userId];
        if(!empty($mdtId)){
            $where = ['mdt_id' => $mdtId];    
        }
        $search = "";
        $statusArr = $orderCodeArr = $typeArr = [];
        
        /**
         * implement filter and sort
         */
        if(!empty($data->filters)){ 
            foreach($data->filters as $filter){
                /**
                 * status filter and sort
                 */
                if(!empty($filter["field"]) && $filter["field"] == "status"){
                    if(!empty($filter["values"][0])){
                        $statusArr = $filter["values"];
                    }
                    $sortOrder = (!empty($filter["sort"]) && $filter["sort"] == "desc") ? 
                    "desc" : "asc"; 
                    $filterSortArr[] = [$filter["field"], $sortOrder];
                }

                /**
                 * order_code filter and sort
                 */
                if(!empty($filter["field"]) && $filter["field"] == "order_code"){
                    if(!empty($filter["values"][0])){
                        $orderCodeArr = $filter["values"] ;
                    }
                    $sortOrder = (!empty($filter["sort"]) && $filter["sort"] == "desc") ? 
                    "desc" : "asc"; 
                    $filterSortArr[] = [$filter["field"], $sortOrder];
                }

                /**
                 * order type filter and sort
                 */
                if(!empty($filter["field"]) && $filter["field"] == "type"){
                    if(!empty($filter["values"][0])){
                        $whereIn = $filter["values"];
                        $otypeRepo = new OrderTypeRepository();
                        $typeArr = $otypeRepo->orderTypes($whereIn);
                    }
                    $sortOrder = (!empty($filter["sort"]) && $filter["sort"] == "desc") ? 
                    "desc" : "asc"; 
                    $filterSortArr[] = ["auftragstyp", $sortOrder];
                }
            }
        }

        $orders = Order::where($where);
        /**
         * if status filter is not blank then 
         * applying this condition  
         */
        if(!empty($statusArr)){
            $orders = $orders->whereIn("status",$statusArr);
        }

        /**
         * if order_code filter is not blank then 
         * applying this condition  
         */
        if(!empty($orderCodeArr)){
            $orders = $orders->whereIn("order_code",$orderCodeArr);
        }

        /**
         * if order_type filter is not blank then 
         * applying this condition  
         */
        if(!empty($typeArr)){
            $orders = $orders->whereIn("auftragstyp",$typeArr);
        }

        /**
         * if search field is not blank then 
         * applying this condition  
         */
    
        if(!empty($data->search)){
            $search = $data->search;
            $orders = $orders->where(function($query) use($search){
                $query->where("ort", "like", "%{$search}%")
                ->orWhere("auftragstyp", "like", "%{$search}")
                ->orWhere("order_code", "like", "%{$search}");
            });
        }

        /**
         * if selected filter is not blank then
         * applying this filter instead of default filter
         */

        if(!empty($filterSortArr)){
            $sortArr = []; // reset filter
            $sortArr = $filterSortArr; // assign selected filter
        }

        /**
         * if multiple sort values are not blank then 
         * applying this condition
         */
        if(!empty($sortArr)){
            foreach($sortArr as $sort){
                $orders = $orders->orderByRaw("ISNULL($sort[0]),$sort[0] $sort[1]");
            }
        }

        $orders = $orders->paginate($size);
        return $orders;
    
    }

    /**
     * count Order by status with user id.
     * @param  id, $userId
     * @return array, $order
     */
    public function countOrderByStatusWithUserId($userId, $mdtId = null) {

        $order = Order::countOrderByStatusWithUserId($userId, $mdtId);
        return $order;
    }

    /**
     * Store Order.
     * @param  array $raworder, raw information of orders
     * @param  int $userId, user id
     * @param  int $mdtid, Mdt id
     * @return array, $order
     */
    public function storeOrder($rawOrder, $userId, $mdtId) {

        $order = new Order;
        $order->user_id = 0;
        $order->order_code = $this->getOrderCode($mdtId, $rawOrder['code']);
        $order->mdt_id = $mdtId;
        $order->markt_id = $rawOrder['marktNr'];
        $order->order_data = $rawOrder['storeOrderData'];
        $order->auftragstyp = $rawOrder['auftragstyp'];
        $order->status = 1;
        $order->created_by = $userId;
        $order->save(); // save order
        return $order;

    }

    /**
     * Delete Order by using order id
     * @param  int $orderid;
     */
    public function deleteOrder($orderId) {

        Order::where('id', $orderId)
            ->delete();

    }

    /**
     * Delete order files and unlink from directory
     * @param  int $docId, document id;
     */
    public function deleteUnlinkOrderFiles($docId) {

        $fetchDoc = TempDocs::where('id', $docId)->first();
        $docAssignedName = $fetchDoc['doc_temp_name'];
        $fetchDoc = TempDocs::where('id', $docId)->delete();
        $filePath = public_path('uploads') . '/' . $docAssignedName;
        unlink($filePath);

    }

    /** upload order temp files.
     * @param array $fileInfo,file information
     * @return array, $tempDocs
     */
    public function orderUploadFiles($fileInfo) {

        $tempDocs = new TempDocs();
        $tempDocs->doc_original_name = $fileInfo['realFname'];
        $tempDocs->doc_temp_name = $fileInfo['uniqueFname'];
        $tempDocs->url = $fileInfo['pulicUrlPath'];
        $tempDocs->file_size = $fileInfo['calculatedFileSize'];
        $tempDocs->file_type = $fileInfo['fileType'];
        $tempDocs->save();
        return $tempDocs;
    }

    /**
     * fetching all ordes with user Info
     * @return array, $orders
     */
    public function allOrdersWithUsers() {

        $orders = Order::with(['user' => function ($query) {
            $query->select(['id', 'code']);
        }])->whereNotNull('created_by')
            ->whereNotNull('mdt_id')
            ->whereNull('order_code')->get();

        return $orders;

    }

    /**
     * fetched all order type which is exists in orders
     * based on mdt or customer
     * @param int $createdBy 
     * @param int $mdt default is null 
     * @return array, $orderTypesList
     */

     public function getOrdersFormType($createdBy,$mdt = null){
        $where = ["created_by" => $createdBy];
        if(!empty($mdt)){
            $where = ["mdt_id" => 1];
        }
        $orderTypesList = Order::where($where)->groupBy("auftragstyp")->pluck("auftragstyp")->toArray();
        return $orderTypesList;
     }

     
    /**
     * get orde details using id
     * @return int, $id
     * @return object, $order
     */
    public function getOrder($id){
        return Order::find($id);
    }

    /**
     * update orde details using id
     * @return int, $id
     * @return object, $order
     */
    public function updateOrder($data, $id){
        Order::where(['id' => $id])->update($data);
        return $this->getOrder($id);
    }

}