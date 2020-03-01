<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\BusinessDriver;
use App\Api\V1\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class DriverRepository extends BaseRepository
{

    public static function showAll()
    {
        $result = BusinessDriver::from('business_driver')
            ->select(['id', 'driver_id', 'surname', 'firstname', 'email', 'phone', 'biz_id', 'avatar', 'created_at'])
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusiness($businessId)
    {
        $result = BusinessDriver::from('business_driver')
            ->select(['id', 'driver_id', 'surname', 'firstname', 'email', 'phone', 'biz_id', 'avatar', 'created_at'])
            ->where('biz_id', '=', $businessId)
            ->limit(30)
            ->get();
        return $result;
    }

    public static function show($businessId, $driverId)
    {
        $result = BusinessDriver::from('business_driver')
            ->select(['id', 'driver_id', 'surname', 'firstname', 'email', 'phone', 'biz_id', 'avatar', 'created_at'])
            ->where([['biz_id', '=', $businessId], ['id', '=', $driverId]])
            ->limit(30)
            ->get();
        return $result;
    }

    public function add($details)
    {

        try {
            $res = BusinessDriver::create([
                'surname' => $details['surname'],
                'firstname' => $details['firstname'],
                'biz_id' => $details['biz_id'],
                'created_by' => $details['author']
            ]);

            $message =  "Driver created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            // DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Driver added successful.', $res);
            return response()->json($response_message);
        } catch (\Throwable $th) {


            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed.r Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error.');
            return response()->json($response_message);
        }
    }
}