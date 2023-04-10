<?php
namespace App\Repositories\Files;

use App\Models\FileTypes;
use App\Repositories\Interfaces\Files\FileTypeRepositoryInterface;

class FileTypeRepository implements FileTypeRepositoryInterface
{

    /**
     * get listing of the file types.
     * @return object $fileTypes
     */
    public function fileTypes()
    {
        $fileTypes = FileTypes::all();
        return $fileTypes;
    }
}
