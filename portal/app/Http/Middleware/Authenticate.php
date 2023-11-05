<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */
  protected function redirectTo(Request $request): ?string
  {
    if($request->is("portal*"))
    {
      return $request->expectsJson() ? null : route('portal.need-login');
    }
    else
    {
      return $request->expectsJson() ? null : route('admin.login');
    }
  }
}
