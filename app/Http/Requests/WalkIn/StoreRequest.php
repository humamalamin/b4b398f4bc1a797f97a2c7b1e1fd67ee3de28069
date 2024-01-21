<?php

namespace App\Http\Requests\WalkIn;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'table_id' =>  ['required', 'exists:table_models,id'],
            'request_date' => ['required', 'date'],
            'request_time' => ['required', 'date_format:H:i:s'],
            'username' => ['required'],
        ];
    }
}
