<?php
namespace App\Repositories\Interfaces\Files;

/*
 * Interface FileRepositoryInterface
 * @package App\Repositories
 */
interface FileRepositoryInterface {
    public function show($id);
    public function destroy($data);
    public function store($data);
}
