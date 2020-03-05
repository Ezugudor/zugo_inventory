<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Outlets;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\OutletsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class OutletsController extends BaseController
{


    private $outletsRepo;

    public function __construct(OutletsRepository $outletsRepo)
    {
        $this->outletsRepo = $outletsRepo;
    }

    public function showAll()
    {
        $result = $this->outletsRepo->showAll();
        return ['outlets' => $result];
    }
    public function showAllByBusiness(Request $request, $bizId = null)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $result = $this->outletsRepo->showAllByBusiness($bizID);
        return ['outlets' => $result];
    }
    public function showAllInfoByBusiness(Request $request)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $result = $this->outletsRepo->showAllInfoByBusiness($bizID);
        return ['outlets' => $result];
    }


    public static function show($bizId, $outletId)
    {
        $result = Outlets::from('outlets')
            ->select(['id', 'outlet_id', 'name', 'address', 'logo', 'phone', 'abbr', 'email', 'disabled', 'created_at'])
            ->where([['biz_id', '=', $bizId], ['id', '=', $outletId]])
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
                'name' => 'required',
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
            $in = $this->outletsRepo->add($detail);

            $result = $this->outletsRepo->showAllInfoByBusiness($bizID);
            $res =  ['outlets' => $result];

            $message =  "Outlet created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Outlet added successful.', $res);
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
                'name' => 'required'
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
            $in = $this->outletsRepo->update($id, $detail, $bizID);

            $result = $this->outletsRepo->showAllInfoByBusiness($bizID);
            $res =  ['outlets' => $result];

            $message =  "Outlet updated successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Outlet updated successful.', $res);
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
            $in = $this->outletsRepo->delete($id, $bizID);

            $result = $this->outletsRepo->showAllInfoByBusiness($bizID);
            $res =  ['outlets' => $result];

            $message =  "Outlet deleted successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Outlet deleted successful.', $res);
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
