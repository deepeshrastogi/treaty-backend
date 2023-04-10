<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Repositories\Interfaces\Orders\OrderTypeRepositoryInterface;
use Validator;

class OrderTypeService
{

    use ApiResponse;
    /**
     * @var $orderTypeRepository
     */
    protected $orderTypeRepository;
    protected $mdtService;

    /**
     * order constructor.
     *
     * @param orderTypeRepository $orderTypeRepository
     */

    public function __construct(OrderTypeRepositoryInterface $orderTypeRepository)
    {
        $this->orderTypeRepository = $orderTypeRepository;
    }

    /**
     * get Order form list
     * @param object $data
     * return [json] \Illuminate\Http\Response
     */
    public function orderTypes($data, $whereIn = [])
    {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $orderType = $this->orderTypeRepository->orderTypes($whereIn);
            return $this->success(['order_list' => $orderType]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }


    /**
     * get Order_type's topics list
     * @param object $data
     * @param int $id, where id is order_type_id
     * return [json] \Illuminate\Http\Response
     */
    public function orderTypeTopics($data, $id)
    {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $orderTypeTopics = $this->orderTypeRepository->orderTypeTopics($id);
            return $this->success(['order_type_topics' => $orderTypeTopics]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * store order type
     * @param object $data
     * return [json] \Illuminate\Http\Response
     */
    public function store($data)
    {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $validator = Validator::make($data->all(), [
                'label' => 'required',
                'value' => 'required',
            ]);

            //if validation failes, then  error would return
            if ($validator->fails()) {
                return $this->error($validator->errors());
            }
            $orderTypeData = [
                "label" => $data->label,
                "value" => $data->value,
            ];
            $orderType = $this->orderTypeRepository->store($orderTypeData);
            return $this->success(['order_type' => $orderType]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

     /**
     * update order type
     * @param object $data
     * @param int $id
     * return [json] \Illuminate\Http\Response
     */
    public function update($data, $id)
    {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $validator = Validator::make($data->all(), [
                'label' => 'required',
                'value' => 'required',
            ]);

            //if validation failes, then  error would return
            if ($validator->fails()) {
                return $this->error($validator->errors());
            }
            $orderTypeData = [
                "label" => $data->label,
                "value" => $data->value,
            ];
            $orderType = $this->orderTypeRepository->update($orderTypeData, $id);
            return $this->success(['order_type' => $orderType]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

     /**
     * update order type
     * @param object $data
     * @param int $id
     * return [json] \Illuminate\Http\Response
     */
    public function destroy($data, $id)
    {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $orderType = $this->orderTypeRepository->destroy($id);
            return $this->success(['order_type' => $orderType]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

}
