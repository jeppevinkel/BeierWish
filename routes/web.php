<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
})->name('home');

Route::get('/me', function () {
    $user = auth()->user();
//    $lists = $user->wishLists()->with('wishes')->get();

//    $user->load(['wishLists']);
    $user->wishLists->load(['wishes']);

    return $user;
})->middleware('auth');

Route::get('/me/wishlists', function () {
    return auth()->user()->wishLists;
})->middleware('auth');

Route::get('/auth/{provider}', [LoginController::class, 'redirectToProvider'])->name('auth');
Route::get('/auth/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('auth.callback');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');
