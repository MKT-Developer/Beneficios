<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pilar;

class BeneficioController extends Controller
{

    public function byPilar($slug)
    {
        $pilar = Pilar::where('slug', $slug)
            ->with(['beneficios' => function ($q) {
                $q->where('activo', true)
                    ->orderBy('orden')
                    ->with('ubicaciones');
            }])
            ->first();

        if (!$pilar) {
            return response()->json([
                'error' => 'Pilar no encontrado',
                'slug_enviado' => $slug
            ], 404);
        }

        $beneficios = $pilar->beneficios->map(function ($b) {
            return [
                'id' => $b->id,
                'nombre' => $b->nombre,
                'descripcion' => $b->descripcion,
                'beneficios' => $b->beneficios,
                'condiciones' => $b->condiciones,

                'logo' => $b->logo
                    ? url('storage/beneficios/' . $b->logo)
                    : null,

                // CONTACTO NORMALIZADO
                'redsocial' => $b->redsocial
                    ? 'https://instagram.com/' . ltrim($b->redsocial, '@')
                    : null,

                'sitio' => $b->sitio,

                'telefono' => $b->telefono
                    ? 'tel:' . preg_replace('/\s+/', '', $b->telefono)
                    : null,

                'correo' => $b->correo
                    ? 'mailto:' . $b->correo
                    : null,

                'ubicaciones' => $b->ubicaciones->map(fn($u) => [
                    'id' => $u->id,
                    'nombre' => $u->nombre,
                ]),
            ];
        });

        return response()->json([
            'pilar' => [
                'nombre' => $pilar->nombre,
                'descripcion' => $pilar->descripcion,
                'icono' => $pilar->icono
                    ? url('storage/pilares/' . $pilar->icono)
                    : null,
            ],
            'beneficios' => $beneficios,
        ]);
    }

    // public function byPilar($slug)
    // {
    //     // Buscar pilar por slug
    //     $pilar = Pilar::where('slug', $slug)->first();

    //     if (!$pilar) {
    //         return response()->json([
    //             'error' => 'Pilar no encontrado',
    //             'slug_enviado' => $slug
    //         ], 404);
    //     }

    //     // Traer todos los beneficios activos de ese pilar con sus ubicaciones (ciudades)
    //     $beneficios = $pilar->beneficios()
    //         ->where('activo', true)
    //         ->orderBy('orden')
    //         ->with('ubicaciones') // traemos ciudades
    //         ->get()
    //         ->map(function ($beneficio) {
    //             return [
    //                 'id' => $beneficio->id,
    //                 'nombre' => $beneficio->nombre,
    //                 'descripcion' => $beneficio->descripcion,
    //                 'beneficios' => $beneficio->beneficios,
    //                 'condiciones' => $beneficio->condiciones,
    //                 // Logo completo desde storage
    //                 'logo' => $beneficio->logo ? url('storage/beneficios/' . $beneficio->logo) : null,
    //                 // Contacto
    //                 'redsocial' => $beneficio->redsocial
    //                     ? 'https://instagram.com/' . ltrim($beneficio->redsocial, '@')
    //                     : null,

    //                 'sitio' => $beneficio->sitio,

    //                 'telefono' => $beneficio->telefono
    //                     ? 'tel:' . preg_replace('/\s+/', '', $beneficio->telefono)
    //                     : null,

    //                 'correo' => $beneficio->correo
    //                     ? 'mailto:' . $beneficio->correo
    //                     : null,
    //                 // 'redsocial' => $beneficio->redsocial ?? null,
    //                 // 'sitio' => $beneficio->sitio ?? null,
    //                 // 'telefono' => $beneficio->telefono ?? null,
    //                 // 'correo' => $beneficio->correo ?? null,
    //                 // Ubicaciones disponibles
    //                 'ubicaciones' => $beneficio->ubicaciones->map(fn($u) => [
    //                     'id' => $u->id,
    //                     'nombre' => $u->nombre,
    //                 ]),
    //             ];
    //         });

    //     return response()->json([
    //         'pilar' => [
    //             'nombre' => $pilar->nombre,
    //             'descripcion' => $pilar->descripcion,
    //             'icono' => $pilar->icono ? url('storage/pilares/' . $pilar->icono) : null,
    //         ],
    //         'beneficios' => $beneficios,
    //     ]);
    // }
}
