<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RootController;
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

Route::get('/', [RootController::class, 'index'])->name('root.index');
Route::get('/sign-up', function (Request $request) {
  return Redirect::route('root.index');
});
Route::post('/sign-up', [RootController::class, 'receive_signup'])->name('root.sign-up-post');
