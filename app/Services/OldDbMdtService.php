<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Repositories\Interfaces\Mdts\OldDbMdtRepositoryInterface;

class OldDbMdtService {

    use ApiResponse;
    /**
     * @var $mdtRepository
     */
    protected $mdtRepository;

    /**
     * CustomerService constructor.
     *
     * @param mdtRepository $mdtRepository
     */

    public function __construct(OldDbMdtRepositoryInterface $mdtRepository) {
        $this->mdtRepository = $mdtRepository;
    }

    /**
     * [POST] Get Mdts list for add new location
     * @param  object $data
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function list($data) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $mdts = $this->mdtRepository->list($data);
            return $this->success(['mdts' => $mdts]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * fetch Mdt by using user id
     * @param  object $data 
     * @param int $mdtId
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function fetchMdt($data, $mdtId) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $mdt = $this->mdtRepository->fetchMdt($mdtId);
            return $this->success(['mdt' => $mdt]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }
}
