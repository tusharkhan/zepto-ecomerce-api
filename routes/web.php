<?php

use App\Http\Controllers\Auth\Admin\AdminAuthController;
use App\Http\Controllers\Auth\User\UserAuthController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

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
        'Unauthorized',
        ['error' => 'Unauthorized attempt'],
        Response::HTTP_UNAUTHORIZED,
    );
})->name('unavailable');


Route::prefix('api')->group(function () {
    // Auth Routes for Admin
    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    });

    // Auth Routes for User
    Route::prefix('user')->group(function () {
        Route::get('/', [UserAuthController::class, 'me'])->name('user.index');
        Route::post('login', [UserAuthController::class, 'login'])->name('user.login');
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
    });
});


