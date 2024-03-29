<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\{Entry, Team, Update};
use App\Mail\ResendLink;

use Rmunate\Utilities\SpellNumber;

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
    $canAddTeam = (count($entry->teams) < settings()->get('max_group_teams')) && (Team::count() < settings()->get('max_teams'));
    $teams = SpellNumber::value(settings()->get('max_group_teams', 0))->toLetters() . " " . Str::plural('team', settings()->get('max_group_teams'));

    return view('portal.teams', [
      'entry' => $entry,
      'canAddTeam' => $canAddTeam,
      'teams' => $teams
    ]);
  }

  public function addTeam()
  {
    $entry = Auth::guard('entry')->user();

    if((count($entry->teams) < settings()->get('max_group_teams')) && (Team::count() < settings()->get('max_teams')))
    {
      Team::create([
        'name' => $entry->name,
        'entry_id' => $entry->id
      ]);
    }
    else
    {
      session()->flash('alert', ['danger' => 'Maximum number of teams has been reached']);
    }

    return redirect()->route('portal.teams');
  }

  public function renameTeam(Request $request, $id)
  {
    $team = Team::find($id);
    if(!isset($team) || $team->entry()->id != Auth::guard('entry')->user()->id)
    {
      abort(403);
    }

    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'name' => 'required|max:255|unique:teams'
      ]);

      $team->name = $validated["name"];
      $team->save();

      session()->flash('alert', ['success' => 'Your team was renamed']);
      return redirect()->route('portal.teams');
    }

    return view('portal.team.rename', ['entry' => $team->entry(), 'team' => $team]);
  }

  public function updates()
  {
    $entry = Auth::guard('entry')->user();
    $updates = Update::orderBy('created_at', 'desc')->simplePaginate(10);

    return view('portal.updates', [
      'entry' => $entry,
      'updates' => $updates,
    ]);
  }
}
