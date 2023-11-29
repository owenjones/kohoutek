<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
  protected $fillable = ['name', 'entry_id'];

  public function entry(): Entry
  {
    return $this->belongsTo(Entry::class)->first();
  }

  protected function code(): Attribute
  {
    return Attribute::make(
      get: fn () => "K-" . (string)($this->id)
    );
  }
}
