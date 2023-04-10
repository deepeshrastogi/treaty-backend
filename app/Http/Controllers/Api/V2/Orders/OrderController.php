<?php

namespace App\Http\Controllers\Api\V2\Orders;

use App\Http\Controllers\Api\V2\Controller;
use App\Services\OldDbOrderService;
use Illuminate\Http\Request;
use Config;

class OrderController extends Controller {

/**
 * @var OlderDbOrderService
 */
    protected $orderService;

    /**
     * OrderController Constructor
     *
     * @param OldDbOrderService $OldDbOrderService
     *
     */
    public function __construct(OldDbOrderService $orderService) {
        $this->orderService = $orderService;
    }

    /**
     * get listing of the orders.
     * @param  \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    function list(Request $request) {

        $orders = $this->orderService->list($request);
        return $orders;

    }

    /**
     * Store a new order from customer.
     * @param  \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $orders = $this->orderService->store($request);
        return $orders;

    }

    /**
     * Remove the specified resource from storage.
     * @param  \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function destroy(Request $request) {

        $orders = $this->orderService->destroy($request);
        return $orders;
    }

    /**
     * Delete order temp files.
     * @param  \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function deleteUploadedFiles(Request $request) {

        $deleteUploadedFiles = $this->orderService->deleteUploadedFiles($request);
        return $deleteUploadedFiles;

    }

    /* upload order temp files.
     * @param  \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function orderUploadFiles(Request $request) {

        $orderUploadFiles = $this->orderService->orderUploadFiles($request);
        return $orderUploadFiles;

    }

    /**
     * update order code for existing orders which has no order id
     * @param  \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function updateOrderCode(Request $request) {

        $updateOrderCode = $this->orderService->updateOrderCode($request);
        return $updateOrderCode;

    }

}
