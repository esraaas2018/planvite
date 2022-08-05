<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserSettingsSetRequest;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\FCMService;
use Google\Cloud\Storage\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function checkToken(){
        return apiResponse();
    }

    public function userNotifications(){
        $user = Auth::user();
        $notifications=  $user->notifications()->get();
        return apiResponse(NotificationResource::collection($notifications));
    }

    public function userInfo(){
        $user = Auth::user();
        return apiResponse(new UserResource($user));
    }

    public function settings(UserSettingsSetRequest $request)
    {
        $user = Auth::user();
        $user = tap($user)->update([
            'fcm_token' => $request->fcm_token,
            'language' => $request->language,
        ]);

        return apiResponse(new UserResource($user));
    }

    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number'=>$request->phone_number,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken('token')->plainTextToken;
        return apiResponse($token);
    }

    public function login(UserLoginRequest $request)
    {

        $user = User::query()->where('email', $request->email)->first();
//        if(!$user->email_verified_at){
//            if($request->otp){
//                $auth = app('firebase.auth');
//                $signInResult = $auth->signInWithEmailAndOobCode("edwardkarra@gmail.com", $request->otp);
//                if(!$signInResult)
//                    abort(403, "OTP Error");
//                else{
//                    $user->email_verified_at = now();
//                    $user->save();
//                }
//            }else{
//                abort(403, "Not verified account");
//            }
//        }

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('token')->plainTextToken;
            return apiResponse($token);
        } else {
            return apiResponse(null, "incorrect credentials", 401);
        }

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return apiResponse(null, 'logout successfully');
    }
}
