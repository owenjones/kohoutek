<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

use App\Mail\EntryReceived;

class Entry extends Model
{
  protected $fillable = [
    'district_id',
    'group',
    'troop',
    'contact_name',
    'contact_email',
    'auth_token'
  ];

  public function district(): BelongsTo
  {
    return $this->belongsTo(District::class);
  }

  public function teams(): HasMany
  {
    return $this->hasMany(Team::class);
  }

  public function updateBalance(): integer
  {
    
  }

  public function entryReceived(): void
  {
    // Mail::to($this->contact_email)->queue(new EntryReceived($this));
  }
}
