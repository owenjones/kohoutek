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
        'group.name' => 'required|max:3',
      ]);

      return response(200);
    }
}
