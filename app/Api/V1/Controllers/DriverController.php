<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\BusinessDriver;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\DriverRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class DriverController extends BaseController
{
    private $driverRepo;

    public function __construct(DriverRepository $drivers)
    {
        $this->driverRepo = $drivers;
    }


    public function showAll()
    {

        $result = $this->driverRepo->showAll();
        return ['business_drivers' => $result];
    }
    public function showAllByBusiness(Request $request)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $result = $this->driverRepo->showAllByBusiness($bizID);
        return ['business_drivers' => $result];
    }


    public static function show($bizId, $outletId)
    {
        $result = BusinessDriver::from('business_driver')
            ->select(['id', 'customer_id', 'surname', 'firstname', 'email', 'phone', 'biz_id', 'avatar', 'created_at'])
            ->where([['biz_id', '=', $bizId], ['id', '=', $outletId]])
            ->limit(30)
            ->get();
        return $result;
    }


    ///////////////////////////////////////////
    ///////////////////////////////////////////
    ///////////////////////////////////////////


    public function add(Request $request)
    {

        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;

        $validator = Validator::make(
            $request->input(),
            [
                'surname' => 'required',
                'firstname' => 'required'
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
        $details = json_decode($rc);
        // Log::info("details received");
        // Log::info($rc_decoded);


        // $currentReceivings = $rc_decoded->current_receivings;
        // $distributions = $rc_decoded->distribution;



        DB::beginTransaction();
        try {
            $data =  [
                'surname' => $details->surname,
                'firstname' => $details->firstname,
                'email' => $details->email,
                'phone' => $details->phone,
                'truck_id' => $details->truck_id,
                'address' => $details->address,
                'created_by' => $user,
                'biz_id' => $bizID
            ];
            $bss = $this->driverRepo->add($data);

            $result = $this->driverRepo->showAllByBusiness($bizID);
            $res =  ['business_drivers' => $result];

            $message =  "Driver created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Driver added successful.', $res);
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

    // //////////////////////////////////////
    // //////////////////////////////////////
    // //////////////////////////////////////

    public function update(Request $request)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $validator = Validator::make(
            $request->input(),
            [
                'id' => 'required',
                'surname' => 'required',
                'firstname' => 'required',
                'email' => 'required'
            ]
        );

        // Log::info("logging Requests inputs");
        // Log::info($request->input());
        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("logging error" . $validator);


            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message, 401);
        }

        DB::beginTransaction();
        try {
            $detail = $request->input();
            $in = $this->driverRepo->update($detail, $bizID);

            $result = $this->driverRepo->showAllByBusiness($bizID);
            $res =  ['business_drivers' => $result];

            $message =  "Driver updated successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Driver updated successful.', $res);
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

    /////////////////////////////////////////////
    /////////////////////////////////////////////
    /////////////////////////////////////////////


    public function delete(Request $request, $id)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;

        DB::beginTransaction();
        try {
            $in = $this->driverRepo->delete($id, $bizID);

            $result = $this->driverRepo->showAllByBusiness($bizID);
            $res =  ['business_drivers' => $result];

            $message =  "Driver deleted successfully created";
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
            return response()->json($response_message, 500);
        }
    }
}
