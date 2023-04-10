<?php
namespace App\Repositories\Interfaces\Locations;

/*
 * Interface OldDbLocationRepositoryInterface
 * @package App\Repositories
 */
interface OldDbLocationRepositoryInterface {
    public function getOrtLists($mdt, $search_keys);
    public function mdtLocations($mdt, $search_keys);
}
