<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Mail\EntryReceived;

class Entry extends Authenticatable
{
  protected $fillable = [
    'district_id',
    'group',
    'troop',
    'contact_name',
    'contact_email',
    'auth_token'
  ];

  public function district(): District
  {
    return $this->belongsTo(District::class)->first();
  }

  public function teams(): HasMany
  {
    return $this->hasMany(Team::class);
  }

  public function updateBalance(): integer
  {
    
  }

  public function verify()
  {
    $this->verified = true;
    $this->save();
  }
}
