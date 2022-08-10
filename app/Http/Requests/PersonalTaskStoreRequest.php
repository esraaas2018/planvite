<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class PersonalTaskStoreRequest extends FormRequest
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
            'name'=>'required|max:255',
            'deadline'=>'nullable|date',
            'description' => 'string|nullable',
        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->validate();
        $validator->after(function ($validator) {
            if(Carbon::parse($this->deadline) < today())
            {
                $validator->errors()->add('deadline', 'deadline is not valid');
            }
        });
    }
}
