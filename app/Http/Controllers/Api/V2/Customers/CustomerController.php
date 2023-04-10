<?php
namespace App\Http\Controllers\Api\V2\Customers;

use App\Http\Controllers\Api\V2\Controller;
use App\Services\OldDbCustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller {

    /**
     * @var OldDbCustomerService
     */
    protected $customerService;

    /**
     * PostController Constructor
     *
     * @param OldDbCustomerService $OldDbCustomerService
     *
     */
    public function __construct(OldDbCustomerService $customerService) {
        $this->customerService = $customerService;
    }

    /**
     * [Post] get Customer Managers list
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function getCustomerManagers(Request $request) {
        $customerManagers = $this->customerService->getCustomerManagers($request);
        return $customerManagers;
    }

    /**
     * [GET] talk to experts request
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function talkToExperts(Request $request) {
        $talkToExperts = $this->customerService->talkToExperts($request);
        return $talkToExperts;
    }

}
