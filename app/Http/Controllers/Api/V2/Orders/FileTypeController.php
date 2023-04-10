<?php

namespace App\Http\Controllers\Api\V2\Orders;

use App\Http\Controllers\Api\V2\Controller;
use App\Services\OrderTypeService;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{

    // use ApiResponse;

    /**
     * @var OrderTypeService
     */
    protected $orderTypeService;

    /**
     * OrderController Constructor
     *
     * @param OrderTypeService $orderTypeService
     *
     */
    public function __construct(OrderTypeService $orderTypeService)
    {
        $this->orderTypeService = $orderTypeService;
    }

    /**
     * get Order form list
     * @param  \Illuminate\Http\Request  $request
     * return [json] \Illuminate\Http\Response
     */
    public function orderList(Request $request)
    {
        $orderForms = $this->orderTypeService->orderList($request);
        return $orderForms;
    }

     /**
     * get order file types
     * @param  \Illuminate\Http\Request  $request
     * return [json] \Illuminate\Http\Response
     */
    public function orderFileTypes(Request $request)
    {
        $orderFileTypes = $this->orderTypeService->orderFileTypes($request);
        return $orderFileTypes;
    }

}
