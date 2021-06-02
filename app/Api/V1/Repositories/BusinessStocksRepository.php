<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\BusinessStocks;
use App\Api\V1\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessStocksRepository extends BaseRepository
{

    public static function showAll()
    {
        $result = BusinessStocks::from('business_stocks as a')
            ->select(['a.id', 'a.product_name', 'a.product_type', 'a.cp', 'a.price', 'a.stock_qty', 'a.expiry', 'b.surname as admin_surname', 'b.firstname as admin_firstname', 'a.created_at'])
            ->leftJoin('business_admin as b', 'a.created_by', '=', 'b.id')->orderBy('a.id', "DESC")
            ->limit(30)
            ->get();
        return $result;
    }

    public static function showAllByBusiness($businessId)
    {
        $result = BusinessStocks::from('business_stocks as a')
            ->select(['a.id', 'a.product_name', 'a.product_type', 'a.cp', 'a.price', 'a.stock_qty', 'a.expiry', 'b.surname as admin_surname', 'b.firstname as admin_firstname', 'a.created_at'])
            ->leftJoin('business_admin as b', 'a.created_by', '=', 'b.id')
            ->where('a.biz_id', '=', $businessId)
            ->orderBy('a.id', "DESC")
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show(Request $request, $stockId)
    {
        Log::info($stockId);
        $result = BusinessStocks::from('business_stocks')
            ->select(['a.id', 'a.product_name', 'a.product_type', 'a.cp', 'a.price', 'a.stock_qty', 'a.expiry', 'b.surname as admin_surname', 'b.firstname as admin_firstname', 'a.created_at'])
            ->leftJoin('business_admin as b', 'a.created_by', '=', 'b.id')
            ->where('a.id', '=', $stockId)
            ->get();
        return $result;
    }

    public function add($details)
    {

        try {
            $auth = BusinessStocks::create([
                'product_name' => $details['name'],
                'product_type' => $details['type'],
                'created_by' => $details['user'],
                'biz_id' => $details['biz_id']
            ]);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Stock added successful.');
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
    public function update($id, $details, $bizID)
    {

        try {

            $auth = BusinessStocks::where('id', $id)
                ->where('biz_id', $bizID)
                ->update([
                    'product_name' => $details['name'],
                    'product_type' => $details['type'],
                ]);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Stock updated successful.');
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
    public function delete($id, $bizID)
    {

        try {

            $auth = BusinessStocks::where('id', $id)
                ->where('biz_id', $bizID)
                ->delete();

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Stock deleted successful.');
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
