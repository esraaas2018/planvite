<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

/**
 * @property mixed $name
 * @property mixed $description
 * @property mixed $deadline
 */
class ProjectStoreRequest extends FormRequest
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
            'name'=>['required','string'],
            'deadline'=>['required','date'],
            'description'=>['nullable','string'],
            'statuses' => ['nullable', 'array', 'max:7'],
            'statuses.*' => ['string', 'max:20']
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->validate();
        $validator->after(function ($validator) {
            if(Carbon::parse($this->deadline) <= today())
            {
                $validator->errors()->add('deadline', 'cannot add a project with a date before tomorrow');
            }
        });
    }
}
