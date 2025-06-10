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
            'project_id'            => 'required|integer',
            'service_type_id'       => 'required|integer',
            'project_theme'         => 'required|string',
            'reporting_data'        => 'required|date',
            'terms_payment'         => 'required|string',
            'region'                => 'required|string',
            'all_price'             => 'required|numeric',
            'accrual_this_month'    => 'required|numeric',
            'task'                  => 'required|string',
            'specialist_service_id' => 'required|array',

            'link_to_work_plan'     => 'nullable|string',
        ];
    }
}
