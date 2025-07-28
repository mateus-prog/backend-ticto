<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UserBlockedException;
use App\Exceptions\TokenRevocationException;

use App\Http\Resources\UserResource;
use App\Services\UserService;

class AuthenticateService
{
    protected UserService $userService;
    protected int $maxAttempts = 3;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(array $credentials): JsonResponse
    {
        $user = $this->getUserByEmail($credentials['email']);

        if (!$this->authenticate($credentials)) {
            return $this->handleInvalidLoginAttempt($user);
        }

        if ($user->blocked) {
            Auth::logout();
            throw new UserBlockedException();
        }

        return $this->respondWithToken($user);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Token revogado com sucesso.',
                'status' => Response::HTTP_OK,
            ]);
        } catch (\Exception $e) {
            throw new TokenRevocationException();
        }
    }

    public function changePassword(array $input): void
    {
        $user = Auth::user();

        if (isset($input['password'])) {
            $this->userService->update($user->id, $input);
        } else {
            throw new \InvalidArgumentException('Senha não informada.');
        }
    }

    private function getUserByEmail(string $email): object
    {
        $user = $this->userService->findByField('email', $email, 'first');

        if (!$user) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }

    private function authenticate(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    private function handleInvalidLoginAttempt(object $user): JsonResponse
    {
        $attempts = ++$user->attempts;
        $blocked = $attempts >= $this->maxAttempts;

        $this->userService->update($user->id, [
            'attempts' => $attempts,
            'blocked' => $blocked,
        ]);

        Auth::logout();
        throw new InvalidCredentialsException();
    }

    private function resetLoginAttempts(object $user): void
    {
        $this->userService->update($user->id, [
            'attempts' => 0,
            'blocked' => false,
        ]);
    }

    private function respondWithToken(object $user): JsonResponse
    {
        $this->resetLoginAttempts($user);

        return response()->json([
            'message' => 'Autorizado',
            'status' => Response::HTTP_OK,
            'token' => $user->createToken('API Token')->plainTextToken,
            'user' => new UserResource($user)
        ]);
    }
}
