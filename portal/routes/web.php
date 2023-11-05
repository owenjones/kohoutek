<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdminController, PortalController, RootController};

Route::controller(RootController::class)->group(function () {
  Route::get('/', 'index')->name('root.index');
  Route::get('/sign-up', function (Request $request) {
    return Redirect::route('root.index');
  });
  Route::post('/sign-up', 'signup')->name('root.sign-up-post');
});

Route::prefix('portal')->group(function () {
  # Protected routes
  Route::middleware(['auth:entry'])->group(function () {
    Route::controller(PortalController::class)->group(function () {
      Route::get('/', 'index')->name('portal');
      Route::get('/logout', 'logout')->name('portal.logout');
    });
  });

  # Unprotected routes - info & resending access link
  Route::controller(PortalController::class)->group(function () {
    Route::get('/login', function() {
      return view('portal.auth.login');
    })->name('portal.login');
    Route::get('/login/{id}_{token}', 'login')->name('portal.login');
    Route::match(['get', 'post'], '/resend', 'resend')->name('portal.resend');
  });
});

Route::prefix('admin')->group(function () {
  Route::middleware(['auth:admin'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
      Route::get('/', 'index')->name('admin.index');
    });
  });

  Route::match(['get', 'post'], '/login', [AdminController::class, 'login'])->name('admin.login');
});