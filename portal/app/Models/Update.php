<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Update extends Model
{
  protected $fillable = ['title', 'message'];

  public function formatted(): Attribute
  {
    return Attribute::make(
      get: fn () => Str::of($this->message)->markdown()
    );
  }

  public function date(): Attribute
  {
    return Attribute::make(
      get: fn () => ($this->created_at)->diffForHumans()
    );
  }
}
