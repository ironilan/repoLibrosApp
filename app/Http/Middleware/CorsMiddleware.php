<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $headers = [
            'Access-Control-Allow-Origin'      => 'http://localhost:5173', // React
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With',
            'Access-Control-Allow-Credentials' => 'true', // Permitir cookies
        ];

        // Si la solicitud es OPTIONS (preflight), devolver respuesta con encabezados CORS
        if ($request->isMethod('OPTIONS')) {
            return response()->json('CORS Preflight OK', 200, $headers);
        }

        $response = $next($request);

        // Agregar los encabezados a la respuesta
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
