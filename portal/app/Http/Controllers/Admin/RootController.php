<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\{District, Entry, Team};

class RootController extends Controller
{
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
      'entries' => $entries->count(),
      'scouts' => $scouts->count(),
      'guides' => $guides->count(),
      'teams' => Team::count(),
    ]);
  }
}
