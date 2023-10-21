<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\{County, Entry};

class RootController extends Controller
{
    public function index()
    {
      return view('root.index', [
        'avon' => County::where('code', 'avon')->first(),
        'bsg' => County::where('code', 'bsg')->first(),
        'sn' => County::where('code', 'sn')->first()
      ]);
    }

    public function receive_signup(Request $request)
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

      Entry::create([
        'district_id' => $request->district,
        'group' => $group,
        'troop' => $troop,
        'contact_name' => $request->name,
        'contact_email' => $request->email,
        'auth_token' => Str::random(255)
      ]);

      return "OK";
    }
}
