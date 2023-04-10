<?php

namespace App\Http\Controllers\Api\V2\Mdts;

use App\Http\Controllers\Api\V2\Controller;
use App\Services\OldDbMdtService;
use Illuminate\Http\Request;

class MdtController extends Controller {

    /**
     * @var MdtService
     */
    protected $mdtService;

    /**
     * PostController Constructor
     *
     * @param MdtService $MdtService
     *
     */
    public function __construct(OldDbMdtService $mdtService) {
        $this->mdtService = $mdtService;
    }

    /**
     * get mdt list
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function list(Request $request) {
        $mdts = $this->mdtService->list($request);
        return $mdts;
    }
}
