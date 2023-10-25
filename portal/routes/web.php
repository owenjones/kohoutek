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
  // Route::middleware(['entry'])->group(function () {
    Route::controller(PortalController::class)->group(function () {
      Route::get('/', [PortalController::class, 'index']);
    });
  // });

  # Unprotected routes - info & resending access link
  Route::controller(PortalController::class)->group(function () {
    Route::get('/login', 'needLogin')->name('portal.need-login');
    Route::get('/login/{id}_{key}', 'login')->name('portal.login');
    Route::match(['get', 'post'], '/resend', 'resendKey')->name('portal.resend-key');
  });
});

Route::prefix('admin')->group(function () {
  Route::middleware(['auth'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
      Route::get('/', 'index')->name('admin.index');
    });
  });

  Route::match(['get', 'post'], '/login', [AdminController::class, 'login'])->name('admin.login');
});