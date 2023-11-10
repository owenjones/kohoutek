<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\{District, Entry, User};

class AdminController extends Controller
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

  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
  }

  public function index()
  {
    $districts = District::all();

    $entries = Entry::all();
    $scouts = $entries->reject(function (Entry $entry) { 
      return $entry->district()->county()->code == "bsg" || $entry->district()->county()->code == "sn";
    });
    $guides = $entries->reject(function (Entry $entry) { 
      return $entry->district()->county()->code == "avon";
    });

    return view('admin.index', [
      'districts' => $districts,
      'entries' => count($entries),
      'scouts' => count($scouts),
      'guides' => count($guides),
    ]);
  }

  public function entries($filter = false)
  {
    $entries = Entry::all();
    if($filter) {
      $entries = $entries->reject(function (Entry $entry) use ($filter) {
        return ($filter == "verified") ? !$entry->verified : $entry->verified;
      });
    }

    return view('admin.entry.list', ['entries' => $entries]);
  }

  public function viewEntry($id)
  {
    return view('admin.entry.view', ['entry' => Entry::findOrFail($id) ]);
  }

  public function resendEntryLink(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'id' => 'required|exists:entries',
      ]);

      $entry->resendLoginLink();
      session()->flash('alert', ['success' => 'The portal link was resent to the entry']);
      return redirect()->route('admin.entry', ['id' => $entry->id]);
    }

    return view('admin.entry.link', ['entry' => $entry]);
  }

  public function cancelEntry(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'id' => 'required|exists:entries',
        'verify_id' => 'required|same:id'
      ]);

      $entry->cancel($request->input('silent'));

      session()->flash('alert', ['success' => 'Entry cancelled']);
      return redirect()->route('admin.entries');
    }
    
    return view('admin.entry.cancel', ['entry' => $entry]);
  }
}
