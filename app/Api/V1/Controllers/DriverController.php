<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\BusinessDriver;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\DriverRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class DriverController extends BaseController
{
    private $driverRepo;

    public function __construct(DriverRepository $drivers)
    {
        $this->driverRepo = $drivers;
    }


    public function showAll()
    {
        $result = $this->driverRepo->showAll();
        return $result;
    }
    public function showAllByBusiness($bizId)
    {
        $result = $this->driverRepo->showAllByBusiness($bizId);
        return $result;
    }


    public static function show($bizId, $outletId)
    {
        $result = BusinessDriver::from('business_driver')
            ->select(['id', 'customer_id', 'surname', 'firstname', 'email', 'phone', 'biz_id', 'avatar', 'created_at'])
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
                'surname' => 'required',
                'firstname' => 'required',
                'email' => 'required',
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


        $surname = $request->get('surname');
        $firstname = $request->get('firstname');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $bizID = $request->get('biz_id');

        DB::beginTransaction();
        try {
            $auth = BusinessDriver::create([
                'surname' => $surname,
                'firstname' => $firstname,
                'phone' => $phone,
                'email' => $email,
                'biz_id' => $bizID
            ]);

            $message =  "Customer created successfully";
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
