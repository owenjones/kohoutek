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

  public function county(): County
  {
    return $this->belongsTo(County::class)->first();
  }

  public function entries(): HasMany
  {
    return $this->hasMany(Entry::class);
  }
}
