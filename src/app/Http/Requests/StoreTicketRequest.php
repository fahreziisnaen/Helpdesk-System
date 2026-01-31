<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Check authorization with fallback for shared hosting compatibility
        if (!$this->user()) {
            return false;
        }
        
        $role = strtolower(trim($this->user()->role ?? ''));
        return $role === 'user';
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }
}
