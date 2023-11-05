<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function login(Request $request)
  {
    if($request->isMethod('POST'))
    {
      // do login
    }

    return view('auth.login');
  }

  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
  }

  public function index()
  {

  }
}
