<?php

namespace Modules\Test\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportExcelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'excel_file.required' => 'Please upload an Excel file.',
            'excel_file.file' => 'The uploaded file is invalid.',
            'excel_file.mimes' => 'The file must be an Excel file (xlsx, xls) or CSV.',
            'excel_file.max' => 'The file size must not exceed 10MB.',
        ];
    }
}
