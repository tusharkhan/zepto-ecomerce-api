<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param AdminLoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = Auth::guard('api')->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return sendError(
            'Unauthorized',
            Response::HTTP_UNAUTHORIZED,
            ['error' => 'Unauthorized attempt']
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
            Response::HTTP_OK,
            $this->guard()->user()
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
            Response::HTTP_OK,
            []
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
            Response::HTTP_OK,
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60 * 24 * 2,
                'user' => $this->guard()->user()
            ]
        );
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
