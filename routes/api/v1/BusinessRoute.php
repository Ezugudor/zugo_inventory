<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$api = app('Dingo\Api\Routing\Router');
$api->version(
    'v1',
    [
        'namespace' => 'App\Api\V1\Controllers'
    ],
    function ($api) {



        $api->group(['middleware' => ['auth:api']], function ($api) {
            $api->get('business/users', [
                'as' => 'authorization.viewall',
                'uses' => 'BusinessAdminController@showAll',
            ]);

            $api->get('business/users/{id}', [
                'as' => 'authorization.view',
                'uses' => 'BusinessAdminController@show',
            ]);

            $api->post('business/logout', [
                'as' => 'authorization.logout',
                'uses' => 'BusinessAdminController@logout',
            ]);

            // Business Stocks Routes
            $api->get('business/{bizId}/stocks', [
                'as' => 'business_stock.viewall',
                'uses' => 'BusinessStocksController@showAll',
            ]);

            $api->get('business/{bizId}/stocks/{id}', [
                'as' => 'business_stock.view',
                'uses' => 'BusinessStocksController@show',
            ]);

            $api->post('business/{bizId}/add', [
                'as' => 'business_stock.add',
                'uses' => 'BusinessStocksController@add',
            ]);

            //Business Credit Payment Routes
            $api->get('business/{bizId}/credit-payment', [
                'as' => 'business_credit_payment.viewall',
                'uses' => 'BusinessCreditPaymentController@showAllByBusiness',
            ]);

            $api->get('business/{bizId}/credit-payment/{id}', [
                'as' => 'business_credit_payment.view',
                'uses' => 'BusinessCreditPaymentController@show',
            ]);

            // Business Customer Credit Routes
            $api->get('business/{bizId}/customer-credit', [
                'as' => 'business_customer_credit.viewall',
                'uses' => 'BusinessCustomerCreditController@showAll',
            ]);

            $api->get('business/{bizId}/customer-credit/{id}', [
                'as' => 'business_customer_credit.view',
                'uses' => 'BusinessCustomerCreditController@show',
            ]);

            //Business Customer Routes
            $api->get('business/{bizId}/customers', [
                'as' => 'business_customer.viewall',
                'uses' => 'CustomerController@showAllByBusiness',
            ]);

            $api->get('business/{bizId}/customers/{id}', [
                'as' => 'business_customer.view',
                'uses' => 'CustomerController@show',
            ]);

            //Business Outlets Routes
            $api->get('business/{bizId}/outlets', [
                'as' => 'business_outlet.viewall',
                'uses' => 'OutletsController@showAllInfoByBusiness',
            ]);

            $api->get('business/{bizId}/outlets/{id}', [
                'as' => 'business_outlet.view',
                'uses' => 'OutletsController@show',
            ]);

            //Business Receivings Routes
            $api->get('business/{bizId}/receivings', [
                'as' => 'receivings.viewall',
                'uses' => 'BusinessReceivingsController@showAllByBusiness',
            ]);

            $api->get('business/{bizId}/receivings/{id}', [
                'as' => 'receivings.view',
                'uses' => 'BusinessReceivingsController@show',
            ]);

            $api->post('business/receivings', [
                'as' => 'receivings.add',
                'uses' => 'BusinessReceivingsController@add',
            ]);
            $api->put('business/receivings/{id}', [
                'as' => 'receivings.process',
                'uses' => 'BusinessReceivingsController@process',
            ]);

            //Business Drivers Routes
            $api->get('business/{bizId}/drivers', [
                'as' => 'drivers.viewall',
                'uses' => 'DriverController@showAllByBusiness',
            ]);

            $api->get('business/{bizId}/drivers/{id}', [
                'as' => 'drivers.view',
                'uses' => 'DriverController@show',
            ]);



            // Business Supply
            $api->get('business/supply', [
                'as' => 'business_supply.viewall',
                'uses' => 'BusinessSupplyController@showAll',
            ]);

            $api->get('business/supply/{id}', [
                'as' => 'business_supply.view',
                'uses' => 'BusinessSupplyController@show',
            ]);

            $api->post('business/supply', [
                'as' => 'business_supply.add',
                'uses' => 'BusinessSupplyController@add',
            ]);

            $api->delete('business/supply/{id}', [
                'as' => 'business_supply.delete',
                'uses' => 'BusinessSupplyController@delete',
            ]);
        });

        $api->post('business/users', [
            'as' => 'authorization.register',
            'uses' => 'BusinessAdminController@register',
        ]);
        // $api->group(['middleware' => ['cors']], function ($api) {
        $api->post('business/login', [
            'as' => 'authorization.login',
            'uses' => 'BusinessAdminController@login',
        ]);
        // });
    }
);
