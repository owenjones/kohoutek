<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Entry;

class PortalController extends Controller
{

  public function login($id, $token)
  {
    $entry = Entry::find($id);
    
    if($entry == NULL || $entry->auth_token != $token)
    {
      return redirect(route('portal.need-login'));
    } 
    else 
    {
      // Auth::guard('entry')->login($entry);
      return redirect()->route('portal');
    }
  }

  public function resendLink(Request $request)
  {
    return view('portal.auth.resend-link');
  }

  public function index()
  {
    $entry = Entry::first(); # for testing - just grab first in db

    return view('portal.index', [
      'entry' => $entry
    ]);
  }
}
