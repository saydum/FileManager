<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
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
            'name' => 'required|string|max:550',
            'path' => 'required|string|max:550',
            'directory_id' => 'required|integer|exists:directories,id',
            'user_id' => 'required|integer|exists:users,id',
            'is_public' => 'required|boolean',
            'files' => 'required|array',
            'files.*' => 'file|max:10240'
        ];
    }
}
