<?php
namespace App\Repositories\Interfaces\Mdts;

/*
 * Interface OldMdtRepositoryInterface
 * @package App\Repositories
 */
interface OldDbMdtRepositoryInterface {
    public function list();
    public function fetchMdt($userId);
}
