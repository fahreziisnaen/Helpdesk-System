<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by controller
    }

    public function rules(): array
    {
        return [
            'ticket_id' => 'required|exists:tickets,id',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }
}
