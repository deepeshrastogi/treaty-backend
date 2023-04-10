<?php

namespace App\Http\Controllers\Api\V2\Files;

use App\Http\Controllers\Api\V2\Controller;
use App\Services\FileTypeService;
use Illuminate\Http\Request;

class FileTypeController extends Controller
{

    // use ApiResponse;

    /**
     * @var fileTypeService
     */
    protected $fileTypeService;

    /**
     * OrderController Constructor
     *
     * @param FileTypeService $fileTypeService
     *
     */
    public function __construct(FileTypeService $fileTypeService)
    {
        $this->fileTypeService = $fileTypeService;
    }

     /**
     * get file types
     * @param  \Illuminate\Http\Request  $request
     * return [json] \Illuminate\Http\Response
     */
    public function fileTypes(Request $request)
    {
        $fileTypes = $this->fileTypeService->fileTypes($request);
        return $fileTypes;
    }

}
