<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterViewResponse as RegisterViewResponseContract;

class RegisterViewResponse implements RegisterViewResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        return view('auth.register');
    }
}
