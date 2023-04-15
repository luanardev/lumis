<?php

use App\Http\Controllers\Oauth\OauthController;
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
    return view('auth.login');
});

Route::get('/oauth/login', [OauthController::class, 'login'] )->name('oauth.login');
Route::get('/oauth/callback', [OauthController::class, 'callback'] )->name('oauth.callback');
Route::post('/oauth/logout', [OauthController::class, 'logout'])->name('oauth.logout');

require __DIR__.'/auth.php';

