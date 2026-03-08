<?php

namespace App\Http\Requests\Preorder;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutCodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'currency' => 'nullable|string|in:MYR,BND,IDR,SGD',
            'notes' => 'nullable|string',
            'shipping_courier_name' => 'required|string|max:255',
            'shipping_courier_logo' => 'nullable|string|max:255',
            'shipping_service_name' => 'required|string|max:255',
            'shipping_service_id' => 'required|string|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_source' => 'nullable|string|in:myparcelasia,easyparcel,delyva',
        ];
    }
}
