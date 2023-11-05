<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Mail};

use App\Models\Entry;
use App\Mail\ResendLink;

class PortalController extends Controller
{

  public function login($id, $token)
  {
    $entry = Entry::find($id);
    
    if($entry == NULL || $entry->auth_token != $token)
    {
      return redirect(route('portal.login'));
    } 
    else 
    {
      $entry->verify();
      Auth::guard('entry')->login($entry);
      return redirect()->route('portal');
    }
  }

  public function logout()
  {
    Auth::guard('entry')->logout();
    return redirect()->route('root.index');
  }

  public function resend(Request $request)
  {
    $submitted = false;
    session()->forget('alert');

    if($request->isMethod('POST'))
    {
      $validated = $request->validate(['email' => ['required', 'email']]);

      $entry = Entry::where('contact_email', $validated['email'])->first();
      if($entry)
      {
        Mail::to($entry->contact_email)->queue(new ResendLink($entry));
      }

      session()->flash('alert', [
        'primary' => 'If an entry with this contact emails exists a new portal link has been sent to it.'
      ]);
      $submitted = true;
    }

    return view('portal.auth.resend', ['submitted' => $submitted]);
  }

  public function index()
  {
    $entry = Auth::guard('entry')->user();

    return view('portal.index', [
      'entry' => $entry
    ]);
  }
}
