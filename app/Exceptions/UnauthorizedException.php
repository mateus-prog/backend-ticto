<?php
namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Não Autorizado',
            'status' => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED);
    }
}