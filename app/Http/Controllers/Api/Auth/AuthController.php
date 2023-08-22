<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiCode;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\Auth\LoginUserRequest;
use App\Http\Requests\Api\Auth\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends AppBaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'register',
            'login',
        ]]);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \App\Http\Requests\Api\Auth\LoginUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request)
    {
        $input = $request->validated();
        if ($token = auth()->attempt($input)) {
            $user = auth()->user();
            $user->remember_token = $token;
            $user->save();

            return  $this->sendResponse([
                'token' => $token,
                'user' => $user,
            ], "User successfully logged in",  ApiCode::SUCCESS, 0);
        } else {
            //if authentication is unsuccessfully, notice how I return json parameters
            return $this->sendResponse(
                null,
                "Invalid Phone or Password",
                ApiCode::BAD_REQUEST,
                1
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Auth\StoreUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(StoreUserRequest $request)
    {
        $input = $request->validated();
        $user = User::create($input);

        $token = auth()->login($user);
        $user->remember_token = $token;
        $user->save();
        return $this->sendResponse(
            [
                'token' => $token,
                'user' => User::findOrFail($user->id)
            ],
            'User created successfully',
            ApiCode::CREATED,
            0
        );
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $user = auth()->user();
        $user->remember_token = Auth::refresh();
        $user->save();
        return $this->sendResponse(
            [
                'remember_token' => $user->remember_token,
            ],
            'updated info successfully',
            ApiCode::SUCCESS,
            0
        );
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $user = auth()->user();
        auth()->logout();
        return $this->sendResponse(
            null,
            __("User successfully logged out"),
            ApiCode::SUCCESS,
            0
        );
    }
}
