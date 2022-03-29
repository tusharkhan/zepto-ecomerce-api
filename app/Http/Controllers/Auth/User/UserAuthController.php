<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param UserLoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = Auth::guard('web')->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return sendError(
            'Unauthorized',
            ['error' => 'Unauthorized attempt'],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return sendResponse(
            'Data retrieved successfully',
            $this->guard()->user(),
            Response::HTTP_OK,
        );
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return sendResponse(
            'Successfully logged out',
            [],
            Response::HTTP_OK,
        );
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return sendResponse(
            'Successfully logged in',
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60 * 24 * 2,
                'user' => $this->guard()->user()
            ],
            Response::HTTP_OK,
        );
    }


    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegistrationRequest $userRegistrationRequest) {

        $user = User::create([
            'name'              => $userRegistrationRequest->name,
            'email'             => $userRegistrationRequest->email,
            'password'          => Hash::make($userRegistrationRequest->password),
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        return sendResponse(
            'User created successfully',
            $user,
            Response::HTTP_OK,
        );
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('web');
    }
}
