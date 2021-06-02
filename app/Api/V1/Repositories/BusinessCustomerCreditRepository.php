<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\BusinessCustomerCreditSum;
use App\Api\V1\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessCustomerCreditRepository extends BaseRepository
{

    public static function showAll()
    {
        $result = BusinessCustomerCreditSum::from('business_customer_credit_sum as a')
            ->select(['a.bcc_id', 'b.product_name', 'a.qty', 'a.total_amount', 'a.bccs_id', 'a.biz_id', 'c.username'])
            ->leftJoin("business_stocks as b", "a.product_id", "=", "b.id")
            ->leftJoin("business_admin as c", "a.created_by", "=", "c.id")
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusiness($businessId)
    {
        $result = BusinessCustomerCreditSum::from('business_customer_credit_sum as a')
            ->select([
                'a.id', 'a.is_outlet', 'a.is_auto_generated', 'a.sku_code', 'b.id as outlet_id', 'b.name as outlet_name', 'c.id as customer_id', 'c.surname as customer_surname',
                'c.firstname as customer_firstname', 'a.total_amount', 'a.balance', 'a.comment', 'a.created_at', 'd.firstname as author'
            ])
            ->leftJoin("outlets as b", "a.outlet", "=", "b.id")
            ->leftJoin("customer_business as c", "a.customer", "=", "c.id")
            ->leftJoin("business_admin as d", "a.created_by", "=", "d.id")
            ->where('a.biz_id', '=', $businessId)
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show($customerCreditId)
    {
        Log::info($customerCreditId);
        $result = BusinessCustomerCreditSum::from('business_customer_credit_sum as a')
            ->select(['a.bcc_id', 'b.product_name', 'b.product_type', 'a.qty', 'a.total_amount', 'a.bccs_id', 'a.biz_id', 'c.username'])
            ->leftJoin("business_stocks as b", "a.product_id", "=", "b.id")
            ->leftJoin("business_admin as c", "a.created_by", "=", "c.id")
            ->where('a.id', '=', $customerCreditId)
            ->get();
        return $result;
    }

    public function add($rows)
    {
        try {
            $auth = BusinessCustomerCreditSum::insert($rows);

            $message =  "Credit created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Credit added successful.');
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


    // //////////////////////////////////////////
    // //////////////////////////////////////////
    // //////////////////////////////////////////


    public function update($details)
    {

        try {

            $auth = BusinessCustomerCreditSum::where('id', $details['id'])
                ->where('biz_id', $details['biz_id'])
                ->update([
                    'product_name' => $details['name'],
                    'product_type' => $details['type'],
                ]);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Credit updated successful.');
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


    ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////


    public function delete($id, $bizID)
    {

        try {

            $auth = BusinessCustomerCreditSum::where('id', $id)
                ->where('biz_id', $bizID)
                ->delete();

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Credit deleted successful.');
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


    ///////////////////////////////////////////
    ///////////////////////////////////////////
    ///////////////////////////////////////////


    public function updateBalance($details)
    {

        try {

            $rx = BusinessCustomerCreditSum::where('id', $details['bccs_id'])
                ->where('biz_id', $details['biz_id'])
                ->update([
                    'balance' => $details['balance'],
                    'last_payed' => Carbon::now()
                ]);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Updated Customer credit successful.', $rx);
            return response()->json($response_message);
        } catch (\Throwable $th) {

            DB::rollBack();

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error in Stocks repo.');
            return response()->json($response_message, 500);
        }
    }
}
