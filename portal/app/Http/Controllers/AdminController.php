<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AdminController extends Controller
{
  public function login(Request $request)
  {
    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
      ]);

      if(Auth::guard('admin')->attempt($validated))
      {
        $request->session()->regenerate();
        return redirect()->route('admin.index');
      }

      return back()->withErrors([
        'email' => 'These credentials do not match our records',
      ])->onlyInput('email');
    }

    return view('admin.auth.login');
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
