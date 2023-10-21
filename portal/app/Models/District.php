<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
  protected $fillable = [
    'name',
    'county_id'
  ];
  public $timestamps = false;

  public function county(): BelongsTo
  {
    return $this->belongsTo(County::class);
  }

  public function entries(): HasMany
  {
    return $this->hasMany(Entry::class);
  }
}
