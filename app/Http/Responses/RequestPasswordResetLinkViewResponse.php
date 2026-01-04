<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse as RequestPasswordResetLinkViewResponseContract;

class RequestPasswordResetLinkViewResponse implements RequestPasswordResetLinkViewResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        return view('auth.forgot-password');
    }
}
