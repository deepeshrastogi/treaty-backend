<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Jobs\AnswerToTheCustomerJob;
use App\Jobs\TalkToExpertJob;
use App\Repositories\Interfaces\Customers\OldDbCustomerRepositoryInterface;
use Config;
use Validator;

class OldDbCustomerService {

    use ApiResponse;
    /**
     * @var $customerRepository
     */
    protected $customerRepository;

    /**
     * CustomerService constructor.
     *
     * @param customerRepository $customerRepository
     */

    public function __construct(OldDbCustomerRepositoryInterface $customerRepository) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Call talk to experts.
     * @param object $data
     * @return [json] \Illuminate\Http\Response
     */
    public function talkToExperts($data) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $companyEmail = Config::get('api.EXPERT_EMAIL');
            $details['email'] = $user->email;
            $details['name'] = $data->firstName . " " . $data->lastName;
            $details['phone_no'] = $data->telephone;
            $details['message'] = $data->message;
            $companyData = $details;
            $companyData['company_email'] = $companyEmail;
            dispatch(new AnswerToTheCustomerJob($companyData));
            dispatch(new TalkToExpertJob($details));
            return $this->success(['message' => [trans('messages.talkToExpt.send')]]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }

    /**
     * get customer managers list.
     * @param object $data
     * @return [json] \Illuminate\Http\Response
     */
    public function getCustomerManagers($data) {
        $user = $this->checkToken($data->bearerToken(), 200);
        if ($user) {
            $validator = Validator::make($data->all(), [
                'markt_nr' => 'required',
            ]);

            //if validation failes, then  error would return
            if ($validator->fails()) {
                return $this->error($validator->errors());
            }

            $markt = $data->markt_nr;
            $data = $this->customerRepository->getCustomerManagers($markt);
            return $this->success(["data" => $data]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
    }
}
