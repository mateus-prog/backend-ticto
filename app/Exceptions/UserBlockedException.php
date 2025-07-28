<?php
namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserBlockedException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Usuário bloqueado.',
            'status' => Response::HTTP_UNAUTHORIZED
        ], Response::HTTP_UNAUTHORIZED);
    }
}