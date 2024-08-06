<?php

namespace App\Http\Requests\Directory;

use Illuminate\Foundation\Http\FormRequest;

class StoreDirectoryRequest extends FormRequest
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
            'path' => 'required|string|unique:directories|max:550',
            'user_id' => 'required|integer|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле имя обязательно для заполнения.',
            'path.required' => 'Поле путь обязательно для заполнения.',
            'path.unique' => 'Данный путь уже существует.',
        ];
    }
}
