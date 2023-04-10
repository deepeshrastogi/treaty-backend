<?php
namespace App\Repositories\Locations;

use App\Models\ORT;
use App\Repositories\Interfaces\Locations\OldDbLocationRepositoryInterface;

class OldDbLocationRepository implements OldDbLocationRepositoryInterface {
    /**
     * get orts list
     * @param int $mdt
     * @param string $search_keys
     * return [object]
     */
    public function getOrtLists($mdt, $search_keys) {
        $ortObj = new ORT();

        //Allow all the orts temporarily for frontend requirement
        $orts = !empty($search_keys) ?
        $ortObj->searchOrt($search_keys) :
        $ortObj->getAllOrt();

        /*
        this code is temporarily disabled
        if ($mdt == 1) {

            $orts = !empty($search_keys) ?
            $ortObj->searchOrt($search_keys) :
            $ortObj->getAllOrt();

        } else {

            $orts = !empty($search_keys) ?
            $ortObj->searchOrtByMdt($mdt, $search_keys) :
            $ortObj->getAllOrtByMdt($mdt);

        }*/

        return $orts;
    }

    /**
     * get orts list
     * @param int $mdt
     * @param string $search_keys
     * return [object]
     */
    public function mdtLocations($mdt, $search_keys) {
        $ortObj = new ORT();
        $orts = !empty($search_keys) ?
        $ortObj->searchOrtByMdt($mdt, $search_keys) :
        $ortObj->getAllOrtByMdt($mdt);
        return $orts;
    }
}
