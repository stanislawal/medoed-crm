<?php

namespace App\Http\Requests\ProjectService;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name'                  => 'required|string',
            'project_id'            => 'required|integer',
            'service_type_id'       => 'required|integer',
            'all_price'             => 'required|numeric',
            'accrual_this_month'    => 'required|numeric',
            'task'                  => 'required|string',
            'specialist_service_id' => 'nullable|array',
            'created_at'            => 'nullable|date:Y-m-d',
        ];
    }
}
