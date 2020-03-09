<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\BusinessStocks;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\BusinessStocksRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessStocksController extends BaseController
{

    private $stocksRepo;

    public function __construct(BusinessStocksRepository $stocksRepo)
    {
        $this->stocksRepo = $stocksRepo;
    }

    public function showAll()
    {
        $result = $this->stocksRepo->showAll();
        return ['business_stocks' => $result];
    }
    public function showAllByBusiness(Request $request)
    {
        $bizID = $request->user('api')->biz_id;
        // $user = $request->user('api')->id;
        $result = $this->stocksRepo->showAllByBusiness($bizID);
        return ['business_stocks' => $result];
    }


    public static function show(Request $request, $stockId)
    {
        Log::info($stockId);
        $result = BusinessStocks::from('business_stocks')
            ->select(['product_name', 'product_type', 'cp', 'price', 'stock_qty', 'expiry'])
            ->where('product_id', '=', $stockId)
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
                'name' => 'required',
                'type' => 'required',
                // 'qty' => 'required',
                // 'price' => 'required',
                // 'cp' => 'required',
                // 'expiry' => 'required'
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
            $in = $this->stocksRepo->add($detail);

            $result = $this->stocksRepo->showAllByBusiness($bizID);
            $res =  ['business_stocks' => $result];

            $message =  "Stock created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Stock added successful.', $res);
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

    public function update(Request $request, $id)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;
        $validator = Validator::make(
            $request->input(),
            [
                'name' => 'required',
                'type' => 'required'

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
            $in = $this->stocksRepo->update($id, $detail, $bizID);

            $result = $this->stocksRepo->showAllByBusiness($bizID);
            $res =  ['business_stocks' => $result];

            $message =  "Stock created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Stock added successful.', $res);
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
    public function delete(Request $request, $id)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;

        DB::beginTransaction();
        try {
            $in = $this->stocksRepo->delete($id, $bizID);

            $result = $this->stocksRepo->showAllByBusiness($bizID);
            $res =  ['business_stocks' => $result];

            $message =  "Stock created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Stock deleted successful.', $res);
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
