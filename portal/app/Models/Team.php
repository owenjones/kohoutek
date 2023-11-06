<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Team extends Model
{
  protected $fillable = ['name', 'entry_id'];

  protected function code(): Attribute
  {
    return Attribute::make(
      get: fn () => "K-" . (string)($this->id)
    );
  }
}
