<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\BusinessCreditPayment;
use App\Api\V1\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessCreditPaymentRepository extends BaseRepository
{

    public function showAll()
    {
        $result = BusinessCreditPayment::from('business_credit_payment')
            ->select(['a.id', 'a.bcp_id', 'b.firstname as customer', 'a.is_outlet', 'c.name as outlet', 'a.amount', 'a.payment_type', 'a.payment_desc', 'a.receipt_id', 'a.bccs_id', 'a.created_at'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlets as c", "a.outlet", "=", "c.id")->limit(30)
            ->get();
        return $result;
    }

    public function showAllByBusiness($businessId)
    {
        $result = BusinessCreditPayment::from('business_credit_payment as a')
            ->select(['a.id', 'a.bcp_id', 'b.firstname as customer', 'a.is_outlet', 'c.name as outlet', 'a.amount', 'a.payment_type', 'a.payment_desc', 'a.receipt_id', 'a.bccs_id', 'a.created_at'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlets as c", "a.outlet", "=", "c.id")
            ->where('a.biz_id', '=', $businessId)
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show($creditPaymentId)
    {
        Log::info($creditPaymentId);
        $result = BusinessCreditPayment::from('business_credit_payment')
            ->select(['a.id', 'a.bcp_id', 'b.firstname as customer', 'a.is_outlet', 'c.name as outlet', 'a.amount', 'a.payment_type', 'a.payment_desc', 'a.receipt_id', 'a.bccs_id', 'a.created_at'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlets as c", "a.outlet", "=", "c.id")->where('id', '=', $creditPaymentId)
            ->get();
        return $result;
    }

    public function add($details)
    {

        try {
            $auth = BusinessCreditPayment::insert($details);

            $message =  "Payment created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);

            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Payment added successful.');
            return response()->json($response_message);
        } catch (\Throwable $th) {

            DB::rollBack();

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error.');
            return response()->json($response_message,500);
        }
    }

    // //////////////////////////////////////////
    // //////////////////////////////////////////
    // //////////////////////////////////////////


    public function update($id, $details)
    {

        try {

            $auth = BusinessCreditPayment::where('id', $id)
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

            $auth = BusinessCreditPayment::where('id', $id)
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

    
}
