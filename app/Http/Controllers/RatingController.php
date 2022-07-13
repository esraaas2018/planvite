<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRateRequest;
use App\Http\Requests\EditRatingRequest;
use App\Http\Requests\UserRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Project;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{

    public  function rateUser(UserRatingRequest $request, Project $project, User $reviewed)
    {
             $rating = $reviewed->ratings()->create($request->validated()+
                 [   'reviewer_id' => Auth::id(),
                     'project_id'=>$project->id
                 ]
             );
             $reviewed->update(['rating' => null]);

             return apiResponse(new RatingResource($rating), 'Rated successfully.');

    }

    public function UserAverageRating(User $user)
    {
        $userAverageRating = $user->rating;
        return apiResponse($userAverageRating);
    }

    public function UserRatingCount(User $user)
    {
        $userRatingCount = $user->ratings()->count();
        return apiResponse($userRatingCount);

    }

    public function ratingPercent(User $user,$max = 5)
    {
        $quantity = $user->ratings()->count();
        $total =  $user->ratings()->sum('rating');
        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }
}
