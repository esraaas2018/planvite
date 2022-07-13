<?php

namespace App\Http\Requests;

use App\Policies\PersonalTaskPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PesronalTaskChangeStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return PersonalTaskPolicy::access(Auth::user(), $this->route()->personal_task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'completed'=>'required|boolean'
        ];
    }
}
