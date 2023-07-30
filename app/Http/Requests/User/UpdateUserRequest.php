<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => [
                'nullable', // обязательное поле
                'string', // строка
            ],
            'full_name' => [
                'nullable', // обязательное поле
                'string', // строка
            ],
            'password' => [
                'nullable',
                'string',
                'min:1'
            ],
            'contact_info' => [
              'nullable',
              'string',
            ],
            'birthday' => [
                'nullable',
                'date',
            ],
            'role' => [
                'nullable',
                Rule::in(['Администратор', 'Менеджер', 'Автор']),
            ],
            'manager_salary' => [
              'nullable',
              'numeric'
            ],
            'duty' => [
                'nullable',
                'numeric'
            ],
            'link_author' => [
                'nullable',
                'string'
            ],

            'payment' => [
                'nullable',
                'string'
            ],
            'bank_id' => [
                'nullable',
                'numeric'
            ]
        ];
    }
}
