<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| Public Authentication Routes
|--------------------------------------------------------------------------
*/

/*
 * Normal Registration
 *
 * Allowed:
 * employee
 * freelancer
 * client
 *
 * Admin is NOT allowed.
 */
Route::post('/register', [AuthController::class, 'register']);


/*
 * Login
 */
Route::post('/login', [AuthController::class, 'login']);



/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
     * Get currently authenticated user
     */
    Route::get('/me', [AuthController::class, 'me']);


    /*
     |--------------------------------------------------------------------------
     | Admin Routes
     |--------------------------------------------------------------------------
     */

    /*
     * Secure Admin Registration
     *
     * Only Admin can create another Admin.
     */
    Route::post(
        '/admin/register',
        [AuthController::class, 'registerAdmin']
    )->middleware('role:admin');


    /*
     * Admin Test
     */
    Route::get('/admin/test', function () {

        return response()->json([
            'success' => true,
            'message' => 'Welcome Admin',
        ]);

    })->middleware('role:admin');



    /*
     |--------------------------------------------------------------------------
     | Employee Routes
     |--------------------------------------------------------------------------
     */

    Route::get('/employee/test', function () {

        return response()->json([
            'success' => true,
            'message' => 'Welcome Employee',
        ]);

    })->middleware('role:employee');



    /*
     |--------------------------------------------------------------------------
     | Freelancer Routes
     |--------------------------------------------------------------------------
     */

    Route::get('/freelancer/test', function () {

        return response()->json([
            'success' => true,
            'message' => 'Welcome Freelancer',
        ]);

    })->middleware('role:freelancer');



    /*
     |--------------------------------------------------------------------------
     | Client Routes
     |--------------------------------------------------------------------------
     */

    Route::get('/client/test', function () {

        return response()->json([
            'success' => true,
            'message' => 'Welcome Client',
        ]);

    })->middleware('role:client');

});