<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PlaceorderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'                => 'required|string|max:255',
            'email'               => 'required|email',
            'phone'               => 'required|string|max:20',
            'address'             => 'required|string',
            'city'                => 'required|string',
            'country'             => 'required|string',
            'postcode'            => 'required|string|max:10',
            'subtotal'            => 'required|numeric',
            'total'               => 'required|numeric',
            'payment_method'      => 'required|string|exists:payment_methods,name',
            'items'               => 'required|array',
            'items.*.product_id'  => 'required|exists:products,id',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.price'       => 'required|numeric|min:0',
        ];

        if ($this->payment_method === 'Bank Transfer') {
            $rules['payment_screenshot'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }
}
