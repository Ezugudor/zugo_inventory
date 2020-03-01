<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\OutletSales;
use App\Api\V1\Models\OutletSalesSum;
use App\Api\V1\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class OutletSalesRepository extends BaseRepository
{

    public static function showAll()
    {
        $result = OutletSales::from('outlet_sales as a')
            ->select(['a.id', 'a.trans_id', 'a.product_id', 'a.qty', 'a.price', 'a.cp', 'a.total_price', 'a.total_cp', 'a.discount', 'b.username as admin', 'c.firstname as customer'])
            ->leftJoin("outlet_admin as b", "a.created_by", "=", "b.id")
            ->leftJoin("outlet_sales_sum as oss", "a.oss_id", "=", "oss.id")
            ->leftJoin("customer_business as c", "oss.customer", "=", "c.id")
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusiness($businessId)
    {
        $result = OutletSales::from('outlet_sales as a')
            ->select(['a.id', 'a.trans_id', 'a.product_id', 'a.qty', 'a.price', 'a.cp', 'a.total_price', 'a.total_cp', 'a.discount', 'b.username as admin', 'c.firstname as customer'])
            ->leftJoin("outlet_admin as b", "a.created_by", "=", "b.id")
            ->leftJoin("outlet_sales_sum as oss", "a.oss_id", "=", "oss.id")
            ->leftJoin("customer_business as c", "oss.customer", "=", "c.id")
            ->where('a.biz_id', '=', $businessId)
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusinessOutlet($businessId, $outlet)
    {
        $result = OutletSales::from('outlet_sales as a')
            ->select(['a.id', 'a.trans_id', 'a.product_id', 'a.qty', 'a.price', 'a.cp', 'a.total_price', 'a.total_cp', 'a.discount', 'b.username as admin', 'c.firstname as customer'])
            ->leftJoin("outlet_admin as b", "a.created_by", "=", "b.id")
            ->leftJoin("outlet_sales_sum as oss", "a.oss_id", "=", "oss.id")
            ->leftJoin("customer_business as c", "oss.customer", "=", "c.id")
            ->where([['a.biz_id', '=', $businessId], ['a.outlet', '=', $outlet]])
            ->limit(30)
            ->get();
        return $result;
    }


    public static function show(Request $request, $customerCreditId)
    {
        Log::info($customerCreditId);
        $result = OutletSales::from('outlet_sales as a')
            ->select(['a.id', 'a.trans_id', 'a.product_id', 'a.qty', 'a.price', 'a.cp', 'a.total_price', 'a.total_cp', 'a.discount', 'b.username', 'c.firstname'])
            ->leftJoin("outlet_admin as b", "a.created_by", "=", "b.id")
            ->leftJoin("outlet_sales_sum as oss", "a.oss_id", "=", "oss.id")
            ->leftJoin("customer_business as c", "oss.customer", "=", "c.id")
            ->where('a.id', '=', $customerCreditId)
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
            $auth = OutletSales::create([
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
