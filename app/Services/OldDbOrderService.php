<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Http\Traits\OrdersTrait;
use App\Jobs\SendOrderConfirmationJob as OrderConfirmation;
use App\Repositories\Interfaces\Orders\OldDbOrderRepositoryInterface;
use App\Services\OldDbMdtService;
use App\Services\OrderTypeService;

class OldDbOrderService {

    use ApiResponse, OrdersTrait;
    /**
     * @var $orderRepository
     */
    protected $orderRepository;
    protected $mdtService;
    protected $orderTypeService;

    /**
     * order constructor.
     *
     * @param orderRepository $orderRepository
     */

    public function __construct(OldDbOrderRepositoryInterface $orderRepository, OldDbMdtService $mdtService, OrderTypeService $orderTypeService) {
        $this->orderRepository = $orderRepository;
        $this->mdtService = $mdtService;
        $this->orderTypeService = $orderTypeService;
    }

    /**
     * get listing of the orders.
     * @param  $data, Request data required information to fetch list
     * @return [json] \Illuminate\Http\Response
     */
    public function list($data) {

        $size = ($data->has('size')) ? $data->size : 30;
        $userData = $this->checkToken($data->bearerToken(), 200);

        if ($userData) {
            $userId = $userData->id;
            $code = !empty($userData['userMdt']->code) ? $userData['userMdt']->code : '';
            $adhUserMdtId = (!empty($userData->mdt) && $userData->mdt == 1) ? 1 : null;
            $orders = $this->orderRepository->getOrderList($data, $size, $userId, $adhUserMdtId);
            $extractPaginationData = $orders->toArray();
            unset($extractPaginationData['data']);
            $orderList = [];
            if (!empty($orders)) {
                $orderInfo = [];
                foreach ($orders as $order) {
                    $orderCode = !empty($order['order_code']) ? $order['order_code'] : "";
                    if (empty($orderCode)) {
                        $orderCode = $this->getOrderCode($order['mdt_id'], $code, $order['id']);
                    }
                    $order_data = json_decode($order['order_data'], true);
                    $orderInfo['id'] = $order['id'];
                    $orderInfo['order_code'] = $orderCode;
                    $orderInfo['message'] = isset($order_data['message']) ? $order_data['message'] : "";
                    $orderInfo['utility_bill'] = isset($order_data['utility_bill']) ?
                    $order_data['utility_bill'] : "";
                    $orderInfo['year'] = isset($order_data['year']) ? $order_data['year'] : "";
                    $orderInfo['ort'] = isset($order_data['location']['ort']) ?
                    $order_data['location']['ort'] : "";
                    $orderInfo['files'] = isset($order_data['files']) ?
                    $order_data['files'] : [];
                    $orderInfo['billing_period']['from'] = isset($order_data['billing_period']['from']) ?
                    $order_data['billing_period']['from'] : "";
                    $orderInfo['billing_period']['to'] = isset($order_data['billing_period']['to']) ?
                    $order_data['billing_period']['to'] : "";
                    $orderInfo['period_use']['from'] = isset($order_data['period_use']['from']) ?
                    $order_data['period_use']['from'] : "";
                    $orderInfo['period_use']['to'] = isset($order_data['period_use']['to']) ?
                    $order_data['period_use']['to'] : "";
                    $orderInfo['advanced_payment_for_OS']['cost'] =
                    isset($order_data['advanced_payment_for_OS']['cost']) ?
                    $order_data['advanced_payment_for_OS']['cost'] : "";
                    $orderInfo['advanced_payment_for_OS']['type'] =
                    isset($order_data['advanced_payment_for_OS']['type']) ?
                    $order_data['advanced_payment_for_OS']['type'] : "";
                    $orderInfo['counter_part']['id'] = isset($order_data['counter_part']['id']) ?
                    $order_data['counter_part']['id'] : "";
                    $orderInfo['counter_part']['name'] = isset($order_data['counter_part']['name']) ?
                    $order_data['counter_part']['name'] : "";
                    $orderInfo['cost'] = isset($order_data['advanced_payment_for_OS']['cost']) ?
                    $order_data['advanced_payment_for_OS']['cost'] : "";
                    $orderInfo['type'] = isset($order_data['advanced_payment_for_OS']['type']) ?
                    $order_data['advanced_payment_for_OS']['type'] : "";
                    $orderInfo['subsidary'] = "";
                    $getOrt = explode('-', $orderInfo['ort']);
                    $orderInfo['subsidary_number'] = !empty($getOrt[0]) ? trim($getOrt[0]) : "";
                    $orderInfo['location'] = !empty($getOrt[1]) ? trim($getOrt[1]) : "";
                    $orderInfo['auftragstyp'] = $order['auftragstyp'];
                    $orderInfo['sta'] = $order['status'];
                    $orderStatus = "";
                    if ($order['status'] == 1) { //status is open
                        $orderStatus = trans('messages.order_status.open');
                    } else if ($order['status'] == 2) { //status is open
                        $orderStatus = trans('messages.order_status.process');
                    } else if ($order['status'] == 3) { //status is completed
                        $orderStatus = trans('messages.order_status.completed');
                    }
                    $orderInfo['status'] = $orderStatus;
                    $orderInfo['created_at'] = $order['created_at'];
                    if(!empty($adhUserMdtId)){
                        $mdt = "";
                        if(!empty($order['mdt_id'])){
                            $mdtDetails = $this->mdtService->fetchMdt($data,$order['mdt_id']);
                            $mdt = $mdtDetails->original['content']['mdt'];
                        }
                        $orderInfo['mdt'] = $mdt;
                    }
                    $orderList[] = $orderInfo;
                }
            }

            $order = $this->orderRepository->countOrderByStatusWithUserId($userId, $adhUserMdtId);
            $statusList = [
                ["id" => 1, "name" => "Offen"],
                ["id" => 2, "name" => "In Arbeit"],
                ["id" => 3, "name" => "Erledigt"]
            ];
           
            $orderFormTypes = $this->orderRepository->getOrdersFormType($userId, $adhUserMdtId);
            $orderFormTypes = !empty($orderFormTypes) ? $orderFormTypes : [];
            $orderTypes = $this->orderTypeService->orderTypes($data, $orderFormTypes);
            $orderTypesLists = $orderTypes->original['content']['order_list'];
            return $this->success(["results" => ["total" => $order->total, "open" => $order->open,
                "in_progress" => $order->in_progress, "completed" => $order->completed],
                "orders" => $orderList, "pagination_info" => $extractPaginationData, "status" => 1,
                "filters" => ["status_lists" => $statusList,"order_types_list" => $orderTypesLists]]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * Store a new order from customer.
     * @param $rawOrderdata, Request raw order data
     * @return [json] \Illuminate\Http\Response
     */
    public function store($rawOrderdata) {
        $userData = $this->checkToken($rawOrderdata->bearerToken(), 200);

        if ($userData) { // if user login
            $userId = $userData->id;
            $mdtId = $userData->mdt;
            $code = !empty($userData['userMdt']->code) ? $userData['userMdt']->code : '';
            $messages = [
                'order_data.required' => trans('messages.order_data.required'),
            ];

            $rawOrderdata->validate(['order_data' => 'required'], $messages);
            $orderData['code'] = $code;
            $orderData['orderType'] = isset($rawOrderdata->order_data['Order_type']['id']) ?
            $rawOrderdata->order_data['Order_type']['id'] : "";

            $orderData['auftragstyp'] = isset($rawOrderdata->order_data['Order_type']['order_name']) ?
            $rawOrderdata->order_data['Order_type']['order_name'] : "";
            $orderData['storeOrderData'] = isset($rawOrderdata->order_data) ?
            json_encode($rawOrderdata->order_data, JSON_UNESCAPED_UNICODE) : "";

            $orderData['marktNr'] = isset($rawOrderdata->order_data['location']['market_nr']) ?
            $rawOrderdata->order_data['location']['market_nr'] : "";
            $orderData['ort'] = isset($rawOrderdata->order_data['location']['ort']) ?
            $rawOrderdata->order_data['location']['ort'] : "";
            $mdtDetails = $this->mdtService->fetchMdt($rawOrderdata,$mdtId);
            $mdt = $mdtDetails->original['content']['mdt'];
            $order = $this->orderRepository->storeOrder($orderData, $userId, $mdtId);
            
            //order related email related information
            $data['orderData'] = $rawOrderdata->order_data;
            $data['email'] = $userData->email;
            $data['lang'] = app()->getLocale();
            $data['auftragstyp'] = $orderData['auftragstyp'];
            $data['user'] = $userData;
            $data['mdt'] = $mdt;
            $getOrt = explode('-', $orderData['ort']);
            $data['ort'] = isset($getOrt[1]) ? $getOrt[1] : "";
            $data['status'] = 1;
            $data['created_at'] = $order->created_at;
            $data['order_id'] = $order->id;
            $data['order_type'] = $orderData['orderType'];

            dispatch(new OrderConfirmation($data));

            return $this->success(['order_id' => $order->id, 'status' => 1,
                'order_code' => $order->order_code]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  $data, Request data
     * @return [json] \Illuminate\Http\Response
     */
    public function destroy($data) {
        $userData = $this->checkToken($data->bearerToken(), 200);
        if ($userData) {
            $messages = [
                'order_id.required' => trans('messages.order.required'),
                'order_id.exists' => trans('messages.order.exists'),
            ];
            $data->validate([
                'order_id' => 'required|exists:orders,id',
            ], $messages);

            $orderId = $data['order_id'];
            $this->orderRepository->deleteOrder($orderId);

            return $this->success(['message' => trans('messages.order.deleted'), 'status' => 1]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
        return response(null, 204);
    }

    /**
     * Delete order temp files.
     *  @param array, $data, Request data
     * @return [json] \Illuminate\Http\Response
     */
    public function deleteUploadedFiles($data) {
        $userData = $this->checkToken($data->bearerToken(), 200);
        if ($userData) {
            $messages = [
                'file_id.required' => trans('messages.file.required'),
                'order_id.exists' => trans('messages.file.exists'),
            ];

            $data->validate([
                'file_id' => 'required|exists:temp_docs,id',
            ], $messages);

            $docId = $data['file_id'];
            $this->orderRepository->deleteUnlinkOrderFiles($docId);
            return $this->success(['message' => trans('messages.file.deleted'), 'status' => 1]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /** upload order temp files.
     * @param  $data, Request data
     * @return [json] \Illuminate\Http\Response
     */
    public function orderUploadFiles($data) {
        $userData = $this->checkToken($data->bearerToken(), 200);
        if ($userData) {
            $messages = [
                'order_file.required' => trans('messages.order_file.required'),
                'order_file.mimes' => trans('messages.order_file.mimes'),
            ];

            $data->validate([
                'order_file' => 'required|mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,png|max:2048000',
            ], $messages);

            // file_type
            $uploadedFileSize = $data->file('order_file')->getSize(); // in bytes
            $fileInfo['calculatedFileSize'] = $this->fileSize($uploadedFileSize);
            $fileInfo['fileType'] = $data->file_type;
            $fileInfo['fileExtension'] = time() . '.' . $data->file('order_file')->getClientOriginalExtension();
            $fileInfo['realFname'] = $data->file('order_file')->getClientOriginalName();
            $fileInfo['uniqueFname'] = rand() . time() . "_" . $fileInfo['fileExtension'];
            $data->file('order_file')->move(public_path('uploads'), $fileInfo['uniqueFname']);
            $fileInfo['pulicUrlPath'] = url('/') . '/uploads/' . $fileInfo['uniqueFname'];
            $tempDocs = $this->orderRepository->orderUploadFiles($fileInfo);
            
            // upload new images based on order id 
            if(!empty($data->order_id) && !empty($tempDocs)){
                $orderDetails = $this->orderRepository->getOrder($data->order_id);
                $orderData = json_decode($orderDetails->order_data,true);
                $newOrderImage = [
                    "file_new_name" => $tempDocs->doc_temp_name, 
                    "file_actual_name" => $tempDocs->doc_original_name,
                    "file_url" => $fileInfo['pulicUrlPath'],
                    "file_size" => $tempDocs->file_size,
                    "file_type" => $tempDocs->file_type,
                    "temp_doc_id" => $tempDocs->id,
                    "status" => 1,
                ];
                if(!empty($orderData['files'])){
                    array_push($orderData['files'],$newOrderImage);
                }else{
                    $orderData['files'] = $newOrderImage;
                }

                $orderDataJson = json_encode($orderData);
                $orderDetails = $this->orderRepository->updateOrder(["order_data" => $orderDataJson],$data->order_id);
            }

            return $this->success(['file_new_name' => $fileInfo['uniqueFname'], 'file_actual_name' => $fileInfo['realFname'],'file_url' => $fileInfo['pulicUrlPath'], 'file_size' => $fileInfo['calculatedFileSize'], 'file_type' => $fileInfo['fileType'],
            'temp_doc_id' => $tempDocs->id, 'status' => 1]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * update order code for existing orders which has no order id
     * @param  $data, Request data
     * @return [json] \Illuminate\Http\Response
     */
    public function updateOrderCode($data) {
        $userData = $this->checkToken($data->bearerToken(), 200);
        if ($userData) {

            $orders = $this->orderRepository->allOrdersWithUsers();
            if (!empty($orders->toArray())) {
                foreach ($orders as $order) {
                    if (!empty($order->user->code)) {
                        $this->getOrderCode($order->mdt_id, $order->user->code, $order->id);
                    }
                }
                return $this->success(['message' => trans('messages.orders_code.update')]);
            } else {
                return $this->success(['message' => trans('messages.orders_code.already_update_order')]);
            }
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

}
