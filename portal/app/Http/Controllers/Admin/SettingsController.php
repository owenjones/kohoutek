<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
  public function index(Request $request)
  {
    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'year' => 'required|integer',
        'max_teams' => 'required|integer|min:0',
        'max_group_teams' => 'required|integer|min:0',
        'signup_open' => 'sometimes|string',
        'initial_teams' => 'required|integer|min:1',
      ]);
      
      $validated['signup_open'] = isset($validated['signup_open']);

      settings()->set($validated);
      session()->flash('alert', ['success' => 'Settings updated']);
    }

    return view('admin.settings', [
      'settings' => settings()->all(true),
    ]);
  }

}