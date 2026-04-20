<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pilar;
use App\Models\Pais;


class PilarController extends Controller
{

    /**
     * Obtener pilares de un país.
     * Si no se pasa código, por defecto es 'mx'.
     */
    public function byCodigo($codigo)
    {
        $pais = Pais::where('codigo', $codigo)->first();

        if (!$pais) {
            return response()->json([
                'error' => 'País no encontrado',
                'codigo_enviado' => $codigo
            ], 404);
        }

        return $pais->pilares()
            ->where('activo', true)
            ->orderBy('orden')
            ->get(['nombre', 'slug', 'descripcion', 'icono']);
    }
}
