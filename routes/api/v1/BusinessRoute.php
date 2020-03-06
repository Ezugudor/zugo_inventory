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
            $api->post('business/stocks', [
                'as' => 'business_stock.viewall',
                'uses' => 'BusinessStocksController@add',
            ]);

            $api->put('business/stocks/{id}', [
                'as' => 'business_stock.update',
                'uses' => 'BusinessStocksController@update',
            ]);

            $api->delete('business/stocks/{id}', [
                'as' => 'business_stock.delete',
                'uses' => 'BusinessStocksController@delete',
            ]);

            $api->get('business/{bizId}/stocks', [
                'as' => 'business_stock.viewall',
                'uses' => 'BusinessStocksController@showAllByBusiness',
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
            $api->get('business/customers', [
                'as' => 'business_customer.viewall',
                'uses' => 'CustomerController@showAllByBusiness',
            ]);

            $api->get('business/customers/{id}', [
                'as' => 'business_customer.view',
                'uses' => 'CustomerController@show',
            ]);

            $api->post('business/customers', [
                'as' => 'business_customer.add',
                'uses' => 'CustomerController@add',
            ]);

            $api->put('business/customers/{id}', [
                'as' => 'business_customer.update',
                'uses' => 'CustomerController@update',
            ]);

            $api->delete('business/customers/{id}', [
                'as' => 'business_customer.delete',
                'uses' => 'CustomerController@delete',
            ]);

            // old
            $api->get('business/{bizId}/customers', [
                'as' => 'business_customer.viewall',
                'uses' => 'CustomerController@showAllByBusiness',
            ]);

            $api->get('business/{bizId}/customers/{id}', [
                'as' => 'business_customer.view',
                'uses' => 'CustomerController@show',
            ]);

            //Business Outlets Routes
            $api->get('business/outlets', [
                'as' => 'business_outlet.viewall',
                'uses' => 'OutletsController@showAllInfoByBusiness',
            ]);

            $api->get('business/outlets/{id}', [
                'as' => 'business_outlet.view',
                'uses' => 'OutletsController@show',
            ]);

            $api->post('business/outlets', [
                'as' => 'business_outlet.add',
                'uses' => 'OutletsController@add',
            ]);

            $api->put('business/outlets/{id}', [
                'as' => 'business_outlet.update',
                'uses' => 'OutletsController@update',
            ]);

            $api->delete('business/outlets/{id}', [
                'as' => 'business_outlet.delete',
                'uses' => 'OutletsController@delete',
            ]);

            //old
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

            $api->delete('business/receivings/{id}', [
                'as' => 'business_receivings.delete',
                'uses' => 'BusinessReceivingsController@delete',
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
            $api->post('business/drivers', [
                'as' => 'business_driver.add',
                'uses' => 'DriverController@add',
            ]);

            $api->put('business/drivers/', [
                'as' => 'business_driver.update',
                'uses' => 'DriverController@update',
            ]);

            $api->delete('business/drivers/{id}', [
                'as' => 'business_driver.delete',
                'uses' => 'DriverController@delete',
            ]);

            $api->get('business/drivers', [
                'as' => 'drivers.viewall',
                'uses' => 'DriverController@showAllByBusiness',
            ]);

            $api->get('business/drivers/{id}', [
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

            $api->get('business/supply/filter/{id}', [
                'as' => 'business_supply.filter',
                'uses' => 'BusinessSupplyController@filter',
            ]);

            $api->post('business/supply', [
                'as' => 'business_supply.distribute',
                'uses' => 'BusinessSupplyController@distribute',
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
