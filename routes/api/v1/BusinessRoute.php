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



        $api->group(['middleware' => 'auth:api'], function ($api) {
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
            $api->get('business/stocks', [
                'as' => 'business_stock.viewall',
                'uses' => 'BusinessStocksController@showAll',
            ]);

            $api->get('business/stocks/{id}', [
                'as' => 'business_stock.view',
                'uses' => 'BusinessStocksController@show',
            ]);

            $api->post('business/add', [
                'as' => 'business_stock.add',
                'uses' => 'BusinessStocksController@add',
            ]);


        });

        $api->post('business/users', [
            'as' => 'authorization.register',
            'uses' => 'BusinessAdminController@register',
        ]);

        $api->post('business/login', [
            'as' => 'authorization.login',
            'uses' => 'BusinessAdminController@login',
        ]);


    }
);
