<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\BusinessCustomerCredit;
use App\Api\V1\Models\BusinessCustomerCreditSum;
use App\Api\V1\Models\BusinessStocks;
use App\Api\V1\Models\BusinessSupply;
use App\Api\V1\Models\BusinessSupplySum;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\BusinessCustomerCreditRepository;
use App\Api\V1\Repositories\BusinessReceivingsRepository;
use App\Api\V1\Repositories\BusinessSupplyRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessSupplyController extends BaseController
{
    private $supplyRepo;
    private $creditRepo;
    private $rxRepo;

    public function __construct(
        BusinessSupplyRepository $supplyRepo,
        BusinessCustomerCreditRepository $creditRepo,
        BusinessReceivingsRepository $rxRepo
    ) {
        $this->supplyRepo = $supplyRepo;
        $this->creditRepo = $creditRepo;
        $this->rxRepo = $rxRepo;
    }

    public static function showAll()
    {
        $result = BusinessSupply::from('business_supply_sum as a')
            ->select(['a.bss_id', 'a.total_items', 'a.total_price', 'a.amount_paid', 'a.invoice', 'a.payment_method', 'a.is_outlet', 'a.outlet', 'a.customer', 'b.username', 'a.created_at'])
            ->leftJoin("business_admin as b", "a.created_by", "=", "b.id")
            ->get();
        return $result;
    }

    public static function showAllByBusiness($businessId)
    {
        $result = BusinessSupply::from('business_supply_sum as a')
            ->select(['a.bss_id', 'a.total_items', 'a.total_price', 'a.amount_paid', 'a.invoice', 'a.payment_method', 'a.is_outlet', 'a.outlet', 'a.customer', 'b.username', 'a.created_at'])
            ->leftJoin("business_admin as b", "a.created_by", "=", "b.id")
            ->where('a.biz_id', '=', $businessId)
            ->get();
        return $result;
    }


    public static function show(Request $request, $supplyID)
    {
        Log::info($supplyID);
        $result = BusinessSupply::from('business_supply_sum as a')
            ->select(['a.bss_id', 'a.total_items', 'a.total_price', 'a.amount_paid', 'a.invoice', 'a.payment_method', 'a.is_outlet', 'a.outlet', 'a.customer', 'b.username', 'a.created_at'])
            ->leftJoin("business_admin as b", "a.created_by", "=", "b.id")
            ->where('a.id', '=', $supplyID)
            ->get();
        return $result;
    }
    public function filter(Request $request, $code)
    {
        $bizID = $request->user('api')->biz_id;
        $user = $request->user('api')->id;

        $result = $this->supplyRepo->filter($code, $bizID);
        return ['current_supplies' => $result];
    }


    public function distribute(Request $request)
    {

        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;

        $validator = Validator::make(
            $request->all(),
            [
                // 'current_receivings' => 'required',
                // 'distribution.*' => 'required',
                // 'current_receivings.supply_code' => 'required',
                // 'distribution.*.amount' => 'required|integer'
            ]
        );

        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info($validator);

            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message);
        }

        $rc = $request->getContent();
        $rc_decoded = json_decode($rc);
        // Log::info("details received");
        // Log::info($rc_decoded);


        $currentReceivings = $rc_decoded->current_receivings;
        $distributions = $rc_decoded->distribution;
        $distributionsDB = [];
        $timestamp = Carbon::now();
        foreach ($distributions as $distribution) {
            $distributionsDB[] =  [
                'sku_code' => $currentReceivings->supply_code,
                'is_outlet' => $distribution->is_outlet ? '1' : '0',
                'customer' =>  !$distribution->is_outlet ? $distribution->receiver_id : null,
                'outlet' =>  $distribution->is_outlet ? $distribution->receiver_id : null,
                'mode' => $distribution->mode,
                'driver' => $distribution->driver_id,
                'source' => $distribution->source,
                'payment_method' => $distribution->payment_method,
                'amount_paid' => $distribution->deposit,
                'comment' => $distribution->comment,
                'total_price' => $distribution->amount,
                'created_by' => $user,
                'biz_id' => $bizID,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ];
        }

        DB::beginTransaction();
        try {

            // register distibutions
            $bss = $this->supplyRepo->distribute($distributionsDB);
            //update receivings with USED, and date USED
            $markDetail = ['id' => $currentReceivings->id, 'supply_code' => $currentReceivings->supply_code, 'biz_id' => $bizID];
            $rx = $this->rxRepo->mark($markDetail);
            /**
             * Register the creditor if payment method is Not FULL i.e
             * Payment method = NONE or PART
             */
            if (strtoupper($distribution->payment_method) !== strtoupper('full')) {
                $balance = $distribution->amount - $distribution->deposit;
                $deta =  [
                    'total_amount' => $distribution->amount,
                    'deposit' => $distribution->deposit,
                    'is_outlet' => $distribution->is_outlet ? '1' : '0',
                    'customer' =>  !$distribution->is_outlet ? $distribution->receiver_id : null,
                    'outlet' =>  $distribution->is_outlet ? $distribution->receiver_id : null,
                    'balance' => $balance,
                    'sku_code' => $currentReceivings->supply_code,
                    'created_by' => $user,
                    'biz_id' => $bizID
                ];
                $cr = $this->creditRepo->add($deta);
            }

            $message =  "Supply created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Supply added successful.');
            return response()->json($response_message);
        } catch (\Throwable $th) {

            DB::rollBack();

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            // abort(500, ["err" => "errwr", "messa" => "dsdsd"]);
            $response_message = $this->customHttpResponse(500, 'Transaction Error.');
            return response()->json($response_message, 500);
        }
    }



















    public function add(Request $request)
    {

        $user = $request->user('api');

        Log::info("laravel style");
        // $re = $request->getContent();
        // $red = json_decode($re);

        // Log::info($red->business_s);
        Log::info($request->all());
        // die();

        $validator = Validator::make(
            $request->all(),
            [
                'business_ss.total_items' => 'required|integer',
                'business_ss.total_price' => 'required|integer',
                'business_ss.deposit' => 'required|integer',
                'business_ss.invoice' => 'required|integer',
                'business_ss.payment_method' => 'required|string',
                'business_ss.is_outlet' => 'required|integer',
                'business_ss.customer' => 'required',
                'business_s.*.product_id' => 'required|integer',
                'business_s.*.qty' => 'required|integer',
                'business_s.*.price' => 'required|integer',
                'business_s.*.discount' => 'required|integer'
            ]
        );



        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info($validator);

            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message);
        }

        $rc = $request->getContent();
        $rc_decoded = json_decode($rc);

        $businessSS = $rc_decoded->business_ss;
        $businessS = $rc_decoded->business_s;


        DB::beginTransaction();
        try {
            $bss = BusinessSupplySum::create([
                'total_items' => $businessSS->total_items,
                'total_price' => $businessSS->total_price,
                'amount_paid' => $businessSS->deposit,
                'invoice' => $businessSS->invoice,
                'payment_method' => $businessSS->payment_method,
                'is_outlet' => $businessSS->is_outlet,
                'outlet' => $businessSS->outlet,
                'customer' => $businessSS->customer,
                'created_by' => $user->id,
                'biz_id' => $user->biz_id
            ]);

            foreach ($businessS as $business_item) {
                $cpp = BusinessStocks::from('business_stocks')
                    ->select(['cp'])
                    ->where('id', '=', $business_item->product_id)
                    ->limit(1)
                    ->get();

                $costPrice = $cpp[0]->cp;
                $totalCostPrice = $business_item->qty * $costPrice;
                $totalPrice = $business_item->qty * $business_item->price;

                $auth = BusinessSupply::create([
                    'product_id' => $business_item->product_id,
                    'qty' => $business_item->qty,
                    'price' => $business_item->price,
                    'total_price' => $totalPrice,
                    'cp' => $costPrice,
                    'total_cp' => $totalCostPrice,
                    'discount' => $business_item->discount,
                    'created_by' => $user->id,
                    'bss_id' => $bss->id,
                    'biz_id' => $user->biz_id
                ]);
            }

            /**
             * Register the creditor if payment method is Not FULL i.e
             * Payment method = NONE or PART
             */
            if (strtoupper($businessSS->payment_method) !== strtoupper('full')) {
                $balance = $businessSS->total_price - $businessSS->deposit;
                $bccs = BusinessCustomerCreditSum::create([
                    'total_items' => $businessSS->total_items,
                    'total_amount' => $businessSS->total_price,
                    'deposit' => $businessSS->deposit,
                    'is_outlet' => $businessSS->is_outlet,
                    'outlet' => $businessSS->outlet,
                    'customer' => $businessSS->customer,
                    'balance' => $balance,
                    'created_by' => $user->id,
                    'bss_id' => $bss->id,
                    'biz_id' => $user->biz_id
                ]);

                foreach ($businessS as $business_item) {
                    $auth = BusinessCustomerCredit::create([
                        'product_id' => $business_item->product_id,
                        'qty' => $business_item->qty,
                        'total_amount' => $business_item->price,
                        'created_by' => $user->id,
                        'biz_id' => $user->biz_id,
                        'bccs_id' => $bccs->id
                    ]);
                }
            }

            $message =  "Supply created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Supply added successful.');
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
