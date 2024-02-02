<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\{Payment, Team};

class PaymentController extends Controller
{
  public function index()
  {
    $payments = Payment::all();
    $pence = $payments->sum('amount_pence');
    $pounds = $payments->sum('amount_pounds') + intval(($pence / 100));
    $total = str($pounds) . "." . Str::padLeft(($pence % 100), 2, '0');

    return view('admin.payments.list', [
      'payments' => $payments,
      'total' => $total,
    ]);
  }

  public function outstanding()
  {
    return view('admin.payments.outstanding', [
      'teams' => Team::where('payment_received', false)->orderBy('entry_id')->get()
    ]);
  }
}
