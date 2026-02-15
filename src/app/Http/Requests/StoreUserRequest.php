<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,teknisi,user',
            'phone' => 'nullable|string|max:20',
            'perusahaan' => 'nullable|string|max:255',
        ];
    }
}
