<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

use App\Exceptions\AccessDeniedException;

class Role
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check() || $this->getUserRole() !== $role) {
            throw new AccessDeniedException();
        }
        
        return $next($request);
    }

    private function getUserRole(): string
    {
        return Auth::user()->administrator ? 'admin' : 'employee';
    }
}
