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
    //////////////////////////////////////////
    //////////////////////////////////////////
    //////////////////////////////////////////
    public function add($details)
    {

        try {
            $res = Outlets::create([
                'name' => $details['name'],
                'address' => $details['address'],
                'phone' => $details['phone'],
                'email' => $details['email'],
                'biz_id' => $details['biz_id'],
                'created_by' => $details['user']
            ]);

            $message =  "Outlet created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            // DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Outlet added successful.', $res);
            return response()->json($response_message);
        } catch (\Throwable $th) {


            //Log neccessary status detail(s) for debugging purpose.
            Log::info("One of the DB statements failed. Error: " . $th);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(500, 'Transaction Error.');
            return response()->json($response_message);
        }
    }


    // ///////////////////////////////////////////
    // ///////////////////////////////////////////
    // ///////////////////////////////////////////
    public function update($id, $details, $bizID)
    {

        try {

            $auth = Outlets::where('id', $id)
                ->where('biz_id', $bizID)
                ->update([
                    'name' => $details['name'],
                    'address' => $details['address'],
                    'phone' => $details['phone'],
                    'email' => $details['email']
                ]);

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Outlet updated successful.');
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


    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////

    public function delete($id, $bizID)
    {

        try {

            $auth = Outlets::where('id', $id)
                ->where('biz_id', $bizID)
                ->delete();

            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Outlet deleted successful.');
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
