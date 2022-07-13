<?php

namespace App\Http\Requests;
use App\Policies\RatingPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return RatingPolicy::rate($this->route()->reviewed, $this->route()->project);
    }

    public function validationData()
    {
        return parent::validationData() + [
                'project_id' => $this->route()->project->id
            ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rating' => ['required','integer','min:0','max:5'],
            'project_id' => [Rule::unique('ratings', 'project_id')
                ->where('reviewed_id', $this->route()->reviewed->id)]
        ];
    }


}
