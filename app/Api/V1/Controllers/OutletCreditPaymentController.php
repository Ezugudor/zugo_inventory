<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\OutletCreditPayments;
use App\Api\V1\Controllers\BaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class OutletCreditPaymentController extends BaseController
{

    public static function showAll()
    {
        $result = OutletCreditPayments::from('outlet_credit_payments as a')
            ->select(['a.id', 'a.ocp_id', 'b.firstname as customer',  'c.name as outlet', 'a.amount', 'a.payment_type', 'a.payment_desc', 'a.receipt_id', 'a.occs_id', 'a.created_at', 'd.username as author'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlets as c", "a.outlet", "=", "c.id")
            ->leftJoin("outlet_admin as d", "a.created_by", "=", "d.id")->limit(30)
            ->get();
        return $result;
    }

    public static function showAllByBusiness($businessId)
    {
        $result = OutletCreditPayments::from('outlet_credit_payments as a')
            ->select(['a.id', 'a.ocp_id', 'b.firstname as customer',  'c.name as outlet', 'a.amount', 'a.payment_type', 'a.payment_desc', 'a.receipt_id', 'a.occs_id', 'a.created_at', 'd.username as author'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlets as c", "a.outlet", "=", "c.id")
            ->leftJoin("outlet_admin as d", "a.created_by", "=", "d.id")->limit(30)
            ->where('a.biz_id', '=', $businessId)
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show(Request $request, $creditPaymentId)
    {
        Log::info($creditPaymentId);
        $result = OutletCreditPayments::from('outlet_credit_payments as a')
            ->select(['a.id', 'a.ocp_id', 'b.firstname as customer',  'c.name as outlet', 'a.amount', 'a.payment_type', 'a.payment_desc', 'a.receipt_id', 'a.occs_id', 'a.created_at', 'd.username as author'])
            ->leftJoin("customer_business as b", "a.customer", "=", "b.id")
            ->leftJoin("outlets as c", "a.outlet", "=", "c.id")
            ->leftJoin("outlet_admin as d", "a.created_by", "=", "d.id")->limit(30)
            ->where('id', '=', $creditPaymentId)
            ->get();
        return $result;
    }

    public function add(Request $request)
    {
        $validator = Validator::make(
            $request->input(),
            [
                'product_name' => 'required',
                'product_type' => 'required',
                'stock_qty' => 'required',
                'price' => 'required',
                'cp' => 'required',
                'expiry' => 'required'
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
            $auth = OutletCreditPayments::create([
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
