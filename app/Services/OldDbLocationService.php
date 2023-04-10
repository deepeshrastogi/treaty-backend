<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Repositories\Interfaces\Locations\OldDbLocationRepositoryInterface;

class OldDbLocationService {

    use ApiResponse;
    /**
     * @var $locationRepository
     */
    protected $locationRepository;

    /**
     * CustomerService constructor.
     *
     * @param locationRepository $locationRepository
     */

    public function __construct(OldDbLocationRepositoryInterface $locationRepository) {
        $this->locationRepository = $locationRepository;
    }

    /**
     * [POST] Get ORTS list for add new location
     * @param  object $data
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function getOrtLists($data) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $mdt = $user->mdt;
            $search = strip_tags(trim($data->search));
            $search_keys = str_replace(" ", "|", trim($search));
            if (isset($mdt) and $mdt != '') {
                $orts = $this->locationRepository->getOrtLists($mdt, $search_keys);
                return $this->success(["data" => $orts]);
            } else {
                return $this->error(['error' => [trans('messages.unauthorize')]]);
            }
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }


    /**
     * [GET] Get mdt locations
     * @param  object $data
     * @param  int $mdtId
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function mdtLocations($data, $mdtId) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $mdt = $mdtId;
            $search = strip_tags(trim($data->search));
            $search_keys = str_replace(" ", "|", trim($search));
            if (isset($mdt) and $mdt != '') {
                $orts = $this->locationRepository->mdtLocations($mdt, $search_keys);
                return $this->success(["data" => $orts]);
            } else {
                return $this->error(['error' => [trans('messages.unauthorize')]]);
            }
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }
}
