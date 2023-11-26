<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\HandleComplaintController;
use App\Http\Controllers\User\ComplaintController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('signin');
});

Route::middleware('guest')->group(function ()
{
    Route::get('sign-in', [AuthController::class, 'signin'])->name('signin');
    Route::post('sign-in', [AuthController::class, 'signinaction']);
});

Route::middleware('auth')->group(function ()
{
    Route::get('/home', function () {
        $userRole = auth()->user()->role_id;
        if ($userRole == 1) {
            return redirect()->route('admin.complaint');
        } elseif ($userRole == 2) {
            return redirect()->route('user.complaint');
        } else {
            return redirect()->route('sign-in');
        }
    });
    Route::get('sign-out', [AuthController::class, 'signout'])->name('signout');
    Route::group(['middleware' => 'checkRole:1'], function () {
        Route::get('admin/complaint', [HandleComplaintController::class, 'index'])->name('admin.complaint');
    });
    
    Route::group(['middleware' => 'checkRole:2'], function () {
        Route::get('user/complaint', [ComplaintController::class, 'index'])->name('user.complaint');
    });
});
