<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\CustomerBusiness;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\CustomerRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class CustomerController extends BaseController
{

    private $customersRepo;

    public function __construct(CustomerRepository $customers)
    {
        $this->customersRepo = $customers;
    }

    public function showAll()
    {
        $result = $this->customersRepo->showAll();
        return ['business_customers' => $result];
    }

    public function showAllByBusiness(Request $request, $bizId = null)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $result = $this->customersRepo->showAllByBusiness($bizID);
        return ['business_customers' => $result];
    }


    public static function show($businessId, $outletId)
    {
        $result = CustomerBusiness::from('customer_business')
            ->select(['id', 'customer_id', 'surname', 'firstname', 'email', 'phone', 'biz_id', 'avatar', 'created_at'])
            ->where([['biz_id', '=', $businessId], ['id', '=', $outletId]])
            ->limit(30)
            ->get();
        return $result;
    }


    /////////////////////////////////////
    /////////////////////////////////////
    /////////////////////////////////////

    public function add(Request $request)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;

        $validator = Validator::make(
            $request->input(),
            [
                'surname' => 'required',
                'firstname' => 'required',
                'email' => 'required'
            ]
        );


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
            $in = $this->customersRepo->add($detail);

            $result = $this->customersRepo->showAllByBusiness($bizID);
            $res =  ['business_customers' => $result];

            $message =  "Customer created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Customer added successful.', $res);
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



    // //////////////////////////////////////
    // //////////////////////////////////////
    // //////////////////////////////////////

    public function update(Request $request, $id)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $validator = Validator::make(
            $request->input(),
            [
                'surname' => 'required',
                'firstname' => 'required',
                'email' => 'required'
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
            $in = $this->customersRepo->update($id, $detail, $bizID);

            $result = $this->customersRepo->showAllByBusiness($bizID);
            $res =  ['business_customers' => $result];

            $message =  "Customer updated successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Customer updated successful.', $res);
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



    /////////////////////////////////////////////
    /////////////////////////////////////////////
    /////////////////////////////////////////////


    public function delete(Request $request, $id)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;

        DB::beginTransaction();
        try {
            $in = $this->customersRepo->delete($id, $bizID);

            $result = $this->customersRepo->showAllByBusiness($bizID);
            $res =  ['business_customers' => $result];

            $message =  "Customer deleted successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Customer deleted successful.', $res);
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
