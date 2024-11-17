<?php

use App\Http\Controllers\Api\FlipController;
use App\Http\Controllers\Api\PasswordController;
use App\Livewire\Gallery;
use App\Livewire\IndexPage;
use App\Livewire\Listings;
use App\Livewire\ShowPost;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;
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

Route::get('/', IndexPage::class)->name('home');
Route::get('/listings', Listings::class)->name('listings');

Route::get('/listings/{slug}', ShowPost::class)->name('show-post');
Route::get('/galleries', Gallery::class)->name('galleries');

Route::get('print', function(){
    return view('print2');
});
Route::get('email', function(){
    return view('emails.agent-application-submitted');

});

Route::get('reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
Route::post('/midtrans/notification', [MidtransController::class, 'handleNotification']);
Route::get('/midtrans/redirect', [MidtransController::class, 'handleRedirect']);

