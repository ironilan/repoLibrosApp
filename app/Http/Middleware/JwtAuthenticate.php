<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Intenta autenticar con JWT
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token expired'], Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Token invalid'], Response::HTTP_UNAUTHORIZED);
        } catch (TokenBlacklistedException $e) {
            return response()->json(['message' => 'Token blacklisted'], Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
