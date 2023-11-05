<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

use App\Mail\{EntryReceived, EntryVerified, ResendLink};

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

  public function received()
  {
    Mail::to($this->contact_email)->queue(new EntryReceived($this));
  }

  public function verify()
  {
    $this->verified = true;
    $this->save();
    Mail::to($this->contact_email)->queue(new EntryVerified($this));
  }

  public function getLoginLink()
  {
    return route('portal.login', [
      'id' => $this->id,
      'token' => $this->auth_token,
    ]);
  }

  public function resendLoginLink()
  {
    Mail::to($this->contact_email)->queue(new ResendLink($this));
  }
}
