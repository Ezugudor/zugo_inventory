<?php


namespace App\Api\V1\Controllers;

use App\Api\V1\Models\CustomerBusiness;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Repositories\CustomerRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Transformers\AuthorizationTransformer;
// use App\Jobs\SendRegisterEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Validator;

class CustomerController extends BaseController
{

    private $customersRepo;

    public function __construct(CustomerRepository $customers)
    {
        $this->customersRepo = $customers;
    }

    public function showAll()
    {
        $result = $this->customersRepo->showAll();
        return ['business_customers' => $result];
    }

    public function showAllByBusiness($bizId)
    {
        $result = $this->customersRepo->showAllByBusiness($bizId);
        return ['business_customers' => $result];
    }


    public static function show($businessId, $outletId)
    {
        $result = CustomerBusiness::from('customer_business')
            ->select(['id', 'customer_id', 'surname', 'firstname', 'email', 'phone', 'biz_id', 'avatar', 'created_at'])
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
            $auth = CustomerBusiness::create([
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
