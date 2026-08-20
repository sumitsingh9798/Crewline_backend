<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;


/*
|--------------------------------------------------------------------------
| Public Authentication
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
|
| Anyone can only login.
| Registration is NOT public.
|
*/

Route::post('/login', [
    AuthController::class,
    'login'
]);


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    Route::get('/me', [
        AuthController::class,
        'me'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Admin Only
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->prefix('admin')->group(function () {

        /*
        | Get all users
        */
        Route::get('/users', [
            AdminUserController::class,
            'index'
        ]);


        /*
        | Create user
        */
        Route::post('/users', [
            AdminUserController::class,
            'store'
        ]);


        /*
        | Update user
        */
        Route::put('/users/{user}', [
            AdminUserController::class,
            'update'
        ]);


        /*
        | Delete user
        */
        Route::delete('/users/{user}', [
            AdminUserController::class,
            'destroy'
        ]);

    });


    /*
    |--------------------------------------------------------------------------
    | Role Test Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/test', function () {

        return response()->json([
            'success' => true,
            'message' => 'Welcome Admin',
        ]);

    })->middleware('role:admin');


    Route::get('/employee/test', function () {

        return response()->json([
            'success' => true,
            'message' => 'Welcome Employee',
        ]);

    })->middleware('role:employee');


    Route::get('/freelancer/test', function () {

        return response()->json([
            'success' => true,
            'message' => 'Welcome Freelancer',
        ]);

    })->middleware('role:freelancer');


    Route::get('/client/test', function () {

        return response()->json([
            'success' => true,
            'message' => 'Welcome Client',
        ]);

    })->middleware('role:client');

});