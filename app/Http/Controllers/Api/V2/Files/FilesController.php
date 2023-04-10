<?php

namespace App\Http\Controllers\Api\V2\Files;

use App\Http\Controllers\Api\V2\Controller;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\OrdersTrait;
use App\Services\FileService;
use Illuminate\Http\Request;

class FilesController extends Controller {
    use ApiResponse, OrdersTrait;
    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * PostController Constructor
     *
     * @param FileService $FileService
     *
     */
    public function __construct(FileService $fileService) {
        $this->fileService = $fileService;
    }

    /**
     * Delete order temp files.
     * @param $fileId, \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function destroy(Request $request, $fileId) {
        $file = $this->fileService->destroy($request, $fileId);
        return $file;
    }

    /*
     * Upload order temp files.
     * @param \Illuminate\Http\Request $request
     * @return [json] \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $file = $this->fileService->store($request);
        return $file;
    }

    /*
     * download file using file id.
     * @param $fileId, \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function downloadFile(Request $request, $fileId) {
        $file = $this->fileService->downloadFile($request, $fileId);
        return $file;
    }

    /*
     * download all files using order id.
     * @param $orderId, \Illuminate\Http\Request  $request
     * @return [json] \Illuminate\Http\Response
     */
    public function downloadAllFiles(Request $request, $orderId) {
        $orderFiles = $this->fileService->downloadAllFiles($request, $orderId);
        return $orderFiles;
    }
}
