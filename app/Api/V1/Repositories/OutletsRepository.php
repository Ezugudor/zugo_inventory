<?php


namespace App\Api\V1\Repositories;

use App\Api\V1\Models\Outlets;
use App\Api\V1\Repositories\BaseRepository;
use App\Api\V1\Repositories\OutletCustomerCreditRepository;
use App\Api\V1\Repositories\OutletCreditPaymentRepository;
use App\Api\V1\Repositories\OutletSalesRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class OutletsRepository extends BaseRepository
{
    private $outletCustomerCredit;
    private $outletCreditPayment;
    private $outletSalesRepo;

    public function __construct(
        OutletCustomerCreditRepository $outletCustomerCredit,
        OutletCreditPaymentRepository $outletCreditPayment,
        OutletSalesRepository $outletSalesRepo
    ) {
        $this->outletCustomerCredit = $outletCustomerCredit;
        $this->outletCreditPayment = $outletCreditPayment;
        $this->outletSalesRepo = $outletSalesRepo;
    }
    public static function showAll()
    {
        $result = Outlets::from('outlets')
            ->select(['id', 'outlet_id', 'name', 'address', 'logo', 'phone', 'abbr', 'email', 'disabled', 'created_at'])
            ->limit(30)
            ->get();
        return $result;
    }
    public static function showAllByBusiness($businessId)
    {
        $result = Outlets::from('outlets')
            ->select(['id', 'outlet_id', 'name', 'address', 'logo', 'phone', 'abbr', 'email', 'disabled', 'created_at'])
            ->where('biz_id', '=', $businessId)
            ->limit(30)
            ->get();
        return $result;
    }

    public function showAllInfoByBusiness($businessId)
    {
        $outletCC = $this->outletCustomerCredit->showAllByBusiness($businessId);
        $outletCP = $this->outletCreditPayment->showAllByBusiness($businessId);

        $outletInfo = SELF::showAllByBusiness($businessId);

        $outlets = [];
        foreach ($outletInfo as $outlet) {
            $outletSales = $this->outletSalesRepo->showAllByBusinessOutlet($businessId, $outlet->id);
            if ($outletSales) {
                $outlets[] = ['info' => $outlet, 'sales' => $outletSales, 'credits' => $outletCC, 'payments' => $outletCP];
            } else {
                $outlets[] = ['info' => [], 'sales' => [], 'credits' => [], 'payments' => []];
            }
        }

        return $outlets;
    }


    public static function show($businessId, $outletId)
    {
        $result = Outlets::from('outlets')
            ->select(['id', 'outlet_id', 'name', 'address', 'logo', 'phone', 'abbr', 'email', 'disabled', 'created_at'])
            ->where([['biz_id', '=', $businessId], ['id', '=', $outletId]])
            ->limit(30)
            ->get();
        return $result;
    }

    public function add(Request $request)
    {
        $validator = Validator::make(
            $request->input(),
            [
                'name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'logo' => 'logo',
            ]
        );


        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("logging error" . $validator);

            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message);
        }

        $name = $request->get('name');
        $address = $request->get('address');
        $phone = $request->get('phone');
        $logo = $request->get('logo');
        // $id = $request->get('cp');
        // $expiry = $request->get('expiry');

        DB::beginTransaction();
        try {
            $auth = Outlets::create([
                'name' => $name,
                'address' => $address,
                'logo' => $logo,
                'phone' => $phone
            ]);

            $message =  "Outlet created successfully";
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
}
