<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preorder_id' => 'required|exists:preorders,id',
            'type' => 'required|in:refund,replacement',
            'reason' => 'required|string|min:10|max:1000',
        ];
    }
}
