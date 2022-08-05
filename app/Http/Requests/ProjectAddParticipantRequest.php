<?php

namespace App\Http\Requests;

use App\Policies\ProjectPolicy;
use http\Client\Curl\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed $email
 */
class ProjectAddParticipantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ProjectPolicy::addParticipant(Auth::user(), $this->route()->project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email']
        ];
    }

}
