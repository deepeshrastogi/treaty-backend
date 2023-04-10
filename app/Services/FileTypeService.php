<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Repositories\Interfaces\Files\FileTypeRepositoryInterface;

class FileTypeService
{

    use ApiResponse;
    /**
     * @var $FileTypeService
     */
    protected $fileTypeService;

    /**
     * order constructor.
     *
     * @param FileTypeService $FileTypeService
     */

    public function __construct(FileTypeRepositoryInterface $fileTypeService)
    {
        $this->fileTypeService = $fileTypeService;
    }

    /**
     * get file types
     * @param object $data
     * return [json] \Illuminate\Http\Response
     */
    public function fileTypes($data)
    {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $fileTypeService = $this->fileTypeService->fileTypes();
            return $this->success(['file_types' => $fileTypeService]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

}
