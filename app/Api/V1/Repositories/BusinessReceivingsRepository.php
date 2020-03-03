<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\BusinessReceivings;
use App\Api\V1\Models\BusinessReceivingsSum;
use App\Api\V1\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessReceivingsRepository extends BaseRepository
{

    public static function showAll()
    {
        $result = BusinessReceivingsSum::from('business_receivings_sum as a')
            ->select([
                'a.id', 'a.brs_id', 'b.product_name', 'a.supply_code', 'c.firstname as driver_firstname',
                'c.surname as driver_lastname', 'a.truck_id', 'a.driver_phone', 'a.used', 'a.date_used',
                'a.mode', 'a.size', 'a.qty', 'a.source', 'a.is_outlet', 'd.name as outlet', 'e.firstname as customer_firstname',
                'e.surname as customer_lastname', 'a.created_at'
            ])
            ->leftJoin("business_stocks as b", "a.product", "=", "b.id")
            ->leftJoin("business_driver as c", "a.driver", "=", "c.id")
            ->leftJoin("outlets as d", "a.outlet", "=", "d.id")
            ->leftJoin("customer_business as e", "a.customer", "=", "e.id")
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusiness($businessId)
    {
        $result = BusinessReceivingsSum::from('business_receivings_sum as a')
            ->select([
                'a.id', 'a.brs_id', 'b.product_name', 'a.supply_code', 'c.firstname as driver_firstname',
                'c.surname as driver_lastname', 'a.truck_id', 'a.driver_phone', 'a.used', 'a.date_used',
                'a.mode', 'a.size', 'a.qty', 'a.source', 'a.is_outlet', 'd.name as outlet', 'e.firstname as customer_firstname',
                'e.surname as customer_lastname', 'a.created_at'
            ])
            ->leftJoin("business_stocks as b", "a.product", "=", "b.id")
            ->leftJoin("business_driver as c", "a.driver", "=", "c.id")
            ->leftJoin("outlets as d", "a.outlet", "=", "d.id")
            ->leftJoin("customer_business as e", "a.customer", "=", "e.id")
            ->where('a.biz_id', '=', $businessId)
            ->orderBy('a.created_at', 'DESC')
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show($businessId, $outletId)
    {
        $result = BusinessReceivingsSum::from('business_receivings_sum as a')
            ->select([
                'a.id', 'a.brs_id', 'b.product_name', 'a.supply_code', 'c.firstname as driver_firstname',
                'c.surname as driver_lastname', 'a.truck_id', 'a.driver_phone', 'a.used', 'a.date_used',
                'a.mode', 'a.size', 'a.qty', 'a.source', 'a.is_outlet', 'd.name as outlet', 'e.firstname as customer_firstname',
                'e.surname as customer_lastname', 'a.created_at'
            ])
            ->leftJoin("business_stocks as b", "a.product", "=", "b.id")
            ->leftJoin("business_driver as c", "a.driver", "=", "c.id")
            ->leftJoin("outlets as d", "a.outlet", "=", "d.id")
            ->leftJoin("customer_business as e", "a.customer", "=", "e.id")
            ->where([['a.biz_id', '=', $businessId], ['a.id', '=', $outletId]])
            ->limit(30)
            ->get();
        return $result;
    }

    public function add($data)
    {


        try {
            $auth = BusinessReceivingsSum::create([
                'supply_code' => $data->mode,
                'mode' => $data->mode,
                'size' => $data->mode
            ]);

            $message =  "Receivings created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Outlet added successful.');
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

    public function delete($id, $bizID)
    {

        try {

            $auth = BusinessReceivingsSum::where('id', $id)
                ->where('biz_id', $bizID)
                ->delete();

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Receivings deleted successful.');
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
