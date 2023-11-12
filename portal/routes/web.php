<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
  PortalController,
  RootController,
};

use App\Http\Controllers\Admin\{
  AuthController,
  EntryController,
  RootController as AdminRootController,
  UserController,
};

Route::controller(RootController::class)->group(function () {
  Route::get('/', 'index')->name('root.index');
  Route::get('/sign-up', function (Request $request) {
    return Redirect::route('root.index');
  });
  Route::post('/sign-up', 'signup')->name('root.sign-up-post');
});

Route::prefix('portal')->group(function () {
  Route::middleware(['auth:entry'])->group(function () {
    Route::controller(PortalController::class)->group(function () {
      Route::get('/', 'index')->name('portal');
      Route::get('/logout', 'logout')->name('portal.logout');
      Route::get('/teams', 'teams')->name('portal.teams');
    });
  });

  Route::controller(PortalController::class)->group(function () {
    Route::get('/login', function() {
      return view('portal.auth.login');
    })->name('portal.need-login');
    Route::get('/login/{id}_{token}', 'login')->name('portal.login');
    Route::match(['get', 'post'], '/resend', 'resend')->name('portal.resend');
  });
});

Route::prefix('admin')->group(function () {
  Route::controller(AuthController::class)->group(function () {
    Route::match(['get', 'post'], '/login', 'login')->name('admin.login');
    Route::get('/logout', 'logout')->middleware(['auth:admin'])->name('admin.logout');
  });

  Route::middleware(['auth:admin'])->group(function () {
    Route::controller(AdminRootController::class)->group(function () {
      Route::get('/', 'index')->name('admin.index');
    });
      
    Route::controller(EntryController::class)->group(function () {
      Route::get('/entries/{filter?}', 'entries')->name('admin.entries');
      Route::get('/entry/{id}', 'viewEntry')->name('admin.entry');
      Route::get('/entry/{id}/teams', 'viewEntryTeams')->name('admin.entry-teams');
      Route::get('/entry/{id}/payments', 'viewEntryPayments')->name('admin.entry-payments');
      Route::match(['get', 'post'], '/entry/{id}/contact', 'contactEntry')->name('admin.entry-contact');
      Route::match(['get', 'post'], '/entry/{id}/resend', 'resendEntryLink')->name('admin.entry-resend');
      Route::match(['get', 'post'], '/entry/{id}/chase', 'chaseEntryVerification')->name('admin.entry-chase');
      Route::match(['get', 'post'], '/entry/{id}/cancel', 'cancelEntry')->name('admin.entry-cancel');
    });

    Route::controller(UserController::class)->group(function () {
      Route::get('/users', 'index')->name('admin.users');
      Route::get('/user/{id}', 'view')->name('admin.user.view');
      Route::post('/user/add', 'add')->name('admin.user.add');
      Route::match(['get', 'post'], '/user/{id}/modify', 'modify')->name('admin.user.modify');
      Route::match(['get', 'post'], '/user/{id}/delete', 'delete')->name('admin.user.delete');
    });
  });
});