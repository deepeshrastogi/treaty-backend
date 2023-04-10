<?php
namespace App\Repositories\Interfaces\Subsidiaries;

/*
 * Interface SubsidiaryRepositoryInterface
 * @package App\Repositories
 */
interface SubsidiaryRepositoryInterface {
    public function store($data);
    public function list($data);
}
