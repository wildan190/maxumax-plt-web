<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\ConfirmPasswordViewResponse as ConfirmPasswordViewResponseContract;

class ConfirmPasswordViewResponse implements ConfirmPasswordViewResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        return view('auth.confirm-password');
    }
}
