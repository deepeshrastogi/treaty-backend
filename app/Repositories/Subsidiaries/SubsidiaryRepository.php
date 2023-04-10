<?php
namespace App\Repositories\Subsidiaries;

use App\Models\Subsidiary;
use App\Repositories\Interfaces\Subsidiaries\SubsidiaryRepositoryInterface;

class SubsidiaryRepository implements SubsidiaryRepositoryInterface {
    /**
     * storing subsidiary data
     * @param array $data
     * return [object]
     */
    public function store($data) {
        $subsidiary = Subsidiary::create($data);
        return $subsidiary;
    }

    /**
     * subsidiaries list
     * @param array $data
     * return [object]
     */
    public function list($data) {
        $select = ["*"];
        if (array_key_exists("select", $data)) {
            $select = $data["select"];
        }
        $subsidiaries = Subsidiary::select($select)->orderBy('name', 'asc')->get();
        return $subsidiaries;
    }
}
