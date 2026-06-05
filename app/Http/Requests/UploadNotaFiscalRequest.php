<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UploadNotaFiscalRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'xml' => [
                'required',
                'file',
                'mimes:xml',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'xml.required' => 'O arquivo XML é obrigatório.',
            'xml.file'     => 'O campo deve ser um arquivo.',
            'xml.mimes'    => 'O arquivo deve ser do tipo XML.',
            'xml.max'      => 'O arquivo não pode ser maior que 2MB.',
        ];
    }
}
