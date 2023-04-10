<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Traits\ApiResponse;
use App\Jobs\SendOrderConfirmationJob as OrderConfirmation;
use App\Models\Order;
use App\Models\TempDocs;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller {

    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {

        if ($request->has('size')) {
            $size = $request->size;
        } else {
            $size = 10;
        }
        $data = $this->checkToken($request->bearerToken(), 200);
        if ($data) {

            $userId = $data->id;

            if ($request->has('sort_by')) {

                if ($request->has('sort_value') && $request->sort_value != 0) {

                    $orders = Order::where('created_by', $userId)->where("status", $request->sort_value)->orderBy($request->sort_by, $request->sort_order)->paginate($size);

                } else {

                    if ($request->has('sort_order')) {
                        $orders = Order::where('created_by', $userId)->orderBy($request->sort_by, $request->sort_order)->paginate($size);

                    } else {

                        $orders = Order::where('created_by', $userId)->orderBy('created_at', 'desc')->paginate($size);

                    }

                }
            } else {
                $orders = Order::where('created_by', $userId)->orderBy('created_at', 'desc')->paginate($size);
            }

            $extractPaginationData = $orders->toArray();
            unset($extractPaginationData['data']);

            $orderList = array();
            if (!empty($orders)) {
                $orderInfo = array();
                foreach ($orders as $order) {
                    $order_data = json_decode($order['order_data'], true);

                    $orderInfo['id'] = $order['id'];

                    $orderInfo['message'] = isset($order_data['message']) ? $order_data['message'] : "";
                    $orderInfo['utility_bill'] = isset($order_data['utility_bill']) ? $order_data['utility_bill'] : "";
                    $orderInfo['year'] = isset($order_data['year']) ? $order_data['year'] : "";
                    $orderInfo['ort'] = isset($order_data['location']['ort']) ? $order_data['location']['ort'] : "";
                    $orderInfo['files'] = isset($order_data['files']) ? $order_data['files'] : array();

                    $orderInfo['billing_period']['from'] = isset($order_data['billing_period']['from']) ? $order_data['billing_period']['from'] : "";
                    $orderInfo['billing_period']['to'] = isset($order_data['billing_period']['to']) ? $order_data['billing_period']['to'] : "";

                    $orderInfo['period_use']['from'] = isset($order_data['period_use']['from']) ? $order_data['billing_period']['from'] : "";
                    $orderInfo['period_use']['to'] = isset($order_data['period_use']['to']) ? $order_data['billing_period']['to'] : "";

                    $orderInfo['advanced_payment_for_OS']['cost'] = isset($order_data['advanced_payment_for_OS']['cost']) ? $order_data['advanced_payment_for_OS']['cost'] : "";
                    $orderInfo['advanced_payment_for_OS']['type'] = isset($order_data['advanced_payment_for_OS']['type']) ? $order_data['advanced_payment_for_OS']['type'] : "";

                    $orderInfo['counter_part']['id'] = isset($order_data['counter_part']['id']) ? $order_data['counter_part']['id'] : "";
                    $orderInfo['counter_part']['name'] = isset($order_data['counter_part']['name']) ? $order_data['counter_part']['name'] : "";


                    $orderInfo['cost'] = isset($order_data['advanced_payment_for_OS']['cost']) ? $order_data['advanced_payment_for_OS']['cost'] : "";
                    $orderInfo['type'] = isset($order_data['advanced_payment_for_OS']['type']) ? $order_data['advanced_payment_for_OS']['type'] : "";



                    $orderInfo['subsidary'] = $order['mdt'];
                    $orderInfo['subsidary_number'] = $order['markt_nr'];
                    $getOrt = explode('-', $orderInfo['ort']);
                    $orderInfo['location'] = isset($getOrt[1]) ? $getOrt[1] : "";
                    $orderInfo['auftragstyp'] = $order['auftragstyp'];
                    $orderInfo['sta'] = $order['status'];

                    if ($order['status'] == 1) { //status is open
                        $orderStatus = trans('messages.order_status.open');
                    } else if ($order['status'] == 2) { //status is open
                        $orderStatus = trans('messages.order_status.process');
                    } else if ($order['status'] == 3) { //status is completed
                        $orderStatus = trans('messages.order_status.completed');
                    }

                    $orderInfo['status'] = $orderStatus;
                    $orderInfo['created_at'] = $order['created_at'];
                    $orderList[] = $orderInfo;
                }
            }

            return $this->success(['results' => ["total" => Order::where('created_by', $userId)->count(), "open" => Order::where('created_by', $userId)->where('status', 1)->count(), "in_progress" => Order::where('created_by', $userId)->where('status', 2)->count(), "completed" => Order::where('created_by', $userId)->where('status', 3)->count()], "orders" => $orderList, 'pagination_info' => $extractPaginationData,
                'status' => 1]);
        } else {

            return $this->error(['error' => [trans('messages.unauthorize')]], 401);

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a new order from customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $userData = $this->checkToken($request->bearerToken(), 200);

        if ($userData) { // if user login

            $userId = $userData->id;

            $mdtId = $userData->mdt;

            $messages = array(
                'order_data.required' => trans('messages.order_data.required'),

            );

            $request->validate([
                'order_data' => 'required',
            ], $messages);

            $orderType = isset($request->order_data['Order_type']['id']) ?
            $request->order_data['Order_type']['id'] : "";
            $auftragstyp = isset($request->order_data['Order_type']['order_name']) ?
            $request->order_data['Order_type']['order_name'] : "";
            $storeOrderData = isset($request->order_data) ? json_encode($request->order_data) : "";

            $sql = "SELECT name as company_name FROM `users` u
            inner join mdt on mdt.user=u.id
            where u.id=" . $userId;

            $data = DB::connection('mysql2')->select(DB::raw($sql));
            $mdt = isset($data->company_name) ? $data->company_name : "";
            $marktNr = isset($request->order_data['location']['market_nr']) ?
            $request->order_data['location']['market_nr'] : "";
            $ort = isset($request->order_data['location']['ort']) ? $request->order_data['location']['ort']
            : "";

            $order = new Order;
            $order->user_id = 0;
            $order->mdt_id = $mdtId;
            $order->markt_id = $marktNr;
            $order->order_data = $storeOrderData;
            $order->auftragstyp = $auftragstyp;
            $order->status = 1;
            $order->created_by = $userId;
            $order->save(); // save order

            $data['orderData'] = $request->order_data;
            $data['email'] = $userData->email;
            $data['lang'] = app()->getLocale();
            $data['auftragstyp'] = $auftragstyp;
            $data['user'] = $userData;
            $data['mdt'] = $mdt;
            $getOrt = explode('-', $ort);
            $data['ort'] = isset($getOrt[1]) ? $getOrt[1] : "";
            $data['status'] = 1;
            $data['created_at'] = $order->created_at;
            $data['order_id'] = $order->id;
            $data['order_type'] = $orderType;

            dispatch(new OrderConfirmation($data));

            return $this->success(["order_id" => $order->id, 'status' => 1]);
            //return response()->json(["order_id"=> $order->id, 'status'=>1]);

        } else {

            return $this->error(['error' => [trans('messages.unauthorize')]], 401);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $Order) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $Order) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $Order) {
        $Order->update($request->all());

        // return new OrderResource($Order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {

        $data = $this->checkToken($request->bearerToken(), 200);

        if ($data) {

            $messages = array(
                'order_id.required' => trans('messages.order.required'),
                'order_id.exists' => trans('messages.order.exists'),

            );
            $request->validate([
                'order_id' => 'required|exists:orders,id',

            ], $messages);

            $orderId = $request['order_id'];
            Order::where('id', $orderId)
                ->delete();

            return $this->success(['message' => trans('messages.order.deleted'),

                'status' => 1]);
        } else {

            return $this->error(['error' => [trans('messages.unauthorize')]], 401);

        }

        return response(null, 204);
    }

    /**

     * Delete order temp files.

     *

     * @return \Illuminate\Http\Response

     */

    public function deleteUploadedFiles(Request $request) {

        $data = $this->checkToken($request->bearerToken(), 200);

        if ($data) {

            $messages = array(
                'file_id.required' => trans('messages.file.required'),
                'order_id.exists' => trans('messages.file.exists'),

            );

            $request->validate([
                'file_id' => 'required|exists:temp_docs,id',

            ], $messages);

            $docId = $request['file_id'];
            $fetchDoc = TempDocs::where('id', $docId)
                ->first();

            $docAssignedName = $fetchDoc['doc_temp_name'];
            $fetchDoc = TempDocs::where('id', $docId)->delete();
            $filePath = public_path('uploads') . '/' . $docAssignedName;
            unlink($filePath);

            return $this->success(['message' => trans('messages.file.deleted'),

                'status' => 1]);
        } else {

            return $this->error(['error' => [trans('messages.unauthorize')]], 401);

        }

    }

    /* upload order temp files.

     *

     * @return \Illuminate\Http\Response

     */

    public function orderUploadFiles(Request $request) {
        $data = $this->checkToken($request->bearerToken(), 200);
        if ($data) {

            $messages = array(
                'order_file.required' => trans('messages.order_file.required'),
                'order_file.mimes' => trans('messages.order_file.mimes'),

            );

            $request->validate([

                'order_file' => 'required|mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,png|max:2048000',

            ], $messages);
            // file_type
            $uploadedFileSize = $request->file('order_file')->getSize(); // in bytes
            $calculatedFileSize = $this->fileSize($uploadedFileSize);
            $fileType = $request->file_type;
            $fileExtension = time() . '.' . $request->file('order_file')->getClientOriginalExtension();
            $realFname = $request->file('order_file')->getClientOriginalName();
            $uniqueFname = rand() . time() . "_" . $fileExtension;
            $request->file('order_file')->move(public_path('uploads'), $uniqueFname);
            $pulicUrlPath = url('/') . '/uploads/' . $uniqueFname;

            $tempDocs = new TempDocs();
            $tempDocs->doc_original_name = $realFname;
            $tempDocs->doc_temp_name = $uniqueFname;
            $tempDocs->url = $pulicUrlPath;
            $tempDocs->file_size = $calculatedFileSize;
            $tempDocs->file_type = $fileType;
            $tempDocs->save();

            return $this->success(['file_new_name' => $uniqueFname,
                'file_actual_name' => $realFname,
                'file_url' => $pulicUrlPath,
                'file_size' => $calculatedFileSize,
                'file_type' => $fileType,
                'temp_doc_id' => $tempDocs->id,
                'status' => 1]);
        } else {

            return $this->error(['error' => [trans('messages.unauthorize')]], 401);

        }
    }

/**
 * Formats filesize in human readable way.
 *
 * @param file $file
 * @return string Formatted Filesize, e.g. "113.24 MB".
 */
    public function fileSize($bytes) {

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

}
