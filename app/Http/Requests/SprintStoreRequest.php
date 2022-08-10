<?php

namespace App\Http\Requests;

use App\Policies\SprintPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class SprintStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return SprintPolicy::create(Auth::user(), $this->route()->project);
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
            'description'=>['nullable','string']
        ];
    }
    public function withValidator(Validator $validator)
    {
//        dd(Carbon::parse($this->deadline)->subDays(0) > $this->route()->project->deadline);
        $validator->validate();
        $validator->after(function ($validator) {
            if(Carbon::parse($this->deadline)->toDateString() > Carbon::parse($this->route()->project->deadline)->toDateString())
            {
                $validator->errors()->add('deadline', 'cannot add a sprint with a date after project deadline date');
            }
            if(Carbon::parse($this->deadline)->toDateString() < today()->toDateString())
            {
                $validator->errors()->add('deadline', 'cannot add a sprint with a date before today');
            }
        });
    }
}
