<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\BusinessCustomerCredit;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\BusinessCustomerCreditRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessCustomerCreditController extends BaseController
{
    private $customerCreditRepo;

    public function __construct(BusinessCustomerCreditRepository $customerCreditRepo)
    {
        $this->customerCreditRepo = $customerCreditRepo;
    }

    public function showAll()
    {
        $result = $this->customerCreditRepo->showAll();
        return ['business_customer_credits' => $result];
    }
    public function showAllByBusiness(Request $request)
    {
        $bizID = $request->user('api')->biz_id;
        $result = $this->customerCreditRepo->showAllByBusiness($bizID);
        return ['business_customer_credits' => $result];
    }


    public function show(Request $request, $customerCreditId)
    {
        $result = $this->customerCreditRepo->showAllByBusiness($customerCreditId);
        return ['business_customer_credits' => $result];
    }

    public function add(Request $request)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $validator = Validator::make(
            $request->input(),
            [
                'receiver_id' => 'required',
                'amount' => 'required',
            ]
        );

        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("logging error" . $validator);


            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message);
        }

        $rc = $request->getContent();
        $reqData = json_decode($rc);

        $timestamp = Carbon::now();

        $creditDBData =  [
            'is_outlet' => $reqData->is_outlet ? '1' : '0',
            'customer' =>  !$reqData->is_outlet ? $reqData->receiver_id : null,
            'outlet' =>  $reqData->is_outlet ? $reqData->receiver_id : null,
            'total_amount' => $reqData->amount,
            'balance' => $reqData->amount,
            'is_auto_generated' => '0',
            'comment' => $reqData->comment,
            'created_by' => $user,
            'biz_id' => $bizID,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];


        DB::beginTransaction();
        try {

            $in = $this->customerCreditRepo->add($creditDBData);

            $result = $this->customerCreditRepo->showAllByBusiness($bizID);
            $res =  ['business_customer_credits' => $result];

            $message =  "Credit created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Credit added successful.', $res);
            return response()->json($response_message);
        } catch (\Throwable $th) {

            DB::rollBack();

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error.');
            return response()->json($response_message, 500);
        }
    }

    public function update(Request $request)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $validator = Validator::make(
            $request->input(),
            [
                'debtor' => 'required',
                'amount' => 'required'

            ]
        );

        Log::info("logging Requests inputs");
        Log::info($request->input());
        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("logging error" . $validator);


            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message);
        }

        DB::beginTransaction();
        try {
            $detail = $request->input();
            $detail['user'] = $user;
            $detail['biz_id'] = $bizID;
            $in = $this->customerCreditRepo->update($detail);

            $result = $this->customerCreditRepo->showAllByBusiness($bizID);
            $res =  ['business_customer_credits' => $result];

            $message =  "Credit created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Credit added successful.', $res);
            return response()->json($response_message);
        } catch (\Throwable $th) {

            DB::rollBack();

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error.');
            return response()->json($response_message, 500);
        }
    }
    public function delete(Request $request, $id)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;

        DB::beginTransaction();
        try {
            $in = $this->customerCreditRepo->delete($id, $bizID);

            $result = $this->customerCreditRepo->showAllByBusiness($bizID);
            $res =  ['business_customer_credits' => $result];

            $message =  "Credit created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Credit deleted successful.', $res);
            return response()->json($response_message);
        } catch (\Throwable $th) {

            DB::rollBack();

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error.');
            return response()->json($response_message);
        }
    }
}
