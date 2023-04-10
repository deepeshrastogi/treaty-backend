<?php
namespace App\Repositories\Mdts;

use App\Models\Mdt;
use App\Repositories\Interfaces\Mdts\OldDbMdtRepositoryInterface;

class OldDbMdtRepository implements OldDbMdtRepositoryInterface {
    
    /**
     * get mdts list
     * return [object]
     */
    public function list() {
        $mdtObj = new Mdt();
        $mdts = $mdtObj->getMdt();
        return $mdts;
    }

    /**
     * fetch Mdt by using mdt id
     * @param  int $id, mdt id;
     * @return string $companyName
     */
    public function fetchMdt($id) {
        $company = Mdt::where(['id' => $id])->first();
        $companyName = !empty($company->name) ? $company->name : '';
        return $companyName;
    }
}
