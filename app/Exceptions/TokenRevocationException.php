<?php
namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TokenRevocationException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Erro ao revogar o token',
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}