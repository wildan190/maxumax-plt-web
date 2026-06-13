<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
            'items.*.image' => 'required|image|max:5120', // Increased to 5MB
            'items.*.is_highlight' => 'sometimes|boolean',
            'items.*.description' => 'nullable|string',
        ];
    }
}
