<?php

namespace App\Http\Controllers\Api\V2\Subsidiaries;

use App\Http\Controllers\Api\V2\Controller;
use App\Services\SubsidiaryService;
use Illuminate\Http\Request;

class SubsidiaryController extends Controller {

    /**
     * @var SubsidiaryService
     */
    protected $subsidiaryService;

    /**
     * PostController Constructor
     *
     * @param SubsidiaryService $SubsidiaryService
     *
     */
    public function __construct(SubsidiaryService $subsidiaryService) {
        $this->subsidiaryService = $subsidiaryService;
    }

    /*
     * Upload order temp files.
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $subsidiary = $this->subsidiaryService->store($request);
        return $subsidiary;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {
        $subsidiaries = $this->subsidiaryService->list($request);
        return $subsidiaries;
    }
}
