<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
