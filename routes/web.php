<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
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
    Route::get('sign-in', [SignInController::class, 'signin'])->name('signin');
    Route::post('sign-in', [SignInController::class, 'signinAction']);
    Route::get('sign-up', [SignUpController::class, 'signup'])->name('signup');
    Route::post('sign-up', [SignUpController::class, 'signupAction']);
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
    Route::get('sign-out', [SignInController::class, 'signout'])->name('signout');
    Route::group(['middleware' => 'checkRole:1', 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('complaint', [HandleComplaintController::class, 'index'])->name('complaint');
        Route::get('complaint/data', [HandleComplaintController::class, 'data'])->name('complaint.data');
        Route::post('complaint/update-status', [HandleComplaintController::class, 'updateStatus'])->name('complaint.update-status');
        Route::get('complaint/get-images/{complaintId}', [HandleComplaintController::class, 'getImages'])->name('complaint.get-images');
        Route::get('complaint/get-history/{complaintId}', [HandleComplaintController::class, 'getHistory'])->name('complaint.get-history');
    });
    
    Route::group(['middleware' => 'checkRole:2', 'prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('complaint', [ComplaintController::class, 'index'])->name('complaint');
        Route::get('complaint/data', [ComplaintController::class, 'data'])->name('complaint.data');
        Route::get('complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');
        Route::post('complaint/add', [ComplaintController::class, 'addAction']);
        Route::post('complaint/upload-images', [ComplaintController::class, 'uploadImages'])->name('complaint.upload-images');
        Route::get('complaint/history/{id}', [ComplaintController::class, 'history'])->name('complaint.history');
        Route::get('complaint/get-images/{complaintId}', [ComplaintController::class, 'getImages'])->name('complaint.get-images');
        Route::get('complaint/get-history/{complaintId}', [ComplaintController::class, 'getHistory'])->name('complaint.get-history');
    });    
});
