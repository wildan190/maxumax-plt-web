<?php

namespace App\Http\Requests\Preorder;

use Illuminate\Foundation\Http\FormRequest;

class StorePreorderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'items' => 'required|array',
            'items.*.quantity_ss' => 'nullable|integer|min:0',
            'items.*.quantity_ls' => 'nullable|integer|min:0',
            'items.*.namesets_ss' => 'nullable|array',
            'items.*.namesets_ss.*.key' => 'required_with:items.*.namesets_ss|string',
            'items.*.namesets_ss.*.value' => 'required_with:items.*.namesets_ss|string',
            'items.*.namesets_ls' => 'nullable|array',
            'items.*.namesets_ls.*.key' => 'required_with:items.*.namesets_ls|string',
            'items.*.namesets_ls.*.value' => 'required_with:items.*.namesets_ls|string',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'currency' => 'nullable|string|in:MYR,SGD,IDR,BND',
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
