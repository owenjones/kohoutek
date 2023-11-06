<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Entry;
use App\Mail\ResendLink;

class PortalController extends Controller
{

  public function login(Request $request, $id, $token)
  {
    $entry = Entry::find($id);
    
    if($entry == NULL || $entry->auth_token != $token)
    {
      return redirect(route('portal.need-login'));
    } 
    else 
    {
      $request->session()->regenerate();
      Auth::guard('entry')->login($entry);
      if(!$request->query('noverify', false)) {
        $entry->verify();
      }
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
        $entry->resendLoginLink();
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

  public function teams()
  {
    $entry = Auth::guard('entry')->user();

    return view('portal.teams', [
      'entry' => $entry
    ]);
  }
}
