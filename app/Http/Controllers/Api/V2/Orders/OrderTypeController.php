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
    public function orderTypes(Request $request)
    {
        $orderForms = $this->orderTypeService->orderTypes($request);
        return $orderForms;
    }

    /**
     * get order type topics based on order type
     * @param  \Illuminate\Http\Request  $request
     * @param int $id order_type_id
     * return [json] \Illuminate\Http\Response
     */
    public function orderTypeTopics(Request $request, $id)
    {
        $orderTypeTopics = $this->orderTypeService->orderTypeTopics($request, $id);
        return $orderTypeTopics;
    }

     /**
     * POST store order type
     * @param  \Illuminate\Http\Request  $request
     * return [json] \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $orderType = $this->orderTypeService->store($request);
        return $orderType;
    }

    /**
     * PUT update order type
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * return [json] \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $orderType = $this->orderTypeService->update($request, $id);
        return $orderType;
    }

    /**
     * DELETE order type
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * return [json] \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $orderType = $this->orderTypeService->destroy($request, $id);
        return $orderType;
    }

}
