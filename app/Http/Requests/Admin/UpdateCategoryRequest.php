<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        // Change from string 'NULL' to actual null
        $categoryId = $this->route('category')?->id ?? null;
    
        return [
            'name' => 'required|unique:categories,name,' . $categoryId,
           'slug' => 'nullable|unique:categories,slug,' . $categoryId,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|boolean',
        ];
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->name) {
            $this->merge([
                'slug' => \Str::slug($this->name)
            ]);
        }
    }
    
    
}
