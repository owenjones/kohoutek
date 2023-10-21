<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RootController extends Controller
{
    public function index()
    {
      return view('root.index');
    }

    public function receive_signup(Request $request)
    {
      $validated = $request->validate([
        'county' => 'required',
        'district' => 'required',
        'group' => 'required|max:255',
        'name' => 'required|max:255',
        'email' => 'required|confirmed|email',
        'rules' => 'required'
      ]);

      return "Accepted";
    }
}
