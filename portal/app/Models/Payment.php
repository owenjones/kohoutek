<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Entry;

class Payment extends Model
{
  protected $fillable = [
    'type',
    'reference',
    'amount_pounds',
    'amount_pence',
    'entry_id'
  ];

  public function entry(): Entry
  {
    return $this->belongsTo(Entry::class)->first();
  }


  protected function amount(): Attribute
  {
    return Attribute::make(
      get: function () {
        $pence = Str::padLeft($this->amount_pence, 2, '0');
        return "£" . $this->amount_pounds . "." . $pence;
      }
    );
  }
}
