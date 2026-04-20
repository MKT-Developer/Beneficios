<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ToggleController extends Controller
{
    public function __invoke($model, $id)
    {
        $map = [
            'pais' => \App\Models\Pais::class,
            'pilar' => \App\Models\Pilar::class,
            'beneficio' => \App\Models\Beneficio::class,
            'ubicacion' => \App\Models\Ubicacion::class,
        ];

        abort_unless(isset($map[$model]), 404);

        $item = $map[$model]::findOrFail($id);

        if (!isset($item->activo)) {
            return response()->json([
                'success' => false,
                'message' => 'Modelo no tiene campo activo'
            ], 422);
        }

        $item->activo = !$item->activo;
        $item->save();

        return response()->json([
            'success' => true,
            'activo' => $item->activo,
        ]);
    }
}
