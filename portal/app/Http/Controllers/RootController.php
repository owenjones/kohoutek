<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\{County, Entry, Team};
use App\Mail\EntryReceived;

class RootController extends Controller
{
    public function index()
    {
      return view('root.index', [
        'avon' => County::where('code', 'avon')->first(),
        'bsg' => County::where('code', 'bsg')->first(),
        'sn' => County::where('code', 'sn')->first(),
        'signupsOpen' => settings()->get('signup_open', false),
        'spacesAvailable' => (Team::count() < settings()->get('max_teams')),
        'teamsAllowed' => (settings()->get('max_group_teams') > 1) ? ($num . " teams") : ("1 team"),
      ]);
    }

    public function signup(Request $request)
    {
      Validator::make($request->all(), [
        'district' => ['required', 'exists:districts,id'],
        'group' => ['exclude_if:county,bsg', 'exclude_if:county,sn', 'required', 'max:255'],
        'unit' => ['exclude_if:county,avon', 'required', 'max:255'],
        'troop' => ['nullable', 'max:255'],
        'name' => ['required', 'max:255'],
        'email' => ['required', 'max:255', 'confirmed', 'email', 'unique:entries,contact_email'],
        'rules' => ['required', 'accepted']
      ],
      [
        'district.required' => 'You need to select which District/Division you\'re in',
        'district.exists' => 'You need to select a valid District/Division',
        'group.required' => 'You need to enter your Group name',
        'group.max' => 'The Group name you\'ve entered is too long (max 255 characters)',
        'unit.required' => 'You need to enter your Unit name',
        'unit.max' => 'The Unit name you\'ve entered is too long (max 255 characters)',
        'name.required' => 'You need to enter a contact name',
        'name.max' => 'The contact name you\'ve entered is too long (max 255 characters)',
        'email.required' => 'You need to enter a contact email address',
        'email.max' => 'The contact email you\'ve entered is too long (max 255 characters)',
        'email.confirmed' => 'You need to confirm your contact email address',
        'email.email' => 'You need to enter a valid contact email address',
        'email.unique' => 'This email address has already been used to sign up for Kohoutek',
        'rules.accepted' => 'You need to accept the Kohoutek entry information and rules'
      ])->validate();

      if($request->county == "avon")
      {
        $group = $request->group;
        $troop = $request->troop;
      }
      else
      {
        $group = $request->unit;
        $troop = false;
      }

      $entry = Entry::create([
        'district_id' => $request->district,
        'group' => $group,
        'troop' => $troop,
        'contact_name' => $request->name,
        'contact_email' => $request->email,
        'auth_token' => Entry::generateToken()
      ]);

      for($i = 0; $i < settings()->get('initial_teams', 0); $i++)
      {
        Team::create([
          'name' => $entry->name,
          'entry_id' => $entry->id
        ]);
      }

      $entry->received();
      return "OK";
    }
}
