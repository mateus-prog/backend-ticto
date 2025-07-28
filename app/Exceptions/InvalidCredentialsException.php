<?php
namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialsException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Usuário e/ou senha inválidos.',
            'status' => Response::HTTP_UNAUTHORIZED
        ], Response::HTTP_UNAUTHORIZED);
    }
}