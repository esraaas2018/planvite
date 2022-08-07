<?php

namespace App\Http\Requests;

use App\Policies\TaskPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\True_;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TaskPolicy::Update(Auth::user(),$this->route()->task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>['nullable','string'],
            'deadline'=>['nullable','date'],
            'description'=>['nullable','string'],
            'priority'=>  ['nullable','in:low,medium,high,severe'],
            'user_id'=>['int','nullable','exists:participants,user_id'],
        ];
    }
}
