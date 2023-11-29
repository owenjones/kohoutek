<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;
use App\Models\{Entry, Update};
use App\Mail\EntryContact;

class UpdateController extends Controller
{
  public function index()
  {
    $updates = Update::orderBy('created_at', 'desc')->simplePaginate(10);
    return view('admin.update.list', ['updates' => $updates]);
  }

  public function new(Request $request)
  {
    if($request->isMethod('POST'))
    {
      $validated = $request->validate([
        'title' => 'required|max:50',
        'message' => 'required'
      ]);

      Update::create($validated);

      Entry::each(function ($entry) use ($validated) {
        Mail::to($entry->contact_email)->queue(new EntryContact($entry, 
        $validated['title'], 
        $validated['message']));
      });

      session()->flash('alert', ['success' => 'Update sent to all entries']);
      return redirect()->route('admin.updates');
    }
    else
    {
      return view('admin.update.new');
    }
  }
}
