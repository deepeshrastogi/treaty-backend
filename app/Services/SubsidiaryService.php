<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Repositories\Interfaces\Subsidiaries\SubsidiaryRepositoryInterface;
use Validator;

class SubsidiaryService {

    use ApiResponse;
    /**
     * @var $subsidiaryRepository
     */
    protected $subsidiaryRepository;

    /**
     * CustomerService constructor.
     *
     * @param subsidiaryRepository $subsidiaryRepository
     */

    public function __construct(SubsidiaryRepositoryInterface $subsidiaryRepository) {
        $this->subsidiaryRepository = $subsidiaryRepository;
    }

    /**
     * to validate and storing subsidiary data
     * @param object $data
     * @return [json] \Illuminate\Http\Response
     */
    public function store($data) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $messages = [
                'name.required' => trans('messages.subsidary_name.required'),
                'name.unique' => trans('messages.subsidary_name.unique'),
                'location_id.required' => trans('messages.location_id.required'),
                'po_box.required' => trans('messages.po_box.required'),
                'address.required' => trans('messages.address.required'),
            ];

            $validator = Validator::make($data->all(), [
                'name' => 'required|unique:subsidary',
                'location_id' => 'required',
                'po_box' => 'required',
                'address' => 'required',
            ], $messages);

            //if validation failes, then  error would return
            if ($validator->fails()) {
                return $this->error($validator->errors());
            }

            $subsidaryArr['name'] = $data->name;
            $subsidaryArr['subsidary_number'] = $data->subsidary_number;
            $subsidaryArr['location_id'] = $data->location_id;
            $subsidaryArr['po_box'] = $data->po_box;
            $subsidaryArr['address'] = $data->address;
            $subsidiary = $this->subsidiaryRepository->store($subsidaryArr);
            return $this->success(['subsidiary' => $subsidiary]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * to fetch subsidiaries list
     * @param object $data
     * @return [json] \Illuminate\Http\Response
     */
    public function list($data) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $conditionArr["select"] = ['id', 'name'];
            $subsidiaries = $this->subsidiaryRepository->list($conditionArr);
            return $this->success(['subsidiaries' => $subsidiaries]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }
}
