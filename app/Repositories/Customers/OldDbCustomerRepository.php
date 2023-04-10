<?php
namespace App\Repositories\Customers;
use App\Repositories\Interfaces\Customers\OldDbCustomerRepositoryInterface;
use App\Models\UserOld;

class OldDbCustomerRepository implements OldDbCustomerRepositoryInterface {

    /**
     * get customer Managers list.
     * @param array $data
     * @return [object]
     */
    public function getCustomerManagers($data){
        $userObj = new UserOld();
        return $userObj->getCustomerManagers($data);
    }

}