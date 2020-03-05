<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\BusinessCustomerCredit;
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
        $result = BusinessCustomerCredit::from('business_customer_credit as a')
            ->select(['a.bcc_id', 'b.product_name', 'a.qty', 'a.total_amount', 'a.bccs_id', 'a.biz_id', 'c.username'])
            ->leftJoin("business_stocks as b", "a.product_id", "=", "b.id")
            ->leftJoin("business_admin as c", "a.created_by", "=", "c.id")
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusiness($businessId)
    {
        $result = BusinessCustomerCredit::from('business_customer_credit as a')
            ->select(['a.bcc_id', 'b.product_name', 'a.qty', 'a.total_amount', 'a.bccs_id', 'a.biz_id', 'c.username'])
            ->leftJoin("business_stocks as b", "a.product_id", "=", "b.id")
            ->leftJoin("business_admin as c", "a.created_by", "=", "c.id")
            ->where('a.biz_id', '=', $businessId)
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show(Request $request, $customerCreditId)
    {
        Log::info($customerCreditId);
        $result = BusinessCustomerCredit::from('business_customer_credit as a')
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
            $auth = BusinessCustomerCreditSum::create($rows);

            $message =  "Stock created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);
            
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Stock added successful.');
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
