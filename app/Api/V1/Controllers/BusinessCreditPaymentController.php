<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\BusinessCreditPayment;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\BusinessCreditPaymentRepository;
use App\Api\V1\Repositories\BusinessCustomerCreditRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessCreditPaymentController extends BaseController
{
    private $creditPaymentRepo;
    private $customerCreditRepo;

    public function __construct(BusinessCreditPaymentRepository $creditPayment, BusinessCustomerCreditRepository $customerCreditRepo)
    {
        $this->creditPaymentRepo = $creditPayment;
        $this->customerCreditRepo = $customerCreditRepo;
    }

    public function showAll()
    {
        $result = $this->creditPaymentRepo->showAll();
        return ['business_credit_payments' => $result];
    }

    public function showAllByBusiness(Request $request)
    {
        $bizID = $request->user('api')->biz_id;
        $user = $request->user('api')->id;
        $result = $this->creditPaymentRepo->showAllByBusiness($bizID);
        return ['business_credit_payments' => $result];
    }


    public function show(Request $request, $creditPaymentId)
    {
        Log::info($creditPaymentId);
        $result = BusinessCreditPayment::from('business_credit_payment')
            ->select(['a.id', 'a.bcp_id', 'b.firstname as customer', 'a.is_outlet', 'c.name as outlet', 'a.amount', 'a.payment_type', 'a.payment_desc', 'a.receipt_id', 'a.bccs_id', 'a.created_at'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlets as c", "a.outlet", "=", "c.id")->where('id', '=', $creditPaymentId)
            ->get();
        return $result;
    }
    public function add(Request $request)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $validator = Validator::make(
            $request->input(),
            [
                'balance' => 'required',
                'amount' => 'required',
                'is_outlet' => 'required',
                'id' => 'required'
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
            'customer' =>  $reqData->customer_id,
            'outlet' =>  $reqData->outlet_id,
            'amount' => $reqData->amount,
            'bccs_id' => $reqData->id,
            'payment_desc' => $reqData->comment,
            'created_by' => $user,
            'biz_id' => $bizID,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ];

        $updateData =  [

            'balance' => $reqData->balance - $reqData->amount,
            'biz_id' => $bizID,
            'bccs_id' => $reqData->id,
        ];


        DB::beginTransaction();
        try {

            $in = $this->creditPaymentRepo->add($creditDBData);

            $inn = $this->customerCreditRepo->updateBalance($updateData);

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
