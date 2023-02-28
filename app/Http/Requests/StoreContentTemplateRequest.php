<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentTemplateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'columns' => 'array',
            'prompts' => 'array',
            'maxTokens' => 'integer|numeric|gte:1|lte:4000',
            'csv' => 'max:200000|mimes:csv,xlsx,xls,ots',
        ];
    }
}
