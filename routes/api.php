<?php

use App\Http\Controllers\Api\AddOnController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ManifestController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TravelPackageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::post('manifest/{id}', [ManifestController::class, 'register']);
    Route::put('manifest/{id}/{manifest_id}', [ManifestController::class, 'update']);
    Route::get('manifest/{id}', [ManifestController::class, 'byID']);
    Route::delete('manifest/{id}/{manifest_id}', [ManifestController::class, 'destroy']);

    Route::get('transaction/{id}', [TransactionController::class, 'show']);
    Route::get('transaction', [TransactionController::class, 'index']);
    Route::post('transaction', [TransactionController::class, 'store']);
    Route::delete('transaction/{id}', [TransactionController::class, 'destroy']);
    Route::put('transaction/{id}', [TransactionController::class, 'update']);

    Route::put('profile', [ProfileController::class, 'update']);
    Route::get('profile', [ProfileController::class, 'show']);

    Route::post('change-password', [PasswordController::class, 'changePassword']);
});

Route::post('forgot-password', [PasswordController::class, 'sendResetLinkEmail']);
Route::post('reset-password', [PasswordController::class, 'resetPassword']);

Route::get('add_on_product', [AddOnController::class, 'index']);
Route::post('travel-package', [TravelPackageController::class, 'index']);
Route::get('travel-package/{id}', [TravelPackageController::class, 'show']);
Route::get('products', [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'show']);