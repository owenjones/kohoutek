<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entry;

class PortalController extends Controller
{
    public function login()
    {
      # when login link followed
      # check ID and auth token
      # check if not verified - verify
      # force login and redirect
    }

    public function index()
    {
      $entry = Entry::first(); # for testing - just grab first in db

      return view('portal.index', [
        'entry' => $entry
      ]);
    }
}
