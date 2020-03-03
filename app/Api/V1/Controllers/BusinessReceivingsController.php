<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\BusinessReceivings;
use App\Api\V1\Models\BusinessReceivingsSum;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\BusinessReceivingsRepository;
use App\Api\V1\Repositories\CustomerRepository;
use App\Api\V1\Repositories\DriverRepository;
use Carbon\Carbon;
use Hamcrest\Type\IsInteger;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class BusinessReceivingsController extends BaseController
{
    private $receivingsRepo;
    private $driverRepo;
    private $customerRepo;

    public function __construct(
        BusinessReceivingsRepository $receivingsRepo,
        CustomerRepository $customerRepo,
        DriverRepository $driverRepo
    ) {
        $this->receivingsRepo = $receivingsRepo;
        $this->driverRepo = $driverRepo;
        $this->customerRepo = $customerRepo;
    }

    public function showAll()
    {
        $result = $this->receivingsRepo->showAll();
        return ['business_receivings_sum' => $result];
    }
    public function showAllByBusiness($bizId)
    {
        $result = $this->receivingsRepo->showAllByBusiness($bizId);
        return ['business_receivings_sum' => $result];
    }


    public function show($bizId, $outletId)
    {
        $result = BusinessReceivingsSum::from('business_receivings_sum')
            ->select(['id', 'brs_id', 'supply_code', 'driver', 'truck_id', 'driver_phone', 'mode', 'size', 'source', 'created_at'])
            ->where([['biz_id', '=', $bizId], ['id', '=', $outletId]])
            ->limit(30)
            ->get();
        return $result;
    }

    public function add(Request $request)
    {
        $validator = Validator::make(
            $request->input(),
            [
                'codes' => 'required|array',
                'item' => 'required',
                'size' => 'required'
            ]
        );
        $bizID = $request->user('api')->biz_id;
        $user = $request->user('api')->id;


        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("logging erro");
            // Log::info($validator);

            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message);
        }

        $supplyCode = $request->get('codes');
        $item = $request->get('item');
        $size = $request->get('size');
        // $id = $request->get('cp');
        // $expiry = $request->get('expiry');

        DB::beginTransaction();
        try {
            foreach ($supplyCode as $code) {
                $auth = BusinessReceivingsSum::create([
                    'supply_code' => $code,
                    'product' => $item,
                    'size' => $size,
                    'biz_id' => $bizID,
                    'created_by' => $user,
                    'supplier' => '1'
                ]);
            }

            $message =  "Receivings created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);

            $receivings = $this->receivingsRepo->showAllByBusiness($bizID);
            $drivers = $this->driverRepo->showAllByBusiness($bizID);
            $customers = $this->customerRepo->showAllByBusiness($bizID);

            $data = ['business_receivings_sum' => $receivings, 'business_drivers' => $drivers, 'business_customers' => $customers];

            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Code(s) added successful.', $data);
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

    public function process(Request $request, $id)
    {
        $validator = Validator::make(
            $request->input(),
            [
                'mode' => 'required',
                'source' => 'required',
                'is_outlet' => 'required',
                'receiver' => 'required'
            ]
        );
        $bizID = $request->user('api')->biz_id;
        $user = $request->user('api')->id;


        if ($validator->fails()) {

            //Log neccessary status detail(s) for debugging purpose.
            Log::info("logging erro");
            // Log::info($validator);

            //send nicer error to the user
            $response_message = $this->customHttpResponse(401, 'Incorrect Details. All fields are required.');
            return response()->json($response_message);
        }

        $mode = $request->get('mode');
        $source = $request->get('source');
        $isOutlet = $request->get('is_outlet');
        $receiver = $request->get('receiver');
        $driver = $request->get('driver');
        $driverPhone = $request->get('driver_phone');
        $truckId = $request->get('truck_id');
        $outlet = null;
        $customer = null;

        $details = [
            'mode' => $mode,
            'source' => $source,
            'is_outlet' => $isOutlet,
            'receiver' => $receiver,
            'driver' => $driver,
            'driver' => $driver,
            'truck_id' => $truckId,
            'outlet' => $outlet,
            'customer' => $customer
        ];
        // $id = $request->get('cp');
        // $expiry = $request->get('expiry');

        DB::beginTransaction();
        try {
            if (!$this->isPersonId($driver)) {
                //driver name received and not the ID , so create a new driver and get the id
                $det = ['biz_id' => $bizID, 'surname' => $driver, 'firstname' => $driver, 'author' => $user];
                $result = $this->driverRepo->add($det);
                Log::info($result->getData('id'));
                $driver = $result->getData()->data->id;
            }

            if ($isOutlet) {
                //receiver is outlet
                $outlet = $receiver;
                if (!$this->isPersonId($receiver)) {
                    //receiver name received and not the ID ,but we cannot create an outlet on the fly, 
                    //so throw to the user that outlet does not exist
                    //send nicer error to the user
                    DB::rollBack();
                    $response_message = $this->customHttpResponse(401, 'Selected Outlet does not exist.');
                    return response()->json($response_message);
                }
            } else {
                //receiver is customer
                $customer = $receiver;
                if (!$this->isPersonId($receiver)) {
                    //receiver name received and not the ID ,so create a new customer and get the id
                    $det = [
                            'biz_id' => $bizID, 
                            'surname' => $receiver, 
                            'firstname' => $receiver, 
                            'email'=>'',
                            'address'=>'',
                            'avatar'=>'',
                            'phone'=>'',
                            'user' => $user];
                    $result = $this->customerRepo->add($det);
                    $customer = $result->getData()->data->id;
                }
            }

            $auth = BusinessReceivingsSum::where('id', $id)
                ->where('biz_id', $bizID)
                ->update([
                    'mode' => $mode,
                    'source' => $source,
                    'is_outlet' => $isOutlet ? '1' : '0',
                    'outlet' => $outlet,
                    'customer' => $customer,
                    'driver' => $driver,
                    'driver_phone' => $driverPhone,
                    'used' => '1',
                    'date_used' => Carbon::now(),
                    'truck_id' => $truckId
                ]);


            $message =  "Receivings created successfully";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);

            $receivings = $this->receivingsRepo->showAllByBusiness($bizID);
            $drivers = $this->driverRepo->showAllByBusiness($bizID);
            $customers = $this->customerRepo->showAllByBusiness($bizID);

            $data = ['business_receivings_sum' => $receivings, 'business_drivers' => $drivers, 'business_customers' => $customers];


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Processed successful.', $data);
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

    public function delete(Request $request, $id)
    {
        $user = $request->user('api')->id;
        $bizID = $request->user('api')->biz_id;

        DB::beginTransaction();
        try {
            $in = $this->receivingsRepo->delete($id, $bizID);

            $result = $this->receivingsRepo->showAllByBusiness($bizID);
            $res =  ['business_receivings_sum' => $result];

            $message =  "Stock created successfully created";
            Log::info(Carbon::now()->toDateTimeString() . " => " .  $message);


            /**
             *   If the floww can reach here, then everything is fine
             *   just commit and send success response back 
             */
            DB::commit();
            //send nicer data to the user
            $response_message = $this->customHttpResponse(200, 'Stock deleted successful.', $res);
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

    public function isPersonId($person)
    {
        return is_integer($person);
    }
}
