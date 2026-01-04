<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailViewResponse as VerifyEmailViewResponseContract;

class VerifyEmailViewResponse implements VerifyEmailViewResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        return view('auth.verify-email');
    }
}
