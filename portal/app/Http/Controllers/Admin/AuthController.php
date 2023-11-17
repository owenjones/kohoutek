<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
  Auth, 
  DB
};
use Illuminate\Validation\Rules\Password;

use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
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

  public function setPassword(Request $request, $token)
  {
    $reset = DB::table('password_reset_tokens')->where('token', $token)->first();

    if(!isset($reset))
    {
      session()->flash('alert', ['danger' => 'Invalid token']);
      return redirect()->route('admin.login');
    }

    if($reset->created_at < now()->subDays(1))
    {
      DB::table('password_reset_tokens')->where('token', $token)->delete();
      session()->flash('alert', ['danger' => 'Token has expired']);
      return redirect()->route('admin.login');
    }

    $user = User::where('email', $reset->email)->first();

    if(!isset($user))
    {
      DB::table('password_reset_tokens')->where('token', $token)->delete();
      return redirect()->route('admin.login');
    }

    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'password' => [
          'required', 
          'confirmed', 
          Password::min(8)->mixedCase()->numbers(),
        ],
      ]);

      $user->password = $validated['password'];
      $user->save();
      DB::table('password_reset_tokens')->where('token', $token)->delete();
      Auth::guard('admin')->login($user);
      return redirect()->route('admin.index');
    }
    
    return view('admin.auth.set-password', ['token' => $token]);
  }

  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
  }
}