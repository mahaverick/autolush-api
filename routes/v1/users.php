<?php

Route::group(['namespace' => 'Users', 'as' => 'users.'], function () {
    Route::prefix('users')->group(function () {
        Route::get('categories', ['as' => 'categories.index', 'uses' => 'CategoriesController@index']);

        Route::get('products', ['as' => 'products.index', 'uses' => 'ProductsController@index']);
        Route::get('products/{product}', ['as' => 'products.show', 'uses' => 'ProductsController@show']);

        Route::group(['namespace' => 'Auth', 'as' => 'auth.'], function () {
            Route::prefix('auth')->group(function () {
                Route::post('register', ['as' => 'register', 'uses' => 'RegisterController@register']);
                Route::post('login', ['as' => 'login', 'uses' => 'LoginController@login']);
            });
        });

        Route::middleware(['auth:api'])->group(function () {
            Route::get('profiles/me', ['as' => 'profiles.me', 'uses' => 'ProfilesController@me']);

            Route::get('cart', ['as' => 'cart.index', 'uses' => 'CartsController@index']);
            Route::post('cart', ['as' => 'cart.store', 'uses' => 'CartsController@store']);
            Route::patch('cart/{variation}', ['as' => 'cart.update', 'uses' => 'CartsController@update']);
            Route::delete('cart/{variation}', ['as' => 'cart.destroy', 'uses' => 'CartsController@destroy']);
        });
    });
});
