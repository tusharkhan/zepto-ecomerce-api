<?php

use App\Http\Controllers\Auth\Admin\AdminAuthController;
use App\Http\Controllers\Auth\User\UserAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return sendError(
        'Error',
        404,
        ['Unable to find the page you are looking for.']
    );
})->name('unavailable');


Route::prefix('api')->group(function () {
    // Auth Routes for Admin
    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    });

    // Auth Routes for User
    Route::prefix('user')->group(function () {
        Route::post('login', [UserAuthController::class, 'login'])->name('user.login');
    });
});
