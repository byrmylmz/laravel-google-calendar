<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAccountController;

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
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Managing Google accounts.
Route::name('google.index')->get('google', [GoogleAccountController::class,'index']);
Route::name('google.store')->get('google/oauth', [GoogleAccountController::class,'store']);
Route::name('google.destroy')->delete('google/{googleAccount}', [GoogleAccountController::class,'destroy']);
