<?php
namespace App\Repositories\Files;

use App\Repositories\Interfaces\Files\FileRepositoryInterface;
use App\Models\TempDocs;

class FileRepository implements FileRepositoryInterface
{

    /**
     * get order temp file.
     * @param int $id
     * @return [object]
     */
    public function show($id){
        $file = TempDocs::find($id);
        return $file;
    }

      /**
     * Delete order temp files.
     * @param int $id
     * @return [boolean]
     */
    public function destroy($id)
    {
        $file = $this->show($id);
        return $file->destroy($id);
    }

    /**
     * Upload order temp files.
     * @param array $data
     * @return [object]
     */
    public function store($data)
    {
        $file = TempDocs::create($data);
        return $file;
    }

}
