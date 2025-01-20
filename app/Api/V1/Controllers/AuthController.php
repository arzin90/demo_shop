<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\User\LoginRequest;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseApiController
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (Auth::attempt($validated)) {
            $auth = auth();
            $user = $auth->user();
            $token = JWTAuth::claims(['role' => $user->type])->fromUser($user);

            return $this->generateToken($token, $auth);
        }

        return $this->sendError(__('auth.login_failed'), Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return $this->sendResponse(['access_token' => $newToken], Response::HTTP_CREATED);
        } catch (TokenInvalidException $e) {
            return $this->sendError(__('auth.invalid_token'), Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @param string $token
     * @param AuthManager $auth
     * @return JsonResponse
     */
    protected function generateToken(string $token, AuthManager $auth): JsonResponse
    {
        return $this->sendResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $auth->factory()->getTTL(),
            'user' => $auth->user(),
        ]);
    }
}
