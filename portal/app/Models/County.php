<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class County extends Model
{
  protected $fillable = [
    'code',
    'name',
  ];

  public $timestamps = false;

  public function districts(): HasMany
  {
    return $this->hasMany(District::class);
  }

  protected function type(): Attribute
  {
    return Attribute::make(
      get: fn () => ($this->code == "avon") ? "Scouting" : "Guiding"
    );
  }
}
