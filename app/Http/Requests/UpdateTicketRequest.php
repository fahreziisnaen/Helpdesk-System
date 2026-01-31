<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by policy
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:open,in_progress,solved,closed',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }
}
