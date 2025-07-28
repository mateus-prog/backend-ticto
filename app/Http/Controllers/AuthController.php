<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Exception;

use App\Http\Requests\LoginRequest;
use App\Services\AuthenticateService;

class AuthController extends Controller
{
    protected AuthenticateService $authService;

    public function __construct(AuthenticateService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request->only('email', 'password'));
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->authService->logout($request);
    }

    public function unauthorized(): JsonResponse
    {
        abort(401, 'Não autorizado.');
    }

    public function change(Request $request): JsonResponse
    {
        try {
            $this->authService->changePassword($request->only('password'));

            return response()->json([
                'message' => 'Senha alterada com sucesso',
                'status' => Response::HTTP_OK,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode() ?: Response::HTTP_BAD_REQUEST,
            ], $e->getCode() ?: Response::HTTP_BAD_REQUEST);
        }
    }
}
