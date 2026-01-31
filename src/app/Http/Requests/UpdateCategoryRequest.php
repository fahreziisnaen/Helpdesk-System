<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Check authorization with fallback for shared hosting compatibility
        if (!$this->user()) {
            return false;
        }
        
        $role = strtolower(trim($this->user()->role ?? ''));
        return $role === 'admin';
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $categoryId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
