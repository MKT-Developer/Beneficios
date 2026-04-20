<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pais;

class WidgetController extends Controller
{
    public function index(Request $request)
    {
        $codigoPais = $request->query('pais', 'mx'); // default a México

        // Pais seleccionado
        $pais = Pais::where('codigo', $codigoPais)->firstOrFail();

        // Pilares del país
        $pilares = $pais->pilares()
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        // Lista de países
        $paises = Pais::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('widget.index', compact(
            'pais',
            'pilares',
            'paises'
        ));
    }
}
