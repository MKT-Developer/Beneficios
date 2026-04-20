<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pilar;

class BeneficioController extends Controller
{
    public function byPilar($slug)
    {
        // Buscar pilar por slug
        $pilar = Pilar::where('slug', $slug)->first();

        if (!$pilar) {
            return response()->json([
                'error' => 'Pilar no encontrado',
                'slug_enviado' => $slug
            ], 404);
        }

        // Traer todos los beneficios activos de ese pilar con sus ubicaciones (ciudades)
        $beneficios = $pilar->beneficios()
            ->where('activo', true)
            ->orderBy('orden')
            ->with('ubicaciones') // traemos ciudades
            ->get()
            ->map(function ($beneficio) {
                return [
                    'id' => $beneficio->id,
                    'nombre' => $beneficio->nombre,
                    'descripcion' => $beneficio->descripcion,
                    'condiciones' => $beneficio->condiciones,
                    // Logo completo desde storage
                    'logo' => $beneficio->logo ? url('storage/beneficios/' . $beneficio->logo) : null,
                    // Contacto
                    'redsocial' => $beneficio->redsocial ?? null,
                    'sitio' => $beneficio->sitio ?? null,
                    'telefono' => $beneficio->telefono ?? null,
                    'correo' => $beneficio->correo ?? null,
                    // Ubicaciones disponibles
                    'ubicaciones' => $beneficio->ubicaciones->map(fn($u) => [
                        'id' => $u->id,
                        'nombre' => $u->nombre,
                    ]),
                ];
            });

        return response()->json([
            'pilar' => [
                'nombre' => $pilar->nombre,
                'descripcion' => $pilar->descripcion,
                'icono' => $pilar->icono ? url('storage/beneficios/' . $pilar->icono) : null,
            ],
            'beneficios' => $beneficios,
        ]);
    }
}
