<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Payment extends Model
{
  protected $fillable = [
    'type',
    'reference',
    'amount_pounds',
    'amount_pence',
    'entry_id'
  ];

  protected function amount(): Attribute
  {
    return Attribute::make(
      get: function () {
        $pence = ($this->amount_pence > 0) ? ("." . $this->amount_pence) : "";
        return "£" . $this->amount_pounds . $pence;
      }
    );
  }
}
