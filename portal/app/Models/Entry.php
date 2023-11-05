<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

  protected static function generateToken()
  {
    return Str::random(30);
  }

  # Relationships
  public function district(): District
  {
    return $this->belongsTo(District::class)->first();
  }

  public function teams(): HasMany
  {
    return $this->hasMany(Team::class);
  }

  # Attributes
  protected function loginLink(): Attribute
  {
    return Attribute::make(
      get: fn () => route('portal.login', [
        'id' => $this->id,
        'token' => $this->auth_token,
      ])
    );
  }

  # Events
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

  public function resendLoginLink()
  {
    Mail::to($this->contact_email)->queue(new ResendLink($this));
  }
}
