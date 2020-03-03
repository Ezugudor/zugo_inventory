<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\CustomerBusiness;
use App\Api\V1\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class CustomerRepository extends BaseRepository
{

    public static function showAll()
    {
        $result = CustomerBusiness::from('customer_business')
            ->select(['id', 'customer_id', 'surname', 'firstname', 'address', 'email', 'phone', 'biz_id', 'avatar', 'created_at', 'created_by'])
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusiness($businessId)
    {
        $result = CustomerBusiness::from('customer_business')
            ->select(['id', 'customer_id', 'surname', 'firstname', 'address', 'email', 'phone', 'biz_id', 'avatar', 'created_at', 'created_by'])
            ->where('biz_id', '=', $businessId)
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show($businessId, $outletId)
    {
        $result = CustomerBusiness::from('customer_business')
            ->select(['id', 'customer_id', 'surname', 'firstname', 'address', 'email', 'phone', 'biz_id', 'avatar', 'created_at', 'created_by'])
            ->where([['biz_id', '=', $businessId], ['id', '=', $outletId]])
            ->limit(30)
            ->get();
        return $result;
    }
    //////////////////////////////////////////
    //////////////////////////////////////////
    //////////////////////////////////////////
    public function add($details)
    {

        try {
            $res = CustomerBusiness::create([
                'surname' => $details['surname'],
                'firstname' => $details['firstname'],
                'address' => $details['address'],
                'avatar' => $details['avatar'],
                'phone' => $details['phone'],
                'email' => $details['email'],
                'biz_id' => $details['biz_id'],
                'created_by' => $details['user']
            ]);

            $message =  "Customer created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            // DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Customer added successful.', $res);
            return response()->json($response_message);
        } catch (\Throwable $th) {


            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error.');
            return response()->json($response_message);
        }
    }


    // ///////////////////////////////////////////
    // ///////////////////////////////////////////
    // ///////////////////////////////////////////
    public function update($id, $details, $bizID)
    {

        try {

            $auth = CustomerBusiness::where('id', $id)
                ->where('biz_id', $bizID)
                ->update([
                    'surname' => $details['surname'],
                    'firstname' => $details['firstname'],

                ]);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Customer updated successful.');
            return response()->json($response_message);
        } catch (\Throwable $th) {

            DB::rollBack();

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error in Stocks repo.');
            return response()->json($response_message);
        }
    }


    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////

    public function delete($id, $bizID)
    {

        try {

            $auth = CustomerBusiness::where('id', $id)
                ->where('biz_id', $bizID)
                ->delete();

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Customer deleted successful.');
            return response()->json($response_message);
        } catch (\Throwable $th) {

            DB::rollBack();

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error in Stocks repo.');
            return response()->json($response_message);
        }
    }
}
