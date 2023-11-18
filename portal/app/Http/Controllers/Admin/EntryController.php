<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Mail};

use App\Http\Controllers\Controller;
use App\Models\{
  Entry,
  Payment,
  Team,
};
use App\Mail\{
  EntryContact,
  PaymentReceived,
};

class EntryController extends Controller
{
  public function index($filter = false)
  {
    $entries = Entry::all();
    if($filter) {
      $entries = $entries->reject(function (Entry $entry) use ($filter) {
        return ($filter == "verified") ? !$entry->verified : $entry->verified;
      });
    }

    return view('admin.entry.list', ['entries' => $entries]);
  }

  public function view($id)
  {
    return view('admin.entry.view', ['entry' => Entry::findOrFail($id) ]);
  }

  public function teams($id)
  {
    return view('admin.entry.teams', ['entry' => Entry::findOrFail($id) ]);
  }

  public function addTeam(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    Team::create([
      'name' => $entry->name,
      'entry_id' => $entry->id
    ]);

    return redirect()->route('admin.entry.teams', ['id' => $entry->id]);
  }

  public function deleteTeam(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);
    $team = Team::find($request->input('team_id'));
    $team->delete();

    return redirect()->route('admin.entry.teams', ['id' => $entry->id]);
  }

  public function markTeamPaid(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    $team = Team::find($request->input('team_id'));
    $team->payment_received = true;
    $team->save();

    return redirect()->route('admin.entry.teams', ['id' => $entry->id]);
  }

  public function payments(Request $request, $id)
  {
    return view('admin.entry.payments', ['entry' => Entry::findOrFail($id)]);
  }

  public function recordPayment(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    $validated = $request->validate([
      'reference' => 'string|nullable',
      'type' => 'required', # TODO: faff with enums to make this solid
      'amount_pounds' => 'required|integer',
      'amount_pence' => 'required|integer',
      'entry_id' => 'required|exists:entries,id'
    ]);

    $payment = Payment::create($validated);

    if($request->input('team'))
    {
      Team::whereIn('id', $request->input('team'))->update(['payment_received' => true]);
    }

    Mail::to($entry->contact_email)->queue(new PaymentReceived($entry, $payment));
    session()->flash('alert', ['success' => 'Payment recorded']);
    return redirect()->route('admin.entry.payments', ['id' => $entry->id]);
  }

  public function contact(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'id' => 'required|exists:entries',
        'subject' => 'required|max:30',
        'message' => 'required'
      ]);

      Mail::to($entry->contact_email)->queue(new EntryContact($entry, $validated["subject"], $validated["message"]));
      session()->flash('alert', ['success' => 'The message was sent to the entry']);
      return redirect()->route('admin.entry.view', ['id' => $entry->id]);
    }

    return view('admin.entry.contact', ['entry' => $entry]);
  }

  public function resend(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'id' => 'required|exists:entries',
      ]);

      $entry->resendLoginLink();
      session()->flash('alert', ['success' => 'The portal link was resent to the entry']);
      return redirect()->route('admin.entry.view', ['id' => $entry->id]);
    }

    return view('admin.entry.link', ['entry' => $entry]);
  }

  public function chase(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    if($entry->verified)
    {
      session()->flash('alert', ['warning' => 'This entry has already been validated']);
      return redirect()->route('admin.entry.view', ['id' => $entry->id]);
    }

    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'id' => 'required|exists:entries',
      ]);

      $entry->chase();
      session()->flash('alert', ['success' => 'A verification chaser email was sent to the entry']);
      return redirect()->route('admin.entry.view', ['id' => $entry->id]);
    }

    return view('admin.entry.chase', ['entry' => $entry]);
  }

  public function cancel(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'id' => 'required|exists:entries',
        'verify' => 'required'
      ]);

      $verification_string = "Cancel Entry " . $entry->id;
      if($validated["verify"] != $verification_string) {
        return back()->withErrors(['verify' => 'The entered verification string is incorrect']);
      }

      $entry->cancel($request->input('silent'));
      session()->flash('alert', ['success' => 'Entry cancelled']);
      return redirect()->route('admin.entries');
    }
    
    return view('admin.entry.cancel', ['entry' => $entry]);
  }

  public function notes(Request $request, $id)
  {
    $entry = Entry::findOrFail($id);

    if($request->isMethod('POST'))
    {
      $entry->notes = $request->input('notes');
      $entry->save();
      session()->flash('alert', ['success' => 'Notes updated']);
    }

    return view('admin.entry.notes', ['entry' => $entry]);
  }
}
