<?php

namespace App\Http\Requests;

use App\Policies\TaskPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TaskPolicy::create(Auth::user(), $this->route()->sprint);
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
            'user_id'=>['int','nullable','exists:participants,user_id'],
            'priority'=>  ['nullable','in:low,medium,high'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->validate();
        $validator->after(function ($validator) {
            if(Carbon::parse($this->deadline)->toDateString() > $this->route()->sprint->deadline)
            {
                $validator->errors()->add('deadline', 'cannot add a task with a date after sprint deadline date');
            }
            if(Carbon::parse($this->deadline)->toDateString() < today()->toDateString())
            {
                $validator->errors()->add('deadline', 'cannot add a task with a date before today');
            }
        });
    }
}
