<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\BusinessCreditPayment;
use App\Http\Controllers\Api\V1\BaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessCreditPaymentController extends BaseController
{

    public function showAll()
    {
        $result = BusinessCreditPayment::from('business_credit_payment')
            ->select(['bcp_id', 'customer', 'is_outlet', 'outlet', 'amount', 'payment_type', 'payment_desc', 'receipt_id', 'bccs_id', 'created_at'])
            ->limit(30)
            ->get();
        return $result;
    }


    public function show(Request $request, $creditPaymentId)
    {
        Log::info($creditPaymentId);
        $result = BusinessCreditPayment::from('business_credit_payment')
        ->select(['bcp_id', 'customer', 'is_outlet', 'outlet', 'amount', 'payment_type', 'payment_desc', 'receipt_id', 'bccs_id', 'created_at'])
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
            $auth = BusinessCreditPayment::create([
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
