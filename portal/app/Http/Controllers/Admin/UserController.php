<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
  public function index()
  {
    return view('admin.users.list', ['users' => User::all()]);
  }

  public function view($id)
  {
    return view('admin.users.view', ['user' => User::findOrFail($id)]);
  }

  public function add(Request $request)
  {

  }

  public function delete(Request $request, $id)
  {

  }

  public function modify(Request $request, $id)
  {
    
  }
}
