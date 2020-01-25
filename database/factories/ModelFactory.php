<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
/**
 * Notice that some models below were declared but not used, especially the TYPES
 * because their data were explicitly defined in their individual seeding files.
 */

use App\User;
use App\Api\V1\Models\ActivityLogOutlet;
use App\Api\V1\Models\ActivityLogBusiness;
use App\Api\V1\Models\ActivityLogSystem;
use App\Api\V1\Models\ActivityType;
use App\Api\V1\Models\BizOutletMsg;
use App\Api\V1\Models\BizOutletMsgRecipient;
use App\Api\V1\Models\BizOutletMsgSeen;
use App\Api\V1\Models\BusinessAdmin;
use App\Api\V1\Models\BusinessCreditPayment;
use App\Api\V1\Models\BusinessAdminRole;
use App\Api\V1\Models\BusinessCustomerCredit;
use App\Api\V1\Models\BusinessCustomerCreditSum;
use App\Api\V1\Models\Businesses;
use App\Api\V1\Models\BusinessPaymentResolution;
use App\Api\V1\Models\BusinessReceivings;
use App\Api\V1\Models\BusinessReceivingsSum;
use App\Api\V1\Models\BusinessStocks;
use App\Api\V1\Models\BusinessSubscription;
use App\Api\V1\Models\BusinessSuppliers;
use App\Api\V1\Models\BusinessSupply;
use App\Api\V1\Models\BusinessSupplySum;
use App\Api\V1\Models\CustomerBusiness;
use App\Api\V1\Models\CustomerOutlet;
use App\Api\V1\Models\OutletAdmin;
use App\Api\V1\Models\OutletCreditPayments;
use App\Api\V1\Models\OutletCustomerCredit;
use App\Api\V1\Models\OutletCustomerCreditSum;
use App\Api\V1\Models\OutletReceivings;
use App\Api\V1\Models\OutletReceivingsSum;
use App\Api\V1\Models\Outlets;
use App\Api\V1\Models\OutletSales;
use App\Api\V1\Models\OutletSalesSum;
use App\Api\V1\Models\OutletStocks;
use App\Api\V1\Models\SysBizMsg;
use App\Api\V1\Models\SysBizMsgRecipient;
use App\Api\V1\Models\SysBizMsgSeen;
use App\Api\V1\Models\SystemAdmin;
use App\Api\V1\Models\SystemAdminRole;
use App\Api\V1\Models\SystemSettings;
use Faker\Generator as Faker;
use Faker\Provider\UserAgent;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});


$factory->define(Businesses::class, function (Faker $faker) {
    return [
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'name' => $faker->company,
        'address' => $faker->address,
        'slogan' => $faker->realText($maxNbChars = 50, $indexSize = 2),
        'logo' => $faker->imageUrl($width = 640, $height = 480),
        'abbr' => $faker->word,
        'disabled' => $faker->randomElement($array = array(1, '0')),
        'date_disabled' => $faker->dateTimeThisDecade($max = 'now')->format('Y-m-d H:i:s'),
        'is_subscribed' => $faker->randomElement($array = array(1, '0')),
        'active_sub_code' => $faker->uuid,
        'last_sub_date' => $faker->dateTimeThisDecade($max = 'now')->format('Y-m-d H:i:s'),
        'sub_expiry_date' => $faker->dateTimeThisDecade($max = 'now')->format('Y-m-d H:i:s'),
        'email' => $faker->safeEmail,
        'phone' => $faker->e164PhoneNumber,
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(BusinessAdmin::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'role' => $faker->randomElement($array = array(1, 2, 3)),
        'user_code' => $faker->uuid,
        'password' => $faker->md5,
        'surname' => $faker->lastName,
        'firstname' => $faker->firstName,
        'phone' => $faker->e164PhoneNumber,
        'email' => $faker->safeEmail,
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'avatar' => $faker->imageUrl($width = 640, $height = 480),
        'email_veri_code' => $faker->uuid,
        'email_verified' => $faker->randomElement($array = array(1, '0')),
        'phone_veri_code' => $faker->randomNumber(7, true),
        'phone_verified' => $faker->randomElement($array = array(1, '0')),
        'last_login' => $faker->dateTimeThisDecade($max = 'now')->format('Y-m-d H:i:s'),
        'last_action' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'user_disabled' => $faker->randomElement($array = array(1, '0')),
        'date_disabled' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'activation_code' => $faker->uuid,
        'activation_code_activated' => $faker->randomElement($array = array(1, '0')),
        'activation_code_expire' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'deleted_at' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
    ];
});



$factory->define(ActivityLogBusiness::class, function (Faker $faker) {
    return [
        'activity' => $faker->randomElement($array = array(1, 2, 3)),
        'admin' => $faker->numberBetween($min = 1, $max = 50),
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'client_ip' => $faker->ipv4,
        'browser' => $faker->userAgent
    ];
});

$factory->define(ActivityLogOutlet::class, function (Faker $faker) {
    return [
        'activity' => $faker->randomElement($array = array(1, 2, 3)),
        'admin' => $faker->numberBetween($min = 1, $max = 50),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'client_ip' => $faker->ipv4,
        'browser' => $faker->userAgent
    ];
});

$factory->define(ActivityLogSystem::class, function (Faker $faker) {
    return [
        'activity' => $faker->randomElement($array = array(1, 2, 3)),
        'admin' => $faker->numberBetween($min = 1, $max = 50),
        'client_ip' => $faker->ipv4,
        'browser' => $faker->userAgent
    ];
});

$factory->define(ActivityType::class, function (Faker $faker) {
    return [
        'activity_name' => $faker->word,
        'activity_desc' => $faker->realText($maxNbChars = 100, $indexSize = 2)
    ];
});


$factory->define(BizOutletMsg::class, function (Faker $faker) {
    return [
        'title' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'body' => $faker->realText($maxNbChars = 500, $indexSize = 2),
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'for_all' => $faker->randomElement($array = array(0, 1)),
        'display_type' => $faker->randomElement($array = array(1, 0)),
        'visibility' => $faker->randomElement($array = array(1, 0))
    ];
});

$factory->define(BizOutletMsgRecipient::class, function (Faker $faker) {
    return [
        'msg_id' => $faker->numberBetween($min = 10, $max = 50),
        'recipient' => $faker->numberBetween($min = 1, $max = 50),
        'for_group' => $faker->numberBetween($min = 1, $max = 7),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'seen' => $faker->randomElement($array = array(0, 1)),
        'date_seen' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
    ];
});

$factory->define(BizOutletMsgSeen::class, function (Faker $faker) {
    return [
        'msg_id' => $faker->numberBetween($min = 10, $max = 50),
        'recipient' => $faker->numberBetween($min = 1, $max = 50),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
    ];
});

$factory->define(BusinessAdminRole::class, function (Faker $faker) {
    return [
        'role' => $faker->word,
        'activity_desc' => $faker->realText($maxNbChars = 100, $indexSize = 2)
    ];
});

$factory->define(BusinessCreditPayment::class, function (Faker $faker) {
    return [
        'bcp_id' => $faker->numberBetween($min = 1, $max = 50),
        'customer' => $faker->numberBetween($min = 1, $max = 50),
        'is_outlet' => $faker->randomElement($array = array(0, 1)),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'amount' => $faker->numberBetween($min = 1000, $max = 50000),
        'payment_type' => $faker->randomElement($array = array('cash', 'transfer', 'cheque')),
        'payment_desc' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'receipt_id' => $faker->numberBetween($min = 1000, $max = 5000),
        'bccs_id' =>  $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

$factory->define(BusinessCustomerCredit::class, function (Faker $faker) {
    return [
        'bcc_id' => $faker->numberBetween($min = 1, $max = 50),
        'product_id' => $faker->numberBetween($min = 1, $max = 50),
        'qty' => $faker->numberBetween($min = 1, $max = 50),
        'total_amount' => $faker->numberBetween($min = 1000, $max = 50000),
        'bccs_id' => $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

$factory->define(BusinessCustomerCreditSum::class, function (Faker $faker) {
    return [
        'bccs_id' => $faker->numberBetween($min = 1, $max = 50),
        'customer' => $faker->numberBetween($min = 1, $max = 50),
        'is_outlet' => $faker->randomElement($array = array(0, 1)),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'total_items' => $faker->numberBetween($min = 100, $max = 500),
        'total_amount' => $faker->numberBetween($min = 1000, $max = 50000),
        'deposit' => $faker->numberBetween($min = 1000, $max = 50000),
        'balance' => $faker->numberBetween($min = 1000, $max = 50000),
        'last_payed' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'bss_id' =>  $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(BusinessPaymentResolution::class, function (Faker $faker) {
    return [
        'bpr_id' => $faker->numberBetween($min = 1, $max = 50),
        'payment_id' => $faker->numberBetween($min = 1, $max = 50),
        'payment_amount' => $faker->numberBetween($min = 1000, $max = 50000),
        'bccs_amount_before' => $faker->numberBetween($min = 1000, $max = 50000),
        'bccs_amount_after' => $faker->numberBetween($min = 1000, $max = 50000),
        'bccs_id' =>  $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(BusinessReceivings::class, function (Faker $faker) {
    return [
        'br_id' => $faker->numberBetween($min = 1, $max = 50),
        'product_id' => $faker->numberBetween($min = 1, $max = 50),
        'old_qty' => $faker->numberBetween($min = 1, $max = 50),
        'qty' => $faker->numberBetween($min = 1, $max = 50),
        'price' => $faker->numberBetween($min = 1000, $max = 50000),
        'total_price' => $faker->numberBetween($min = 1000, $max = 50000),
        'cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'total_cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'expiry' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'confirmed' => $faker->randomElement($array = array(0, 1)),
        'brs_id' =>  $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(BusinessReceivingsSum::class, function (Faker $faker) {
    return [
        'brs_id' => $faker->numberBetween($min = 1, $max = 50),
        'supply_code' => $faker->uuid,
        'total_items' => $faker->numberBetween($min = 1, $max = 50),
        'total_amount' => $faker->numberBetween($min = 1, $max = 50),
        'supplier' => $faker->numberBetween($min = 1, $max = 50),
        'driver' => $faker->numberBetween($min = 1, $max = 50),
        'truck_id' => $faker->numberBetween($min = 1909, $max = 9000),
        'driver_phone' => $faker->e164PhoneNumber,
        'mode' => $faker->randomElement($array = array('DD', 'MDD')),
        'size' => $faker->randomElement($array = array(40, 30, 50)),
        'source' => $faker->randomElement($array = array('depot', 'factory')),
        'invoice' => $faker->numberBetween($min = 10000, $max = 50000),
        'descr' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(BusinessStocks::class, function (Faker $faker) {
    return [
        'product_id' => $faker->unique()->numberBetween($min = 1, $max = 50),
        'barcode' => $faker->unique()->numberBetween($min = 100000, $max = 500000),
        'product_name' => $faker->word,
        'product_type' => $faker->randomElement($array = array('type1', 'type2')),
        'cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'price' => $faker->numberBetween($min = 1000, $max = 50000),
        'stock_qty' => $faker->numberBetween($min = 1, $max = 50),
        'expiry' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

$factory->define(BusinessSubscription::class, function (Faker $faker) {
    return [
        'subscription_code' => $faker->uuid,
        'expiry_date' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'expired' => $faker->randomElement($array = array(0, 1)),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

$factory->define(BusinessSuppliers::class, function (Faker $faker) {
    return [
        'company_id' => $faker->numberBetween($min = 1, $max = 50),
        'company_name' => $faker->word,
        'address' => $faker->address,
        'logo' => $faker->imageUrl($width = 640, $height = 480),
        'abbr' => $faker->word,
        'email' => $faker->safeEmail,
        'phone' => $faker->e164PhoneNumber,
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(BusinessSupply::class, function (Faker $faker) {
    return [
        'bs_id' => $faker->numberBetween($min = 1, $max = 50),
        'product_id' => $faker->numberBetween($min = 1, $max = 50),
        'qty' => $faker->numberBetween($min = 1, $max = 50),
        'price' => $faker->numberBetween($min = 1000, $max = 50000),
        'total_price' => $faker->numberBetween($min = 1000, $max = 50000),
        'cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'total_cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'discount' => $faker->numberBetween($min = 500, $max = 1000),
        'bss_id' => $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(BusinessSupplySum::class, function (Faker $faker) {
    return [
        'bss_id' => $faker->numberBetween($min = 1, $max = 50),
        'total_items' => $faker->numberBetween($min = 1, $max = 50),
        'total_amount' => $faker->numberBetween($min = 1, $max = 50),
        'invoice' => $faker->numberBetween($min = 10000, $max = 50000),
        'payment_method' => $faker->randomElement($array = array('part', 'full', 'none')),
        'is_outlet' => $faker->randomElement($array = array(0, 1)),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'customer' => $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

$factory->define(CustomerBusiness::class, function (Faker $faker) {
    return [
        'customer_id' => $faker->numberBetween($min = 1, $max = 50),
        'surname' => $faker->lastName,
        'firstname' => $faker->firstName,
        'address' => $faker->address,
        'avatar' => $faker->imageUrl($width = 640, $height = 480),
        'email' => $faker->safeEmail,
        'phone' => $faker->e164PhoneNumber,
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

/**
 * OUTLETS
 */

$factory->define(CustomerOutlet::class, function (Faker $faker) {
    return [
        'customer_id' => $faker->numberBetween($min = 1, $max = 50),
        'surname' => $faker->lastName,
        'firstname' => $faker->firstName,
        'address' => $faker->address,
        'avatar' => $faker->imageUrl($width = 640, $height = 480),
        'email' => $faker->safeEmail,
        'phone' => $faker->e164PhoneNumber,
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
        'outlet' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});



$factory->define(Outlets::class, function (Faker $faker) {
    return [
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'name' => $faker->company,
        'address' => $faker->address,
        'logo' => $faker->imageUrl($width = 640, $height = 480),
        'abbr' => $faker->word,
        'disabled' => $faker->randomElement($array = array(1, '0')),
        'date_disabled' => $faker->dateTimeThisDecade($max = 'now')->format('Y-m-d H:i:s'),
        'email' => $faker->safeEmail,
        'phone' => $faker->e164PhoneNumber,
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(OutletAdmin::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'user_code' => $faker->uuid,
        'password' => $faker->md5,
        'surname' => $faker->lastName,
        'firstname' => $faker->firstName,
        'phone' => $faker->e164PhoneNumber,
        'email' => $faker->safeEmail,
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'avatar' => $faker->imageUrl($width = 640, $height = 480),
        'email_veri_code' => $faker->uuid,
        'email_verified' => $faker->randomElement($array = array(1, '0')),
        'phone_veri_code' => $faker->randomNumber(7, true),
        'phone_verified' => $faker->randomElement($array = array(1, '0')),
        'last_login' => $faker->dateTimeThisDecade($max = 'now')->format('Y-m-d H:i:s'),
        'last_action' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'user_disabled' => $faker->randomElement($array = array(1, '0')),
        'date_disabled' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'activation_code' => $faker->uuid,
        'activation_code_activated' => $faker->randomElement($array = array(1, '0')),
        'activation_code_expire' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'deleted_at' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
    ];
});



$factory->define(OutletCreditPayments::class, function (Faker $faker) {
    return [
        'ocp_id' => $faker->numberBetween($min = 1, $max = 50),
        'customer' => $faker->numberBetween($min = 1, $max = 50),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'amount' => $faker->numberBetween($min = 1000, $max = 50000),
        'payment_type' => $faker->randomElement($array = array('cash', 'transfer', 'cheque')),
        'payment_desc' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'receipt_id' => $faker->numberBetween($min = 1000, $max = 5000),
        'occs_id' =>  $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

$factory->define(OutletCustomerCredit::class, function (Faker $faker) {
    return [
        'occ_id' => $faker->numberBetween($min = 1, $max = 50),
        'product_id' => $faker->numberBetween($min = 1, $max = 50),
        'qty' => $faker->numberBetween($min = 1, $max = 50),
        'total_amount' => $faker->numberBetween($min = 1000, $max = 50000),
        'occs_id' => $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'outlet' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

$factory->define(OutletCustomerCreditSum::class, function (Faker $faker) {
    return [
        'occs_id' => $faker->numberBetween($min = 1, $max = 50),
        'customer' => $faker->numberBetween($min = 1, $max = 50),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'total_items' => $faker->numberBetween($min = 100, $max = 500),
        'total_amount' => $faker->numberBetween($min = 1000, $max = 50000),
        'deposit' => $faker->numberBetween($min = 1000, $max = 50000),
        'balance' => $faker->numberBetween($min = 1000, $max = 50000),
        'last_payed' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'oss_id' =>  $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


// $factory->define(BusinessPaymentResolution::class, function (Faker $faker) {
//     return [
//         'bpr_id' => $faker->numberBetween($min = 1, $max = 50),
//         'payment_id' => $faker->numberBetween($min = 1, $max = 50),
//         'payment_amount' => $faker->numberBetween($min = 1000, $max = 50000),
//         'bccs_amount_before' => $faker->numberBetween($min = 1000, $max = 50000),
//         'bccs_amount_after' => $faker->numberBetween($min = 1000, $max = 50000),
//         'bccs_id' =>  $faker->numberBetween($min = 1, $max = 50),
//         'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
//         'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
//     ];
// });


$factory->define(OutletReceivings::class, function (Faker $faker) {
    return [
        'or_id' => $faker->numberBetween($min = 1, $max = 50),
        'product_id' => $faker->numberBetween($min = 1, $max = 50),
        'old_qty' => $faker->numberBetween($min = 1, $max = 50),
        'qty' => $faker->numberBetween($min = 1, $max = 50),
        'price' => $faker->numberBetween($min = 1000, $max = 50000),
        'total_price' => $faker->numberBetween($min = 1000, $max = 50000),
        'cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'total_cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'expiry' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'checkout' => $faker->randomElement($array = array(0, 1)),
        'ors_id' =>  $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'outlet' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(OutletReceivingsSum::class, function (Faker $faker) {
    return [
        'ors_id' => $faker->numberBetween($min = 1, $max = 50),
        'total_items' => $faker->numberBetween($min = 1, $max = 50),
        'total_amount' => $faker->numberBetween($min = 1, $max = 50),
        'driver' => $faker->numberBetween($min = 1, $max = 50),
        'truck_id' => $faker->numberBetween($min = 1909, $max = 9000),
        'driver_phone' => $faker->e164PhoneNumber,
        'descr' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'outlet' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(OutletStocks::class, function (Faker $faker) {
    return [
        'product_id' => $faker->numberBetween($min = 1, $max = 50),
        'barcode' => $faker->numberBetween($min = 100000, $max = 500000),
        'product_name' => $faker->word,
        'product_type' => $faker->randomElement($array = array('type1', 'type2')),
        'cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'price' => $faker->numberBetween($min = 1000, $max = 50000),
        'stock_qty' => $faker->numberBetween($min = 1, $max = 50),
        'expiry' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'outlet' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});



$factory->define(OutletSales::class, function (Faker $faker) {
    return [
        'trans_id' => $faker->numberBetween($min = 100000, $max = 5000000),
        'product_id' => $faker->numberBetween($min = 1, $max = 50),
        'qty' => $faker->numberBetween($min = 1, $max = 50),
        'price' => $faker->numberBetween($min = 1000, $max = 50000),
        'total_price' => $faker->numberBetween($min = 1000, $max = 50000),
        'cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'total_cp' => $faker->numberBetween($min = 1000, $max = 50000),
        'discount' => $faker->numberBetween($min = 500, $max = 1000),
        'oss_id' => $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'outlet' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});


$factory->define(OutletSalesSum::class, function (Faker $faker) {
    return [
        'oss_id' => $faker->numberBetween($min = 1, $max = 50),
        'total_items' => $faker->numberBetween($min = 1, $max = 50),
        'total_amount' => $faker->numberBetween($min = 1, $max = 50),
        'invoice' => $faker->numberBetween($min = 10000, $max = 50000),
        'payment_method' => $faker->randomElement($array = array('part', 'full', 'none')),
        'outlet' => $faker->numberBetween($min = 1, $max = 50),
        'customer' => $faker->numberBetween($min = 1, $max = 50),
        'created_by' =>  $faker->numberBetween($min = 1, $max = 50),
        'biz_id' =>  $faker->numberBetween($min = 1, $max = 50),
    ];
});

/**
 * System
 */

 
$factory->define(SystemAdmin::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'role' => $faker->randomElement($array = array(1, 2, 3)),
        'user_code' => $faker->uuid,
        'password' => $faker->md5,
        'surname' => $faker->lastName,
        'firstname' => $faker->firstName,
        'phone' => $faker->e164PhoneNumber,
        'email' => $faker->safeEmail,
        'avatar' => $faker->imageUrl($width = 640, $height = 480),
        'email_veri_code' => $faker->uuid,
        'email_verified' => $faker->randomElement($array = array(1, '0')),
        'phone_veri_code' => $faker->randomNumber(7, true),
        'phone_verified' => $faker->randomElement($array = array(1, '0')),
        'last_login' => $faker->dateTimeThisDecade($max = 'now')->format('Y-m-d H:i:s'),
        'last_action' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'user_disabled' => $faker->randomElement($array = array(1, '0')),
        'date_disabled' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'activation_code' => $faker->uuid,
        'activation_code_activated' => $faker->randomElement($array = array(1, '0')),
        'activation_code_expire' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
        'deleted_at' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
    ];
});



$factory->define(SysBizMsg::class, function (Faker $faker) {
    return [
        'title' => $faker->realText($maxNbChars = 100, $indexSize = 2),
        'body' => $faker->realText($maxNbChars = 500, $indexSize = 2),
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'for_all' => $faker->randomElement($array = array(0, 1)),
        'display_type' => $faker->randomElement($array = array(1, 0)),
        'visibility' => $faker->randomElement($array = array(1, 0))
    ];
});

$factory->define(SysBizMsgRecipient::class, function (Faker $faker) {
    return [
        'msg_id' => $faker->numberBetween($min = 10, $max = 50),
        'recipient' => $faker->numberBetween($min = 1, $max = 50),
        'for_group' => $faker->numberBetween($min = 1, $max = 7),
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
        'seen' => $faker->randomElement($array = array(0, 1)),
        'date_seen' => $faker->dateTimeThisYear($max = '+1 year')->format('Y-m-d H:i:s'),
    ];
});

$factory->define(SysBizMsgSeen::class, function (Faker $faker) {
    return [
        'msg_id' => $faker->numberBetween($min = 10, $max = 50),
        'recipient' => $faker->numberBetween($min = 1, $max = 50),
        'biz_id' => $faker->numberBetween($min = 1, $max = 50),
    ];
});