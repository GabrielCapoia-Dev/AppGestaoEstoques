<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class PermissaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissao)
    {
        $usuario = JWTAuth::parseToken()->authenticate();

        $grupoPermissoes = explode('|', $permissao);

        if (!in_array($usuario->permissao, $grupoPermissoes)) {
            return response()->json([
                'error' => true,
                'message' => 'Acesso negado'
            ], 403);
        }

        return $next($request);
    }
}
    