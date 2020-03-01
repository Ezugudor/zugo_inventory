<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\OutletCustomerCredit;
use App\Api\V1\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class OutletCustomerCreditRepository extends BaseRepository
{

    public static function showAll()
    {
        $result = OutletCustomerCredit::from('outlet_customer_credit_sum as a')
            ->select(['a.id', 'a.occs_id', 'b.firstname as customer', 'a.total_amount', 'a.deposit',  'a.balance', 'a.last_payed', 'c.username as author'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlet_admin as c", "a.created_by", "=", "c.id")
            ->where('a.balance', '>', '0')
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusiness($businessId)
    {
        $result = OutletCustomerCredit::from('outlet_customer_credit_sum as a')
            ->select(['a.id', 'a.occs_id', 'b.firstname as customer', 'a.total_amount', 'a.deposit',  'a.balance', 'a.last_payed', 'c.username as author'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlet_admin as c", "a.created_by", "=", "c.id")
            ->where([['a.biz_id', '=', $businessId], ['a.balance', '>', '0']])
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show(Request $request, $customerCreditId)
    {
        Log::info($customerCreditId);
        $result = OutletCustomerCredit::from('outlet_customer_credit_sum as a')
            ->select(['a.id', 'a.occs_id', 'b.firstname as customer', 'a.total_amount', 'a.deposit',  'a.balance', 'a.last_payed', 'c.username as author'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlet_admin as c", "a.created_by", "=", "c.id")
            ->where([['a.id', '=', $customerCreditId], ['a.balance', '>', '0']])
            ->get();
        return $result;
    }

    public function add(Request $request)
    {
        $validator = Validator::make(
            $request->input(),
            [
                'product_id' => 'required',
                'qty' => 'required',
                'total_amount' => 'required',
                'created_by' => 'required',
                'biz_id' => 'required'
            ]
        );


        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("logging error" . $validator);

            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message);
        }

        $productName = $request->get('product_name');
        $productType = $request->get('product_type');
        $stockQty = $request->get('stock_qty');
        $price = $request->get('price');
        $cp = $request->get('cp');
        $expiry = $request->get('expiry');

        DB::beginTransaction();
        try {
            $auth = OutletCustomerCredit::create([
                'product_name' => $$productName,
                'product_type' => $productType,
                'stock_qty' => $stockQty,
                'price' => $price,
                'cp' => $cp,
                'expiry' => $expiry
            ]);

            $message =  "Stock created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
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
