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
    $submitted = false;
    session()->forget('alert');

    if($request->isMethod('POST'))
    {
      $validated = $request->validate(['email' => ['required', 'email']]);

      $entry = Entry::where('contact_email', $validated['email'])->get();
      if($entry)
      {
        // TODO: make email & send it
      }

      session()->flash('alert', [
        'primary' => 'If an entry with this contact emails exists a new portal link has been sent to it.'
      ]);
      $submitted = true;
    }

    return view('portal.auth.resend-link', ['submitted' => $submitted]);
  }

  public function index()
  {
    $entry = Entry::first(); # for testing - just grab first in db

    return view('portal.index', [
      'entry' => $entry
    ]);
  }
}
