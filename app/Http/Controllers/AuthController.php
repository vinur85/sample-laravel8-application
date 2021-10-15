<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegister;
use App\Models\User;
use \Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Constructor function for AuthController
     */
    public function __construct()
    {

    }

    /**
     * Register User
     *
     * @param Request $request
     */
    public function register(UserRegister $request)
    {
        $user = User::create([
            'name'=> $request->name,
            'password'=> Hash::make($request->password),
            'email'=> $request->email,
        ]);

        return $this->sendResponse([], "Registered successfully!!!");
    }

    /**
     * Login to the application
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            $cookie = Cookie::forget('jwt');
            return $this->sendResponse([], 'Invalid Credentials!!', Response::HTTP_UNAUTHORIZED)
                ->withCookie($cookie);
        }
        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24);

        return $this->sendResponse([], "Logged in successfully!!!")
            ->withCookie($cookie);
    }

    /**
     * Logout current session
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return $this->sendResponse([], "Logged out successfully!!!")
            ->withCookie($cookie);
    }
}
