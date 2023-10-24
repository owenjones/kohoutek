<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{PortalController, RootController};

Route::get('/', [RootController::class, 'index'])->name('root.index');
Route::get('/sign-up', function (Request $request) {
  return Redirect::route('root.index');
});
Route::post('/sign-up', [RootController::class, 'receive_signup'])->name('root.sign-up-post');

Route::get('/portal/', [PortalController::class, 'index']);