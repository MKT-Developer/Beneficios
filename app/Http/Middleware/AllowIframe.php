<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowIframe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $response = $next($request);

        // Eliminar X-Frame-Options
        $response->headers->remove('X-Frame-Options');

        //Sitios en especificos
        $response->headers->set(
            'Content-Security-Policy',
            "frame-ancestors 'self' https://beneficios.meracorporation.com/ https://mera.supercompany.com/ http://127.0.0.1:8000/"
        );

        // O permitir desde cualquier dominio
        // $response->headers->set(
        //     'Content-Security-Policy',
        //     "frame-ancestors *"
        // );

        return $response;

        // return $next($request);
    }
}
