<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
  Auth, 
  DB,
  Mail,
};

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\{
  ResetPassword,
  UserAdded,
};

class UserController extends Controller
{
  public function index()
  {
    return view('admin.user.list', ['users' => User::all()]);
  }


  public function add(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users',
    ]);

    User::insert($validated);

    $token = Str::random(50);

    DB::table('password_reset_tokens')->upsert([
      'email' => $validated['email'],
      'token' => $token,
      'created_at' => now(),
    ], ['email'], ['token', 'created_at']);

    Mail::to($validated['email'])->queue(new UserAdded($validated['name'], route('admin.login.set-password', ['token' => $token])));

    session()->flash('alert', ['success' => 'User added']);
    return redirect()->route('admin.users');
  }

  public function delete(Request $request, $id)
  {
    $user = User::find($id);

    if($request->isMethod('POST'))
    {
      if($user->id == Auth::guard('admin')->user()->id)
      {
        session()->flash('alert', ['danger' => 'You cannot delete yourself']);
        return redirect()->route('admin.users');
      }

      if($request->input('confirm') == ("DELETE " . $user->email))
      {
        $user->delete();
        session()->flash('alert', ['warning' => 'User was deleted']);
        return redirect()->route('admin.users');
      }
      else
      {
        return back()->withErrors(['confirm' => 'Confirmation phrase not entered correctly']);
      }
    }

    return view('admin.user.delete', ['user' => $user]);
  }

  public function passwordReset(Request $request, $id)
  {
    $user = User::find($id);

    if(isset($user))
    {
      $token = Str::random(50);

      DB::table('password_reset_tokens')->upsert([
        'email' => $user->email,
        'token' => $token,
        'created_at' => now(),
      ], ['email'], ['token', 'created_at']);

      Mail::to($user->email)->queue(new ResetPassword($user, route('admin.login.set-password', ['token' => $token])));
      session()->flash('alert', ['success' => 'Password reset requested']);
    }

    return redirect()->route('admin.users');
  }
}
