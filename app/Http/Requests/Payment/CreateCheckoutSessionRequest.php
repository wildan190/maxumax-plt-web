<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckoutSessionRequest extends FormRequest
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
            'shipping_courier_name' => 'nullable|string|max:255',
            'shipping_courier_logo' => 'nullable|string|max:500',
            'shipping_service_name' => 'nullable|string|max:255',
            'shipping_service_id' => 'nullable|string|max:255',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipping_source' => 'nullable|string|in:myparcelasia,easyparcel,delyva,self_collection',
        ];
    }
}
