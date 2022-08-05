<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
}
