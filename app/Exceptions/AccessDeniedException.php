<?php
namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AccessDeniedException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Acesso Negado', 
            'status' => Response::HTTP_FORBIDDEN
        ], Response::HTTP_FORBIDDEN);
    }
}