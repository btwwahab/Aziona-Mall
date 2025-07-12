<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:categories,name',
            'slug' => 'nullable|unique:categories,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name already exists. Please choose another one.',
            'slug.unique' => 'The slug must be unique.',
            'image.required' => 'Please upload an image for the category.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Allowed image formats: jpeg, png, jpg, gif, svg.',
            'image.max' => 'The image size must not exceed 2MB.',
            'status.required' => 'Please select a status for the category.',
            'status.boolean' => 'Invalid status value. It should be Active or Inactive.',
        ];
    }
}
