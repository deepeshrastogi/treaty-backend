<?php

namespace App\Http\Controllers\Api\V2\Locations;

use App\Http\Controllers\Api\V2\Controller;
use App\Services\OldDbLocationService;
use Illuminate\Http\Request;

class LocationController extends Controller {

    /**
     * @var OldDbLocationService
     */
    protected $locationService;

    /**
     * PostController Constructor
     *
     * @param OldDbLocationService $OldDbLocationService
     *
     */
    public function __construct(OldDbLocationService $locationService) {
        $this->locationService = $locationService;
    }

    /**
     * [POST] Get ORTS list for add new location
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function getOrtLists(Request $request) {
        $ortsList = $this->locationService->getOrtLists($request);
        return $ortsList;
    }


    /**
     * [GET] Get mdt location 
     * @param  \Illuminate\Http\Request
     * @param  int $id mdt_id
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function mdtLocations(Request $request, $id) {
        $ortsList = $this->locationService->mdtLocations($request, $id);
        return $ortsList;
    }
}
